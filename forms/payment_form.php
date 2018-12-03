<?php
/**
 * This file is part of Moodle - http://moodle.org/
 * 
 * PHP version 7

 * Moodle is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * Moodle is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

 * @category Api_Calls
 * @package  Enrol_Mmbr
 * @author   Dmitry Nagorny <dmitry.nagorny@mmbr.io>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @link     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
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
                $email,     // User email
                $frequency, // Subscription payment frequency
                $currency,  // Currency
                $courseid,  // ID on enrolment course
                $studentid, // ID of a student who wants to enrol
                $mmbrkey;   // MMBR key to indentify MMBR account

    /**
     * Function defines how page looks
     * 
     * @return null
     */
    public function definition() 
    {
        global $USER, $DB, $PAGE;
        $mform = $this->_form;
        $this->instance = $this->_customdata;
        $plugin = enrol_get_plugin('mmbr');
        // Gather all needed information
        $this->moodle = $DB->get_record_select('config_plugins', "plugin = 'enrol_mmbr' AND name = 'mmbrkey'");
        if (empty($this->moodle) || empty($this->moodle->value)) {
            \core\notification::error(get_string('mmbriodeferror', 'enrol_mmbr'));
            \core\notification::error(get_string('mmbriocustomerkey', 'enrol_mmbr'));
        } else {
            $endtime = 0;
            $this->courseid = $this->instance->courseid;
            $this->price = $plugin->get_cost_cents($this->instance->cost);
            $this->studentid = $USER->id;
            $this->mmbrkey = $this->moodle->value;
            $this->currency = $this->instance->currency;
            $this->frequency = $this->instance->enrolperiod;
            $this->email = $USER->email;
            $mform->addElement('html', '<h3 style="text-align:center;padding-bottom: 20px;">'.get_string('paymentheading', 'enrol_mmbr').'</h3>');
            $mform->addElement('html', '<h3 style="text-align:center;padding-bottom: 20px;">'.get_string('enrolmentoption', 'enrol_mmbr').'<strong>'.$this->instance->name.'</strong></h3>');
            $mform->addElement('html', '<h3 style="text-align:center;padding-bottom: 20px;">Enrolment price: <strong>$'.$this->instance->cost.'</strong></h3>');

            // Create form for subscription
            $env = $plugin->get_development_env();
            switch ($env) {
            case 'development':
                $apiLink = 'http://localhost:4141/comma/v1/foxtrot/frame?';
                break;
            case 'staging':
                $apiLink = 'https://staging.mmbr.io/comma/v1/foxtrot/frame?';
                break;
            default:
                $apiLink = 'https://api.mmbr.io/comma/v1/foxtrot/frame?';
                break;
            }
            $mform->addElement(
                'html',
                '<iframe class="mainframe" id="paymentFrame" src="'. $apiLink .
                'course_id='.   $this->courseid     .''.
                '&student_id='. $this->studentid    .''.
                '&price='.      $this->price        .''.
                '&currency='.   $this->currency     .''.
                '&email='.      urlencode($this->email).''.
                '&repeat_interval='.$this->frequency.''.
                '&public_key='. $this->mmbrkey      .'">
            </iframe>'
            );

            $PAGE->requires->css('/enrol/mmbr/css/form.css');
            $PAGE->requires->js_call_amd('enrol_mmbr/mmbr_io_calls', 'call');
        }

        $mform->addElement('hidden', 'courseid');
        $mform->setType('courseid', PARAM_INT);
        $mform->setDefault('courseid', $this->courseid);

        $mform->addElement('hidden', 'enrolinstanceid');
        $mform->setType('enrolinstanceid', PARAM_INT);
        $mform->setDefault('enrolinstanceid', $this->instance->id);

        $mform->addElement('hidden', 'instanceid');
        $mform->setType('instanceid', PARAM_INT);
        $mform->setDefault('instanceid', $this->instance->id);
        

        $mform->addElement('hidden', 'submit_data');
        $mform->setType('submit_data', PARAM_TEXT);
        
    }  
}

