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
 * @package   enrol_mmbr
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright Dmitry Nagorny
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
        '',
        PARAM_TEXT,
        60));

    $currencies = enrol_get_plugin('mmbr')->get_currencies();
    $settings->add(new admin_setting_configselect('enrol_mmbr/currency',
    get_string('currency', 'enrol_mmbr'), '', 'USD', $currencies));
}


