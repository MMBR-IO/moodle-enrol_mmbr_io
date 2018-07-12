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
 * @package    enrol_mmbr
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once($CFG->dirroot.'/enrol/mmbr/lib.php');

$id = optional_param('id', null, PARAM_INT);
$formaction = optional_param('formaction', null, PARAM_TEXT);
$userenrolments = optional_param_array('userenrolments', null, PARAM_INT);

require_login();

$manageurlparams = array();
if ($id == null) {
    $context = context_system::instance();
  //  require_capability('enrol/mmbr:manageapplications', $context);
    $pageheading = get_string('confirmusers', 'enrol_mmbr');
} else {
    $instance = $DB->get_record('enrol', array('id' => $id, 'enrol' => 'mmbr'), '*', MUST_EXIST);
    require_course_login($instance->courseid);
    $course = get_course($instance->courseid);
    $context = context_course::instance($course->id, MUST_EXIST);
   // require_capability('enrol/mmbr:manageapplications', $context);
    $manageurlparams['id'] = $instance->id;
    $pageheading = $course->fullname;
}

$manageurl = new moodle_url('/enrol/mmbr/manage.php', $manageurlparams);

$PAGE->set_context($context);
$PAGE->set_url($manageurl);
$PAGE->set_pagelayout('admin');
$PAGE->set_heading($pageheading);
$PAGE->navbar->add(get_string('confirmusers', 'enrol_mmbr'));
$PAGE->set_title(get_string('confirmusers', 'enrol_mmbr'));
$PAGE->requires->css('/enrol/mmbr/style.css');

if ($formaction != null && $userenrolments != null) {
    $enrolmmbr = enrol_get_plugin('mmbr');
    switch ($formaction) {
        case 'confirm':
            $enrolmmbr->confirm_enrolment($userenrolments);
            break;
        case 'wait':
            $enrolmmbr->wait_enrolment($userenrolments);
            break;
        case 'cancel':
            $enrolmmbr->cancel_enrolment($userenrolments);
            break;
    }
    redirect($manageurl);
}
