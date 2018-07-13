<?php

/**
 * @package    enrol_mmbr
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     MMBR.IO
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