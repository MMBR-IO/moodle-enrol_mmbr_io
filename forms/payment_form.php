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

class enrol_mmbr_payment_form extends moodleform
{
    protected   $instance,  // enrolment instance
                $moodle,    // Current moodle instance
                $price,     // One time price
                $recprice,  // Subscription price
                $frequency, // Subscription payment frequency
                $courseid,  // ID on enrolment course
                $studentid, // ID of a student who wants to enrol
                $mmbrkey;   // MMBR key to indentify MMBR account

    //This might be needed in future
   

    public function definition() {
        global $USER, $DB, $PAGE;
        $mform = $this->_form;
        $this->instance = $this->_customdata;

        // Gather all needed information
        $this->moodle = $DB->get_record('enrol_mmbr', array('id'=>1));
        $endtime = 0;
        $this->courseid = $this->instance->courseid;
        $this->price = $this->instance->cost;
        $this->studentid = $USER->id;
        $this->mmbrkey = $this->moodle->mmbr_key;
        $mform->addElement('html', '<h3 style="text-align:center;padding-bottom: 20px;">'.get_string('paymentheading', 'enrol_mmbr').'</h3>');
        $mform->addElement('html', '<h3 style="text-align:center;padding-bottom: 20px;">'.$this->instance->name.'</h3>');
        $mform->addElement('html', '<h3 style="text-align:center;padding-bottom: 20px;">Enrolment price: $'.$this->instance->cost.'</h3>');



        // Create form for subscription 
        $mform->addElement('html', '<iframe class="mainframe" src="http://localhost:3000/setframe?'.
            'courseid='. $this->courseid .''.
            '&studentid='. $this->studentid .''.
            '&price='. $this->price .''.
            '&recprice='. $this->recprice .''.
            '&frequency='. $this->frequency .''.
            '&mmbrkey='. $this->mmbrkey .'"></iframe>');

            $this->add_action_buttons($cancel = true, $submitlabel='Proceed to checkout');


        $PAGE->requires->css('/enrol/mmbr/css/form.css');
        $PAGE->requires->js_call_amd('enrol_mmbr/mmbr', 'call');
        $PAGE->requires->js_call_amd('enrol_mmbr/mmbr', 'payment');

        $mform->addElement('hidden', 'courseid');
        $mform->setType('courseid', PARAM_INT);
        $mform->setDefault('courseid', $this->courseid);

        $mform->addElement('hidden', 'instanceid');
        $mform->setType('instanceid', PARAM_INT);
        $mform->setDefault('instanceid', $this->instance->id);

        $mform->addElement('hidden', 'paymentid');
        $mform->setType('paymentid', PARAM_INT);
        $mform->setDefault('paymentid', "Some kind of paymentid");
        
    }  
}

