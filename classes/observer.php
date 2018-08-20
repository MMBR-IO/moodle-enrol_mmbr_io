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



defined('MOODLE_INTERNAL') || die();
require_once $CFG->dirroot . '/user/profile/lib.php';
require_once $CFG->dirroot . '/lib/filelib.php';
require_once $CFG->dirroot . '/enrol/mmbr/lib.php';

// in classes/observer.php
class enrol_mmbr_observer {

    private static $DOMAIN = 'http://localhost/cobb/v1/';
    private static $DOMAIN_PORT = 4143;
    /**
     * USER LOGGEDIN 
     * Event is triggered when User logs in Moodle
     * If this user has enrolments with MMBR.IO plugin 
     *  - check if all enrolments is up to date
     *  - update with MMBR.IO in case something missing
     */
	public static function check_logged_user($event) {
        global $DB;
        $plugin = enrol_get_plugin('mmbr');
        $eventdata = $event->get_data();    // All data about this event
        $userid = $eventdata['userid'];
        $enrol = "enrol";
        $enrolments = $DB->get_records($enrol, array('enrol' => 'mmbr'));
        // Check is this user has enrolment with MMBR plugin
        // False -> do nothing || True -> Check if all his enrolment is up to date
        foreach ($enrolments as &$value) {
            $enrolid = $value->id;
            $records = $DB->get_records("user_enrolments", array('enrolid' => $enrolid, 'userid'=> $userid)); // Get all user enrolments
            if ($records != 0){ // If user has enrolments
                foreach ($records as &$val) {
                    if (!empty($val->timeend) && $val->timeend > 0 && $val->timeend < time()){ // If enrolment exist and expired 
                        $data = ['key'      => $plugin->get_mmbr_io_key(),
                                 'userid'   => $userid,
                                 'courseid' => $value->courseid,
                                 ];
                        $url = "https://webhook.site/d879f249-2604-409d-a666-fc268d56d176";
                        $mcurl = new curl();
                        $mcurl->post($url, format_postdata_for_curlcall($data), []);
                       // $response = $mcurl->getResponse();
                        $response = true;

                        // Update enrolment expiry date
                        if($response) { // If answer from MMBR.IO is true
                            $newtimeend = time() + $value->customint2; // Current time + payment frequency from Enrolment Instance 
                            $plugin->update_user_enrol($value, $userid, false,null, $newtimeend);
                            // Check if expiry date didn't expire LS
                        } else {
                            $plugin->update_user_enrol($value, $userid, true,null, null);
                        }
                    }
                }
            }
        }
    }

    public static function new_enrolment_instance($instance, $course) {
        global $DB;
        $response = new stdClass;
        $plugin = enrol_get_plugin('mmbr');
        $mmbriokey = $plugin->get_mmbr_io_key();
        if (strlen($plugin->get_mmbr_io_key()) < 13) {
            $response->errors = 'miss_key';
            return $response;
        }
        $data = [
            'public_key'    => $mmbriokey,
            'course_id'     => $course->id,
            'course_name'   => $course->fullname,
        ];
        $options['CURLOPT_HTTPGET'] = 1;

        $response = self::get('foxtrot/plugin/instance', $data, $options);
        $response = json_decode($response);
        return $response;
    }

    /**
     * This needed to unenrol user from course 
     * 
     * core\event\user_password_updated.php	user_password_updated	core	user	updated	
     * core\event\user_enrolment_created	user_enrolled	core	user_enrolment	created	
     * core\event\user_enrolment_deleted	user_unenrolled	core	user_enrolment	deleted	
     * core\event\user_enrolment_updated	user_enrol_modified	core	user_enrolment	updated
     * @param $userid - useer to unenrol 
     * @param $courseid - course to unenrol from 
     */
    public static function unenrol_user($userid, $courseid) {
        global $DB;
        $plugin = enrol_get_plugin('mmbr');
        $instance = new stdClass;
        $instances = $plugin->enrol_get_instances($courseid,true);
        $userenrolments = $DB->get_records('user_enrolments', array('userid' => $userid));
        // Figure what instance we are enroled 
        if (count($instances) > 0 && count($userenrolments) > 0) {
            foreach($instances as $ins) {
                foreach($userenrolments as $ue){

                    if ($ins->id == $ue->enrolid){
                        $instance = $ins;
                    }
                }
            }
        }
        $plugin->unenrol_user($instance, $userid);
    }

    /** 
     * Concurrency check with MMBR.IO server that payment was successful  
     * 
     * @param $paymentkey - got from Stripe transaction
     * @return $response  - response from MMBR.IO server confirming payment
     */
    public function verify_payment($paymentkey) {
        // ** Testing **
        $response = new stdClass;
        $response->success = true;
        $response->enrolment = [
            'course_id' => 4,
            'user_id'   => 3,
            'price'     => 10000,
            'currency'  => 'USD',
            'expiry'    => 1631416635,
            'interval'  => 86400];
        // ** Testing **

        global $DB;
        $plugin = enrol_get_plugin('mmbr');
        $data = ['key' => $plugin->get_mmbr_io_key(),
                'th' => $paymentkey];
        $url = "https://webhook.site/d879f249-2604-409d-a666-fc268d56d176";

        $mcurl->post($url, format_postdata_for_curlcall($data), []);
     //   if ($response = $mcurl->getResponse()) {
         if ($response) {
            if ($response->success) {
                return $response;
            }
        }
        return false;
    }    

    public static function get($route, $params = array(), $options = array()) {
        $url = self::$DOMAIN . $route;
        if (!empty($params)) {
            $url .= (stripos($url, '?') !== false) ? '&' : '?';
            $url .= http_build_query($params, '', '&');
        }
        $curl = curl_init();
        curl_setopt_array($curl, array(        
            CURLOPT_HTTPGET         => 1,
            CURLOPT_RETURNTRANSFER  => 1,
            CURLOPT_URL             => $url,
            CURLOPT_PORT            => self::$DOMAIN_PORT,
        ));
        if(!$response = curl_exec($curl)){
            return $response->errors = $curl_error;
        }
        curl_close($curl);
        return $response;
    }

    public static function post() {

    }
}