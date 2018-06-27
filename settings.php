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

    // Confirm mail settings...
    $settings->add(new admin_setting_heading(
        'enrol_mmbr_confirmmail',
        get_string('confirmmail_heading', 'enrol_mmbr'),
        get_string('confirmmail_desc', 'enrol_mmbr')));
    $settings->add(new admin_setting_configtext(
        'enrol_mmbr/confirmmailsubject',
        get_string('confirmmailsubject', 'enrol_mmbr'),
        get_string('confirmmailsubject_desc', 'enrol_mmbr'),
        null,
        PARAM_TEXT,
        60));
    $settings->add(new admin_setting_confightmleditor(
        'enrol_mmbr/confirmmailcontent',
        get_string('confirmmailcontent', 'enrol_mmbr'),
        get_string('confirmmailcontent_desc', 'enrol_mmbr'),
        null,
        PARAM_RAW));

    // Wait mail settings.
    $settings->add(new admin_setting_heading(
        'enrol_mmbr_waitmail',
        get_string('waitmail_heading', 'enrol_mmbr'),
        get_string('waitmail_desc', 'enrol_mmbr')));
    $settings->add(new admin_setting_configtext(
        'enrol_mmbr/waitmailsubject',
        get_string('waitmailsubject', 'enrol_mmbr'),
        get_string('waitmailsubject_desc', 'enrol_mmbr'),
        null,
        PARAM_TEXT,
        60));
    $settings->add(new admin_setting_confightmleditor(
        'enrol_mmbr/waitmailcontent',
        get_string('waitmailcontent', 'enrol_mmbr'),
        get_string('waitmailcontent_desc', 'enrol_mmbr'),
        null,
        PARAM_RAW));

    // Cancel mail settings...
    $settings->add(new admin_setting_heading(
        'enrol_mmbr_cancelmail',
        get_string('cancelmail_heading', 'enrol_mmbr'),
        get_string('cancelmail_desc', 'enrol_mmbr')));
    $settings->add(new admin_setting_configtext(
        'enrol_mmbr/cancelmailsubject',
        get_string('cancelmailsubject', 'enrol_mmbr'),
        get_string('cancelmailsubject_desc', 'enrol_mmbr'),
        null,
        PARAM_TEXT,
        60));
    $settings->add(new admin_setting_confightmleditor(
        'enrol_mmbr/cancelmailcontent',
        get_string('cancelmailcontent', 'enrol_mmbr'),
        get_string('cancelmailcontent_desc', 'enrol_mmbr'),
        null,
        PARAM_RAW));

    // Notification settings...
    $settings->add(new admin_setting_heading(
        'enrol_mmbr_notify',
        get_string('notify_heading', 'enrol_mmbr'),
        get_string('notify_desc', 'enrol_mmbr')));
    $settings->add(new admin_setting_users_with_capability(
        'enrol_mmbr/notifyglobal',
        get_string('notifyglobal', 'enrol_mmbr'),
        get_string('notifyglobal_desc', 'enrol_mmbr'),
        array(),
        'enrol/mmbr:manageapplications'));

    // Expiry settings.
    $settings->add(new admin_setting_heading(
        'enrol_mmbr_expiry',
        get_string('expiry_heading', 'enrol_mmbr'),
        get_string('expiry_desc', 'enrol_mmbr')));
    $options = array(
        ENROL_EXT_REMOVED_KEEP           => get_string('extremovedkeep', 'enrol'),
        ENROL_EXT_REMOVED_SUSPEND        => get_string('extremovedsuspend', 'enrol'),
        ENROL_EXT_REMOVED_SUSPENDNOROLES => get_string('extremovedsuspendnoroles', 'enrol'),
        ENROL_EXT_REMOVED_UNENROL        => get_string('extremovedunenrol', 'enrol'),
    );
    $settings->add(new admin_setting_configselect('enrol_mmbr/expiredaction',
        get_string('expiredaction', 'enrol_mmbr'),
        get_string('expiredaction_help', 'enrol_mmbr'),
        ENROL_EXT_REMOVED_KEEP,
        $options));

    // Enrol instance defaults...
    $settings->add(new admin_setting_heading('enrol_manual_defaults',
        get_string('enrolinstancedefaults', 'admin'), get_string('enrolinstancedefaults_desc', 'admin')));

    $settings->add(new admin_setting_configcheckbox('enrol_mmbr/defaultenrol',
        get_string('defaultenrol', 'enrol'), get_string('defaultenrol_desc', 'enrol'), 0));

    $options = array(ENROL_INSTANCE_ENABLED => get_string('yes'),
                     ENROL_INSTANCE_DISABLED  => get_string('no'));
    $settings->add(new admin_setting_configselect('enrol_mmbr/status',
        get_string('status', 'enrol_mmbr'), get_string('status_desc', 'enrol_mmbr'), ENROL_INSTANCE_ENABLED, $options));

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

if ($hassiteconfig) { // Needs this condition or there is error on login page.
    $ADMIN->add('courses', new admin_externalpage('enrol_mmbr',
            get_string('applymanage', 'enrol_mmbr'),
            new moodle_url('/enrol/mmbr/manage.php')));
}

