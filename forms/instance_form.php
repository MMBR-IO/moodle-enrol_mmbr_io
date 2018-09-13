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
 * If course have more than one instance let user to choose 
 * 
 * @package    enrol_mmbr
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Dmitry Nagorny
 */

defined('MOODLE_INTERNAL') || die();

require_once $CFG->libdir . '/formslib.php';
require_once $CFG->dirroot . '/user/editlib.php';
require_once $CFG->dirroot . '/user/profile/lib.php';

class enrol_mmbr_instance_form extends moodleform
{
    protected   $instances, $courseid;
    
    public function definition() {
        global $USER, $DB, $PAGE;
        $mform = $this->_form;
        // Retrieve array with all instances
        $this->instances = $this->_customdata;
        $plugin = enrol_get_plugin('mmbr');
        $fid = key($this->instances);

        $mform->addElement('html', '<h3 style="text-align:center;padding-bottom: 20px;">'.get_string('enrolheading', 'enrol_mmbr').'</h3>');
        foreach ($this->instances as $instance) {
            $price = $plugin->get_cost_full($instance->cost);
            $mform->addElement('html', '<div class="enrolment">');
            $mform->addElement('radio', 'instanceid', '', $instance->name . ' ($'.$price.')', $instance->id, '');
            $mform->addElement('html', '</div>');
            $this->courseid = $instance->courseid;
        }
        $mform->setDefault('instanceid', $fid);

        $PAGE->requires->css('/enrol/mmbr/css/form.css');
        $PAGE->requires->js_call_amd('enrol_mmbr/style', 'instances');
        $this->add_action_buttons($cancel = false, $submitlabel='Proceed to checkout');

        $mform->addElement('hidden', 'courseid');
        $mform->setType('courseid', PARAM_INT);
        $mform->setDefault('courseid', $this->courseid);
    }  
}

