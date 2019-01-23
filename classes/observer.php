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
 * @category Api_Calls
 * @package  Enrol_mmbrio
 * @author   Dmitry Nagorny <dmitry.nagorny@mmbr.io>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @link     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot . '/user/profile/lib.php');
require_once($CFG->dirroot . '/lib/filelib.php');
require_once($CFG->dirroot . '/enrol/mmbrio/lib.php');

// in classes/observer.php
class enrol_mmbrio_observer
{
    /**
     * Gets development stage
     * Based on it's value returns api string
     *
     * @param  string $e - Stage name
     * @return string $apiLink - API Link based on development stage
     */
    public static function get_domain($e)
    {
        switch ($e) {
            case 'development':
                // Using ngrok for proper work with https and ports on local development
                $apiLink = 'https://35b9bee6.ngrok.io/cobb/v1/';
                break;
            case 'staging':
                $apiLink = 'https://staging.mmbr.io/cobb/v1/';
                break;
            default:
                $apiLink = 'https://api.mmbr.io/cobb/v1/';
                break;
        }
        return $apiLink;
    }

    /**
     * USER LOGGED IN
     * Event is triggered when User logs in Moodle
     * If this user has enrollments with MMBR.IO plugin
     *  - check if all enrollments is up to date
     *  - update with MMBR.IO in case something missing
     *
     * |*| 1) Get all MMBR.IO enrollments -> check is user has one -> validate it
     * | | 2) Get all user_enrollments -> check if there are MMBR.IO ones -> validate them
     * | | 3) Write custom query with table JOIN to get all MMBR.IO enrollments -> validate them
     *
     * @param object $event - This event instance
     *
     * @return null
     */
    public static function check_logged_user($event)
    {
        global $DB;
        $eventdata = $event->get_data();    // All data about this event
        $userid = $eventdata['userid'];
        $enrol = "enrol";
        $enrolments = $DB->get_records($enrol, array('enrol' => 'mmbrio'));
        $plugin = enrol_get_plugin('mmbrio');
        // Check is this user has enrolment with MMBR.IO plugin
        // False -> do nothing || True -> Check if all his enrolment is up to date
        foreach ($enrolments as &$enrolment) {
            $enrolid = $enrolment->id;
            $records = $DB->get_records("user_enrolments", array('enrolid' => $enrolid, 'userid'=> $userid)); // Get all user enrolments
            // If user has enrolments
            if ($records != 0) {
                foreach ($records as &$rec) {
                    // If enrolment exist and expired
                    if (intval($rec->status) == 0 && !empty($rec->timeend) && intval($rec->timeend) != 0 && $rec->timeend > 0 && $rec->timeend < time()) {
                        $result = self::validate_user_enrolment($userid, $enrolment->courseid, $enrolment->cost);
                        // Update enrolment expiry date
                        // If answer from MMBR.IO is true
                        if ($result->success) {
                            $newtimeend = intval(substr(strval($result->data->timeend), 0, 10));
                            $plugin->update_user_enrol($enrolment, $userid, false, null, $newtimeend);  // false -> enrolment is active
                        } else {
                            $plugin->update_user_enrol($enrolment, $userid, true, null, null);          // true -> enrolment is deactivated
                            // Don't show error for now. Might be bad UX to show some random errors
                            // For example if it couldn't connect to our server
                            // \core\notification::error($result->errors); // Shows error to user
                        }
                    }
                }
            }
        }
    }

