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
        
        $mform->addElement('html', '<form action="/charge" method="post" id="payment-form">');
        $mform->addElement('html', ' <div class="form-row">');
        $mform->addElement('html', '<label for="card-element">Credit or debit card</label>');
        //A Stripe Element will be inserted here.
        $mform->addElement('html', '<div id="card-element"></div>');
        //Used to display Element errors
        $mform->addElement('html', '<div id="card-errors" role="alert"></div>');
        $mform->addElement('html', '</div>');
        $mform->addElement('html', '<button id="brnSubmit">Submit Payment</button>');           
        $mform->addElement('html', '</form>');        
        $PAGE->requires->js_call_amd('enrol_mmbr/test', 'setStripe');      
        $PAGE->requires->js( new moodle_url('https://js.stripe.com/v3/'), true);
        $PAGE->requires->css('/enrol/mmbr/css/style.css');
    }

    private function verifyMmbrAccount(int $id)
    {
        if ($id == 'pass') {
            return true;
        }
        return false;
    }    
}

