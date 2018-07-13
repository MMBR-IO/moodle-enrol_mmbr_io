<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
/** 
 * @package     enrol_mmbr
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright   Dmitry Nagorny
 */


require('../../config.php');
require_login();

$courseid   = required_param('courseid', PARAM_INT);
$instanceid = optional_param('instanceid', 0, PARAM_INT); 
$paymentid = optional_param('paymentid', null, PARAM_TEXT);


$course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);
$context = context_course::instance($course->id, MUST_EXIST);

$PAGE->set_context(context_system::instance()); 

$PAGE->set_url('/enrol/mmbr/enrol.php', array('courseid' => $course->id));

$return = new moodle_url('/enrol/index.php', array('id' => $course->id));
if (!enrol_is_enabled('mmbr')) {
    redirect($return);
}

$plugin = enrol_get_plugin('mmbr');
$instances = $plugin->enrol_get_instances($course->id,true);
// If there is only instance send to payment 
if (count($instances) == 1) {
    $instanceid = key($instances);
}
// If $instanceid is set got to payment form 
if ($instanceid > 0) {
    foreach ($instances as $key => $value) {
        if ($key === $instanceid){
            $instance = $value;
        }
    }
    $instance->cost = $plugin->get_cost_full($instance->cost);
    include_once "$CFG->dirroot/enrol/mmbr/forms/payment_form.php";
    $mform = new enrol_mmbr_payment_form(null, $instance);

    if ($data = $mform->get_data()) { 
        // If form submitted go and validate payment
       // var_dump($data);
       // die();
        $plugin->confirm_enrolment($data->paymentkey, $data->instanceid);
    }
    if ($mform->is_cancelled()) {
        redirect($return);

    } 
} else { // Else let user to choose enrolment 
    include_once "$CFG->dirroot/enrol/mmbr/forms/instance_form.php";
    $mform = new enrol_mmbr_instance_form(null, $instances);

    if ($mform->is_cancelled()) {
        redirect($return);
    } 
}

$PAGE->set_heading($course->fullname);
$PAGE->set_title(get_string('pluginname', 'enrol_mmbr'));

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('pluginname', 'enrol_mmbr'));
$mform->display();
echo $OUTPUT->footer();
