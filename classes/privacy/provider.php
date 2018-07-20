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

namespace enrol_mmbr\privacy;
use core_privacy\local\metadata\collection;
 
class provider implements 
        // This plugin does store personal user data.
     //   \core_privacy\local\metadata\provider {

/**
 * Lets pretend that we don't store any data for now
 */

// This plugin does not store any personal user data.
\core_privacy\local\metadata\null_provider {

public static function get_reason() : string {
    return 'privacy:metadata';
}


 
//     public static function get_metadata(collection $collection) : collection {
//      //   $collection = new \core_privacy\local\metadata\collection;
//         $collection->add_external_location_link('user_enrolments', [
//             'status' => 'privacy:metadata:user_enrolments:status',
//             'userid' => 'privacy:metadata:user_enrolments:userid',
//             'timeend' => 'privacy:metadata:user_enrolments:timeend',
//         ], 'privacy:metadata:user_enrolments');

//         $collection->add_external_link('user', [
//             'id' => 'privacy:metadata:user:id',
//             'firstname' => 'privacy:metadata:user:firstname',
//             'lastname' => 'privacy:metadata:user:lastname',
//             'email' => 'privacy:metadata:user:email'
//         ], 'privacy;metadata:user');
 
//     return $collection;
//     }

//     public static function get_contexts_for_userid(int $userid) : contextlist {
//         $contextlist = new \core_privacy\local\request\contextlist();
 
//         // $sql = "SELECT ue.status, ue.userid, ue.timeend, u.firstname, u.lastname, u.email 
//         //          FROM {user_enrolments} ue
//         //    INNER JOIN {user} u ON u.id = ue.userid
//         //         WHERE (
//         //         ue.userid        = :userid,
//         //         )
//         // ";
 
//         // $params = [
//         //     'userid' => $userid,
//         // ];

//         // $contextlist->add_from_sql($sql, $params);
 
//         return $contextlist;
//     }

//     /**
//      * Export all user data stored by plugin.
//      *
//      * @param   int         $userid The userid of the user whose data is to be exported.
//      */
//     public static function export_user_data(int $userid) {
//         // $context = \context_system::instance();
//         // $subcontext[] = get_string('pluginname', 'local_yourplugin');
//         // $userdata = get_user_preference($userid);
//         // if (!empty($userdata)) {
//         //     \core_privacy\local\request\writer::with_context($context)
//         //             ->export_data($subcontext, (object) [
//         //                     'yourstringidentifier' => $data,
//         //         ]);
//         // }
//     }

    
}