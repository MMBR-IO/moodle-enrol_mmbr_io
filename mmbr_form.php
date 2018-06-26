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
 * @author     MMBR
 */

defined('MOODLE_INTERNAL') || die();

require_once $CFG->libdir . '/formslib.php';
require_once $CFG->dirroot . '/user/editlib.php';
require_once $CFG->dirroot . '/user/profile/lib.php';

class enrol_mmbr_apply_form extends moodleform
{
    protected $instance;

    //This might be needed in future
    /**
     * Overriding this function to get unique form id for multiple apply enrolments
     *
     * @return string form identifier
     */
    // protected function get_form_identifier() {
    //     $formid = $this->_customdata->id.'_'.get_class($this);
    //     return $formid;
    // }

    public function definition() {
        global $USER, $DB, $PAGE;

        $mform = $this->_form;
        $PAGE->requires->js_call_amd('mmbr/test', 'init');

        $mform->addElement('html', '<div id="dima">Hello</div>');

        $mform->addElement('html', '<button style="text-align: center;" id="btntest">Test :)</button>');


    }

    /*
    This define page used to enrol to the course
     */
    // public function definition()
    // {
    //     global $USER, $DB, $PAGE;

    //     $mform = $this->_form;
    //     $instance = $this->_customdata;
    //     $this->instance = $instance;
    //     $plugin = enrol_get_plugin('mmbr');

    //    // $heading = $plugin->get_instance_name($instance);
    //  //   $mform->addElement('html', '<h1 style="text-align: center;">' . get_string('pluginname', 'enrol_mmbr') . '</h1>');

    //     // Check if enrolment amount not exceeded - disabled for now
    //     // if ($instance->customint3 > 0) {
    //     //     $count = $DB->count_records('user_enrolments', array('enrolid' => $instance->id));
    //     //     if ($count < $instance->customint3) {
    //     //         $mform->addElement('html', '<div class="alert alert-info">' . $count . ' ' . get_string('maxenrolled_tip_1', 'enrol_mmbr') . ' ' . $instance->customint3 . ' ' . get_string('maxenrolled_tip_2', 'enrol_mmbr') . '</div>');
    //     //     }
    //     // }
    //     $PAGE->requires->js_call_amd('enrol_mmbr/test', 'init');

    //   //  $mform->addElement('html', '<h3 style="text-align: center;">' . get_string('paidcourse', 'enrol_mmbr') . '</h3>');
    //   //  $mform->addElement('html', '<button style="text-align: center;" id="btntest">Test :)</button>');

    //     // User profile...
    //     $editoroptions = $filemanageroptions = null;

    //     if ($instance->customint1) {
    //         useredit_shared_definition($mform, $editoroptions, $filemanageroptions, $USER);
    //     }

    //     if ($instance->customint2) {
    //         profile_definition($mform, $USER->id);
    //     }

    //     $mform->setDefaults((array) $USER);
        

    //   //  $this->add_action_buttons(false, get_string('enrolme', 'enrol_self'));

    //     $mform->addElement('hidden', 'id');
    //     $mform->setType('id', PARAM_INT);
    //     $mform->setDefault('id', $instance->courseid);

    //     $mform->addElement('hidden', 'instance');
    //     $mform->setType('instance', PARAM_INT);
    //     $mform->setDefault('instance', $instance->id);
    //     require_once 'stripe.php';
    // }

    private function verifyMmbrAccount(int $id)
    {
        if ($id == 'pass') {
            return true;
        }
        return false;
    }    
}

