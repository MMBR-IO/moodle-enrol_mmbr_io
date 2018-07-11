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
} else if ($data = $mform->get_data()) { // If form is submitted
    // Based on selected option choose enrolment duration
    $op = $data->name;
    if ($op == 1) {
        $instance->enrolenddate = time() + intval(31536000/12);
    } elseif ($op == 2) {
        $instance->enrolperiod = intval(31536000/12);
    } elseif ($op == 3) {
        $instance->enrolperiod = 31536000;
    }
    
    if ($instance->id){ // If id exists, means we updating existing instance
        $reset = ($instance->status != $data->status); // If they don't match reset enrol caches 

        $instance->name             = $plugin->get_enrolment_options($data->name);                      // Instance name
        $instance->status           = $data->status;                    //Status active/susp
        $instance->cost             = unformat_float($data->cost);      // One time payment cost
        $instance->currency         = $data->currency;                           // Default value for currency
        $instance->roleid           = $data->roleid;                    // Role when enroled
        $instance->timemodified     = time();                           // By default current time when modified
       // $instance->customint2       = $frequency;                // Payment frequency 
        $DB->update_record('enrol', $instance); 

        if ($reset) {
            $context->mark_dirty(); // Reset caches here
        }

    } else { // or create a new one
        $fields = array('status' => $data->status, 
                        'name' => $plugin->get_enrolment_options($data->name), 
                        'cost' => unformat_float($data->cost),
                        'currency' => $data->currency,
                        'roleid' => $data->roleid, 
                       // 'customint2' => $frequency
                    );
        require('classes/observer.php');
        $plugin->add_instance($course, $fields);

         // Nofify MMBR about that new instance is created
         $observer = new enrol_mmbr_observer();
         $observer->newEnrolmentInstance($fields, $course);
    }

    redirect($returnurl);
}

$PAGE->set_heading($course->fullname);
$PAGE->set_title(get_string('pluginname', 'enrol_mmbr'));

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('pluginname', 'enrol_mmbr'));
$mform->display();
echo $OUTPUT->footer();