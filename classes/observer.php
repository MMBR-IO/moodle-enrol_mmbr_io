<?php

defined('MOODLE_INTERNAL') || die();
require_once $CFG->dirroot . '/user/profile/lib.php';
require_once $CFG->dirroot . '/lib/filelib.php';

// in classes/observer.php
class enrol_mmbr_observer {

    /**
     * USER LOGGEDIN 
     * Event is triggered when User logs in Moodle
     * If this user has enrolments with MMBR plugin 
     *  - check if all enrolments is up to date
     *  - update with MMBR in case something missing
     */
	public static function check_logged_user($event) {
        // var_dump($event);
        // die();
        global $DB;
        // All data about this event
        $eventdata = $event->get_data();
        // Get from event user id
        $userid = $eventdata['userid'];
        // Check is this user is enroled with mmbr plugin
        $enrol = "enrol";
        $enrolments = $DB->get_records($enrol, array('enrol' => 'mmbr'));
        // Check is this user has enrolment with MMBR plugin
        // False -> do nothing || True -> Check if all his enrolment is up to date
        foreach ($enrolments as &$value) {
            $enrolid = $value->id;
            $records = $DB->get_records("user_enrolments", array('enrolid' => $enrolid, 'userid'=> $userid)); // Get all user enrolments
            if ($records != 0){ // If user has enrolments
                foreach ($records as &$val) {
                    if ($val->timeend != 0 && $val->timeend < time()){ // If enrolment exist and expired 
                         /**
                          * 
                          * Check with MMBR if payment been made 
                          *
                          * Curl call will be made here and if true is return enrolment will be expended
                          *
                          */

                        // Update enrolment End time 
                         
                    }
                }
            }
        }
        die();

        $data = ['key' => self::getMemberKey(),
                'msg' => "This is our member",
                ];
        $url = "https://webhook.site/d879f249-2604-409d-a666-fc268d56d176";
        $mcurl = new curl();
        $mcurl->post($url, format_postdata_for_curlcall($data), '');
        $response = $mcurl->getResponse();
    }

    private static function checkEnrolmentStatus() {
        
        return false;
    }

    /**
     * NEW ENROLMENT INSTANCE OF MMBR PLUGIN CREATED
     * When Moodle admin adds MMBR Plugin as enrolment option 
     *  - notify MMBR server about new instance
     */
    public static function newEnrolmentInstance($instance, $course) {
        global $DB;
        $data = ['key' => getMemberKey(),
                'courseid' => $course->id,
                'price' => $instance['cost'],
        ];
        $url = "https://webhook.site/d879f249-2604-409d-a666-fc268d56d176";
        $mcurl = new curl();
        $mcurl->post($url, format_postdata_for_curlcall($data), '');
        $response = $mcurl->getResponse();
        // var_dump($response);
        // die();
    }

    private static function getMemberKey() {
        global $DB;
        $keyrecord = $DB->get_record('enrol_mmbr', array('id'=>1));
        return $keyrecord->mmbr_key;
    }

    public static function course_viewed($event) {
        var_dump($event);
        die();
    }
}