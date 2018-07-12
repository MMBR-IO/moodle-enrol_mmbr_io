<?php
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
// if ($paymentid != null) {
//     
// } else 
if ($instanceid > 0) {
    foreach ($instances as $key => $value) {
        if ($key === $instanceid){
            $instance = $value;
        }
    }
    $instance->cost = $plugin->get_cost_full($instance->cost);
    include_once "$CFG->dirroot/enrol/mmbr/forms/payment_form.php";
    $mform = new enrol_mmbr_payment_form(null, $instance);
    // var_dump($instance);
    // die();

    if ($data = $mform->get_data()) {
        $plugin->confirm_enrolment($data->paymentid, $data->instanceid);
    }
    if ($mform->is_cancelled()) {
        redirect($return);

    } 
} else {
    include_once "$CFG->dirroot/enrol/mmbr/forms/mmbr_form.php";
    $mform = new enrol_mmbr_apply_form(null, $instances);

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
