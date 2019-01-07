<?php
/**
 * This file is part of Moodle - http://moodle.org/
 * 
 * PHP version 7

 * Moodle is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * Moodle is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

 * @category Api_Calls
 * @package  Enrol_Mmbr.Io
 * @author   Dmitry Nagorny <dmitry.nagorny@mmbr.io>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @link     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require '../../config.php';
require_once 'lib.php';
require_once 'forms/edit_form.php';

$courseid = required_param('courseid', PARAM_INT);
$instanceid = optional_param('id', 0, PARAM_INT); 

$course = $DB->get_record('course', array('id'=>$courseid), '*', MUST_EXIST);
$context = context_course::instance($course->id, MUST_EXIST);

require_login($course);
require_capability('enrol/mmbr_io:config', $context);

$PAGE->set_url('/enrol/_io/edit.php', array('courseid' => $course->id, 'id' => $instanceid));
$PAGE->set_pagelayout('admin');

$returnurl = new moodle_url('/enrol/instances.php', array('id'=>$course->id));
if (!enrol_is_enabled('mmbr_io')) {
    redirect($returnurl);
}

$plugin = enrol_get_plugin('mmbr_io');

if ($instanceid) {
    $instance = $DB->get_record(
        'enrol',
        array('courseid' => $course->id, 'enrol' => 'mmbr_io', 'id' => $instanceid), '*', MUST_EXIST
    );
    $instance->cost = format_float($instance->cost, 2, true);
} else {
    require_capability('moodle/course:enrolconfig', $context);
    navigation_node::override_active_url(new moodle_url('/enrol/instances.php', array('id' => $course->id)));
    $instance               = new stdClass();
    $instance->id           = null;
    $instance->courseid     = $course->id;
    $instance->enrolperiod  = 0;
    $instance->enrolenddate = 0;
}

$mform = new enrol_mmbr_io_edit_form(null, array($instance, $plugin, $context));
if ($mform->is_cancelled()) {
    redirect($returnurl);
} else if ($data = $mform->get_data()) { // If form is submitted
    // Based on selected option choose enrolment duration // Not the best way, must be done properly later.
    // 0 = represents one time payment 
    // 1 = represents monthly subscription  
    $op = (int)$data->name;
    if ($op == 1) {
        $instance->enrolperiod = intval(31536000/12);
    } 
    
    // If id exists, means we updating existing instance
    if ($instance->id) {

        $instance->name             = $plugin->get_enrolment_options($data->name);  // Instance name
        $instance->status           = 0;                                            // Status -> active 
        $instance->cost             = round($data->price, 2)*100;                    // Price
        $instance->currency         = $data->currency;                              // Currency
        $instance->roleid           = 5;                                            // Role -> Student
        $instance->timemodified     = time();                                       // By default current time when modified
        $DB->update_record('enrol', $instance); 
        
        $context->mark_dirty(); // Reset caches here
        
        redirect($returnurl, get_string('enrolupdated', 'enrol_mmbr_io'), null, \core\output\notification::NOTIFY_SUCCESS);

    } else { // or create a new one
        $fields = array('status' => 0, 
                        'name' => $plugin->get_enrolment_options($data->name), 
                        'cost' => round($data->price, 2)*100,
                        'currency' => $data->currency,
                        'roleid' => 5, 
                        'enrolenddate' => $instance->enrolenddate,
                        'enrolperiod' => $instance->enrolperiod,
                    );
        include 'classes/observer.php';

        // Notify MMBR.IO about that new instance is created
        $observer = new enrol_mmbr_io_observer();
        $result =  $observer->new_enrolment_instance($fields, $course);
        if ($result && $result->success) {
            $plugin->add_instance($course, $fields);
            redirect($returnurl);
        } else {
            if (is_object($result)) {
                switch($result->errors) {
                case 'wrong_key':
                    \core\notification::error(get_string('mmbriokeyerror', 'enrol_mmbr_io'));
                    break;
                case 'miss_key': 
                    \core\notification::error(get_string('mmbriokeymiserror', 'enrol_mmbr_io'));
                    break;
                case 'server':
                    \core\notification::error(get_string('mmbrioservererror', 'enrol_mmbr_io'));
                    break;
                default:
                        \core\notification::error(get_string('mmbriodeferror', 'enrol_mmbr_io'));
                }
            } else {
                \core\notification::error(get_string('mmbriodeferror', 'enrol_mmbr_io'));
            }
        }         
    }
}

$PAGE->set_heading($course->fullname);
$PAGE->set_title(get_string('pluginname', 'enrol_mmbr_io'));

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('pluginname', 'enrol_mmbr_io'));
$mform->display();
echo $OUTPUT->footer();