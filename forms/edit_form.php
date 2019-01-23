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
 * Adds new instance of enrol_mmbrio to specified course
 * or edits current instance.
 *
 * @package enrol_mmbrio
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author  Dmitry Nagorny
 */

defined('MOODLE_INTERNAL') || die();

require_once $CFG->libdir.'/formslib.php';
require_once 'lib.php';
class enrol_mmbrio_edit_form extends moodleform
{
    /**
     * Sets up moodle form.
     *
     * @return void
     */
    public function definition()
    {
        $mform = $this->_form;

        list($instance, $plugin, $context) = $this->_customdata;
        
        $options = $plugin->get_enrolment_options();
        $mform->addElement('select', 'name', get_string('enrolmentoption', 'enrol_mmbrio'), $options, "");
        $mform->setType('name', PARAM_TEXT);

        $mform->addElement('text', 'price', get_string('cost', 'enrol_mmbrio'), array('size' => 8));
        $mform->setType('price', PARAM_RAW);
        if ($instance->id != null) {
            $mform->setDefault('price', $plugin->get_cost_full($instance->cost));
        }
        $mform->addHelpButton('price', 'cost', 'enrol_mmbrio');

        $currencies = $plugin->get_currencies();
        $mform->addElement('select', 'currency', get_string('currency', 'enrol_mmbrio'), $currencies);
        $mform->setDefault('currency', $plugin->get_config('currency'));

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $mform->addElement('hidden', 'courseid');
        $mform->setType('courseid', PARAM_INT);

        if (enrol_accessing_via_instance($instance)) {
            $mform->addElement(
                'static',
                'selfwarn',
                get_string('instanceeditselfwarning', 'core_enrol'),
                get_string('instanceeditselfwarningtext', 'core_enrol')
            );
        }

        $this->add_action_buttons(true, ($instance->id ? null : get_string('addinstance', 'enrol')));

        $this->set_data($instance);
    }

    /**
     * Sets up moodle form validation.
     *
     * @param  stdClass $data
     * @param  stdClass $files
     * @return $error error list
     */
    public function validation($data, $files)
    {
        // global $CFG;
        $errors = parent::validation($data, $files);

        // list($instance, $plugin, $context) = $this->_customdata;

        // Depending on language is used replaces decimal separator to '.'
        $cost = str_replace(get_string('decsep', 'langconfig'), '.', $data['price']);
        if (!is_numeric($cost)) {
            $errors['price'] = get_string('costnumerror', 'enrol_mmbrio');
        }
        if (intval($cost) < 2) {
            $errors['price'] = get_string('costnullerror', 'enrol_mmbrio');
        }
        return $errors;
    }
}
