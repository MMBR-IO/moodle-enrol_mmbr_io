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
 * Plugin upgrade steps are defined here.
 *
 * @package     enrol_mmbr
 * @category    upgrade
 * @copyright   2018 DmitryN defrakcija123@gmail.com
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Execute enrol_mmbr upgrade from the given old version.
 *
 * @param int $oldversion
 * @return bool
 */
function xmldb_enrol_mmbr_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2018062200) {

        // Define table enrol_mmbr to be created.
        $table = new xmldb_table('enrol_mmbr');

        // Adding fields to table enrol_mmbr.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('mmbr_key', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('mmbr_name', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('mmbr_data', XMLDB_TYPE_TEXT, null, null, null, null, null);

        // Adding keys to table enrol_mmbr.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Conditionally launch create table for enrol_mmbr.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Mmbr savepoint reached.
        upgrade_plugin_savepoint(true, 2018062200, 'enrol', 'mmbr');
    }

    return true;
}
