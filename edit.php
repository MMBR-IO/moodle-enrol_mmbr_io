<?php 
require('../../config.php');
require_once('lib.php');
require_once('edit_form.php');

$courseid = required_param('courseid', PARAM_INT);
$instanceid = optional_param('id', 0, PARAM_INT); 


$course = $DB->get_record('course', array('id'=>$courseid), '*', MUST_EXIST);
$context = context_course::instance($course->id, MUST_EXIST);

require_login($course);
require_capability('enrol/mmbr:config', $context);

$PAGE->set_url('/enrol/mmbr/edit.php', array('courseid' => $course->id, 'id' => $instanceid));
$PAGE->set_pagelayout('admin');

$returnurl = new moodle_url('/enrol/instances.php', array('id'=>$course->id));
if (!enrol_is_enabled('mmbr')) {
    redirect($returnurl);
}

$plugin = enrol_get_plugin('mmbr');

if ($instanceid) {
    $instance = $DB->get_record('enrol',
    array('courseid' => $course->id, 'enrol' => 'mmbr', 'id' => $instanceid), '*', MUST_EXIST);
    $instance->cost = format_float($instance->cost, 2, true);
} else {
    require_capability('moodle/course:enrolconfig', $context);
    navigation_node::override_active_url(new moodle_url('/enrol/instances.php', array('id' => $course->id)));
    $instance = new stdClass();
    $instance->id       = null;
    $instance->courseid = $course->id;
}

$mform = new enrol_mmbr_edit_form(null, array($instance, $plugin, $context));
if($mform->is_cancelled()) {
    redirect($returnurl);
} else if ($data = $mform->get_data()) {
    if ($instance->id){
        $reset = ($instance->status != $data->status);

        $instance->status       = $data->status;
        $instance->name         = $data->name;
        $instance->cost         = unformat_float($data->cost);
        $instance->currency     = 'CAD';
        $instance->roleid       = $data->roleid;
        $instance->enrolperiod    = $data->enrolperiod;
        $instance->enrolstartdate = $data->enrolstartdate;
        $instance->enrolenddate   = $data->enrolenddate;
        $instance->timemodified   = time();
        $DB->update_record('enrol', $instance);

        if ($reset) {
            $context->mark_dirty();
        }

    } else {
        $fields = array('status' => $data->status, 'name' => $data->name, 'cost' => unformat_float($data->cost),
                        'currency' => "CAD", 'roleid' => $data->roleid, 'enrolperiod' => $data->enrolperiod, 'enrolstartdate' => $data->enrolstartdate, 'enrolenddate' => $data->enrolenddate);
        $plugin->add_instance($course, $fields);
    }

    redirect($returnurl);
}

$PAGE->set_heading($course->fullname);
$PAGE->set_title(get_string('pluginname', 'enrol_mmbr'));

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('pluginname', 'enrol_mmbr'));
$mform->display();
echo $OUTPUT->footer();