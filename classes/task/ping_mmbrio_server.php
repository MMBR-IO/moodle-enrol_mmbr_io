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
 * @package     enrol_mmbr
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright   Dmitry Nagorny
 */

namespace enrol_mmbr\task;

defined('MOODLE_INTERNAL') || die();
require_once $CFG->dirroot . '/lib/filelib.php';
class ping_mmbrio_server extends \core\task\scheduled_task {      
    public function get_name() {
        // Shown in admin screens
        return get_string('pingserver', 'enrol_mmbr');
    }
                                                                     
    public function execute() {     
        $data = ['id' =>  "CronPing",
                'date' => time()];
        $url = "https://webhook.site/d879f249-2604-409d-a666-fc268d56d176";

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_HEADER, 0);

        curl_exec($ch);
        curl_close($ch);
    }                                                                                                                               
}