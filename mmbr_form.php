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
        $PAGE->requires->js_call_amd('enrol_mmbr/mmbr', 'call');
        $mform = $this->_form;
        $instance = $this->_customdata;
        $this->instance = $instance;

        $mform->addElement('html', '<form action="#" method="post" id="payment-form">');
        $mform->addElement('html', '<iframe class="mainframe" src="http://localhost:3000/setframe?'.
            'courseid='. $instance->courseid .''.
            '&userid='. $USER->id .''.
            '&price=20%20USD"></iframe>');
    // For future to create loading screen while form loads for slow connections
    //  $mform->addElement('html', '<div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>');
        $mform->addElement('html', '</form>');

        // Params to send to Clerk
        $params = [
            "userid" => $USER->id,
            "courseid" => $instance->courseid,
            'instanceid' => $instance->id, 
        ];
        $PAGE->requires->css('/enrol/mmbr/css/form.css');

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);
        $mform->setDefault('id', $instance->courseid);

        $mform->addElement('hidden', 'instance');
        $mform->setType('instance', PARAM_INT);
        $mform->setDefault('instance', $instance->id);
    }

    // Just in case we need to verify account with MMBR
    private function verifyMmbrAccount(int $id)
    {
        if ($id == 'pass') {
            return true;
        }
        return false;
    }    
}

