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
 * @package enrol_mmbr
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author  Dmitry Nagorny
 */

namespace enrol_mmbr\privacy;
use core_privacy\local\metadata\collection;
 
class Provider implements 
    \core_privacy\local\metadata\provider,
    \core_privacy\local\request\data_provider
{
    public static function get_metadata(collection $collection) : collection {
 
        $collection->add_external_location_link(
            'mmbrio_server', [
                'userid' => 'privacy:metadata:mmbrio_server:userid',
                'email' => 'privacy:metadata:mmbrio_server:email',
                'enrolments' => 'privacy:metadata:mmbrio_server:enrolments',
            ],
            'privacy:metadata:mmbrio_server'
        );
 
        return $collection;
    }
    
}