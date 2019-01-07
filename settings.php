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
 * @package   enrol_mmbr_io
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright Dmitry Nagorny
 */

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {
    $settings->add(new admin_setting_heading('enrol_mmbr_io_enrolname', '', get_string('pluginname_desc', 'enrol_mmbr_io')));

    $settings->add(
        new admin_setting_heading(
            'enrol_mmbr_io_key',
            get_string('mmbrkey', 'enrol_mmbr_io'),
            get_string('mmbrkey', 'enrol_mmbr_io')
        )
    );
    $settings->add(
        new admin_setting_configtext(
            'enrol_mmbr_io/mmbrkey',
            get_string('mmbrkey', 'enrol_mmbr_io'),
            get_string('mmbrkey_desc', 'enrol_mmbr_io'),
            '',
            PARAM_TEXT,
            60
        )
    );

    $currencies = enrol_get_plugin('mmbr_io')->get_currencies();
    $settings->add(
        new admin_setting_configselect(
            'enrol_mmbr_io/currency',
            get_string('currency', 'enrol_mmbr_io'), '', 'USD', $currencies
        )
    );
}


