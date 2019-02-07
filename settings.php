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
 * @package   enrol_mmbrio
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright Dmitry Nagorny
 */

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {
    $settings->add(new admin_setting_heading('enrol_mmbrio_enrolname_short', '', get_string('pluginname_desc', 'enrol_mmbrio')));

    $settings->add(
        new admin_setting_heading(
            'enrol_mmbrio_key',
            get_string('mmbrio_set', 'enrol_mmbrio'),
            get_string('mmbrio_set_desc', 'enrol_mmbrio')
        )
    );
    $settings->add(
        new admin_setting_configtext(
            'enrol_mmbrio/mmbrkey',
            get_string('mmbrkey', 'enrol_mmbrio'),
            get_string('mmbrkey_desc', 'enrol_mmbrio'),
            '',
            PARAM_TEXT,
            60
        )
    );

    $currencies = enrol_get_plugin('mmbrio')->get_currencies();
    $settings->add(
        new admin_setting_configselect(
            'enrol_mmbrio/currency',
            get_string('set_currency', 'enrol_mmbrio'),
            '',
            'USD',
            $currencies
        )
    );
}