    /**
     * User enrolment deleted.
     * Event is triggered when user enrollment deleted
     *
     * @param object $event - This event instance
     *
     * @return null
     */
    public static function check_unenrolled_user($event)
    {
        global $DB;
        $eventdata  = $event->get_data();    // All data about this event
        if ($eventdata['other']['enrol'] === "mmbrio") {
            $userid     = $eventdata['other']['userenrolment']['userid'];
            $courseid   = $eventdata['other']['userenrolment']['courseid'];
            $enrolid    = $eventdata['other']['userenrolment']['enrolid'];
            $expiry     = $eventdata['other']['userenrolment']['timeend'];
            $plugin = enrol_get_plugin('mmbrio');
            $price = $DB->get_field('enrol', 'cost', array('id' => $enrolid), $strictness = IGNORE_MISSING);

            $mmbriokey = $plugin->get_mmbrio_key();
            $data = array(
                'public_key'   => $mmbriokey,
                'user_id'      => $userid,
                'course_id'    => $courseid,
                'price'        => $price,
                'expiry'       => $expiry,
            );
            $result = self::post('foxtrot/plugin/delete_enrollment', $data, array());
            if (!is_object($result)) {
                $result = json_decode($result);
            }
            if (intval($expiry) > 0 && !$result->success) {
                \core\notification::error(get_string('unernolfailed', 'enrol_mmbrio'));
            }
        }
    }

     /**
      * Validates if user is enrolled in course with given price
      *
      * @param int $user_id
      * @param int $course_id
      * @param int $price
      *
      * @return object $response - Response from MMBR.IO server
      *      - success: true/false,
      *          if (true) {
      *      - data: {timeend: integer }
      *          } else {
      *      - error: string
      *          }
      */
    public static function validate_user_enrolment($user_id, $course_id, $price)
    {
        $plugin = enrol_get_plugin('mmbrio');
        $mmbriokey = $plugin->get_mmbrio_key();
        $data = [
            'public_key'   => $mmbriokey,
            'user_id'      => $user_id,
            'course_id'    => $course_id,
            'price'        => $price,
        ];
        $response = self::get('foxtrot/plugin/user', $data, array());
        $response = json_decode($response);
        if ($response->success) { // validate if answer has success message
            return $response;
        } else {
            $response->success = false;
            $response->error = get_string('mmbriovaliderror', 'enrol_mmbrio');
            return $response;
        }
    }

    /**
     * Pings MMBR.IO Server when new enrolment instance is created.
     *
     * @param object $instance - Enrolment instance
     * @param object $course   - Course where instance were created
     *
     * @return $response    - Success message, if false provides error message
     */
    public static function new_enrolment_instance($instance, $course)
    {
        $response = new stdClass;
        $plugin = enrol_get_plugin('mmbrio');
        $mmbriokey = $plugin->get_mmbrio_key();
        if (!$mmbriokey) { // Check if key exists
            $response->success = false;
            $response->errors = "miss_key";
            return $response;
        }
        if (strlen($plugin->get_mmbrio_key()) < 13) {
            $response->errors = 'miss_key';
            return $response;
        }
        $data = [
            'public_key'    => $mmbriokey,
            'course_id'     => $course->id,
            'course_name'   => $course->fullname,
        ];
        $response = self::get('foxtrot/plugin/instance', $data);
        $response = json_decode($response);
        return $response;
    }

    public static function get($route, $params = array(), $options = array())
    {
        // Get plugin instance lib.php classes
        $plugin = enrol_get_plugin('mmbrio');
        $env = $plugin->get_development_env();
        $url = self::get_domain($env) . $route;
        if (!empty($params)) {
            $url .= (stripos($url, '?') !== false) ? '&' : '?';
            $url .= http_build_query($params, '', '&');
        }
        $curl = new curl;
        if (!$response = $curl->get($url)) {
            $response = new stdClass();
            return $response;
        }
        return $response;
    }

    // For now have post request on enrollment deletion
    public static function post($route, $params = array(), $options = array())
    {
        // Get plugin instance lib.php classes
        $plugin = enrol_get_plugin('mmbrio');
        $env = $plugin->get_development_env();
        $url = self::get_domain($env) . $route;
        $payload = json_encode($params);

        $curl = new curl;

        $options = array(
            'CURLOPT_RETURNTRANSFER' => true,
            'CURLOPT_POST' => true,
            'CURLOPT_HTTPHEADER' => array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($payload)),
        );

        if (!$response = $curl->post($url, $payload, $options)) {
            return $response->errors = curl_error($curl);
        }
        return $response;
    }
}
