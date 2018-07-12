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
 * Plugin administration pages are defined here.
 *
 * @package     enrol_mmbr
 * @category    admin
 * @copyright   2018 DmitryN defrakcija123@gmail.com
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {
    $settings->add(new admin_setting_heading('enrol_mmbr_enrolname', '', get_string('pluginname_desc', 'enrol_mmbr')));

    $settings->add(new admin_setting_heading(
        'enrol_mmbr_key',
        get_string('mmbrkey', 'enrol_mmbr'),
        get_string('mmbrkey', 'enrol_mmbr')));
    $settings->add(new admin_setting_configtext(
        'enrol_mmbr/mmbrkey',
        get_string('mmbrkey', 'enrol_mmbr'),
        get_string('mmbrkey_desc', 'enrol_mmbr'),
        null,
        PARAM_TEXT,
        60));

    // Enrol instance defaults...
    $settings->add(new admin_setting_heading('enrol_manual_defaults',
        get_string('enrolinstancedefaults', 'admin'), get_string('enrolinstancedefaults_desc', 'admin')));

    $settings->add(new admin_setting_configcheckbox('enrol_mmbr/defaultenrol',
        get_string('defaultenrol', 'enrol'), get_string('defaultenrol_desc', 'enrol'), 0));

    $options = array(1 => get_string('yes'),
                     0  => get_string('no'));
    $settings->add(new admin_setting_configselect('enrol_mmbr/show_standard_user_profile',
        get_string('show_standard_user_profile', 'enrol_mmbr'), '', 1, $options));

    $options = array(1 => get_string('yes'),
                     0  => get_string('no'));
    $settings->add(new admin_setting_configselect('enrol_mmbr/show_extra_user_profile',
        get_string('show_extra_user_profile', 'enrol_mmbr'), '', 1, $options));

    if (!during_initial_install()) {
        $options = get_default_enrol_roles(context_system::instance());
        $student = get_archetype_roles('student');
        $student = reset($student);
        $settings->add(new admin_setting_configselect('enrol_mmbr/roleid',
            get_string('defaultrole', 'role'), '', $student->id, $options));
    }

    $settings->add(new admin_setting_configcheckbox(
        'enrol_mmbr/notifycoursebased',
        get_string('notifycoursebased', 'enrol_mmbr'),
        get_string('notifycoursebased_desc', 'enrol_mmbr'),
        0));

    $settings->add(new admin_setting_configduration('enrol_mmbr/enrolperiod',
        get_string('defaultperiod', 'enrol_mmbr'), get_string('defaultperiod_desc', 'enrol_mmbr'), 0));
}


