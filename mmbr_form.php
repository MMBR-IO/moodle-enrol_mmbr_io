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

require_once($CFG->libdir.'/formslib.php');
require_once($CFG->dirroot.'/user/editlib.php');
require_once($CFG->dirroot.'/user/profile/lib.php');

class enrol_mmbr_apply_form extends moodleform {
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

    /*
        This define page used to enrol to the course
    */
    public function definition() {
        global $USER, $DB;

        $mform = $this->_form;

        $instance = $this->_customdata;
        $this->instance = $instance;
        $plugin = enrol_get_plugin('apply');

     //   $mform->addElement('html', '<p>'.$instance->customtext1.'</p>');
        $mform->addElement('textarea', 'applydescription', 'This text is from Dmitry', 'cols="80"');
     //   $mform->setType('applydescription', PARAM_TEXT);

    $this->add_action_buttons(false, get_string('enrolme', 'enrol_self'));
    
    print_r('Beggining of printR');

    $user = $DB->get_record('enrol', array('id'=>'7'));
    print_r($user);
    }
}