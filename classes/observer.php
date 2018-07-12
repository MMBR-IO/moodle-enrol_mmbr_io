<?php

defined('MOODLE_INTERNAL') || die();
require_once $CFG->dirroot . '/user/profile/lib.php';
require_once $CFG->dirroot . '/lib/filelib.php';
require_once $CFG->dirroot . '/enrol/mmbr/lib.php';

// in classes/observer.php
class enrol_mmbr_observer {
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
         //   var_dump(gettype($value->customint1));
            $enrolid = $value->id;
            $records = $DB->get_records("user_enrolments", array('enrolid' => $enrolid, 'userid'=> $userid)); // Get all user enrolments
            if ($records != 0){ // If user has enrolments
                foreach ($records as &$val) {
                    $temp = true;
                    $mmbrenrol = new enrol_mmbr_plugin();
                    if ($val->timeend > 0 && $val->timeend < time()){ // If enrolment exist and expired 
                        // Userid
                        // Course 
                        // public_key
                        // 
                         /**
                          * 
                          * Check with MMBR.IO if payment been made 
                          *
                          * Curl call will be made here and if true is return enrolment will be expended
                          *
                          */

                        // Update enrolment expiry date
                        if($temp) { // If answer from MMBR.IO is true
                            $newtimeend = time() + $value->customint2; // Current time + payment frequency from Enrolment Instance 
                            $mmbrenrol->update_user_enrol($value, $userid, false,null, $newtimeend);
                            // Check if expiry date didn't expire LS
                        } else {
                            $mmbrenrol->update_user_enrol($value, $userid, true,null, null);
                        }
                         
                    }
                }
            }
        }
        $data = ['key' => $plugin->get_mmbr_io_key(),
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
     * NEW ENROLMENT INSTANCE OF MMBR.IO PLUGIN CREATED
     * When Moodle admin adds MMBR Plugin as enrolment option 
     *  - notify MMBR.IO server about new instance
     */
    public static function newEnrolmentInstance($instance, $course) {
        global $DB;
        $plugin = enrol_get_plugin('mmbr');
        $data = ['key' => $plugin->get_mmbr_io_key(),
                'courseid' => $course->id,
                'price' => $instance['cost'],
        ];
        $url = "https://webhook.site/d879f249-2604-409d-a666-fc268d56d176";
        $mcurl = new curl();
        $mcurl->post($url, format_postdata_for_curlcall($data), '');
        $response = $mcurl->getResponse();
    }

    public static function course_viewed($event) {
        var_dump($event);
        die();
    }

    /**
     * NEW MMBR.IO PLUGIN INSTALLED
     * When installation of this plugin happened 
     *  - notify MMBR server about new plugin
     */
    public static function newPluginInstall($instance, $course) {
        //Call MMBR.IO
    }

    /**
     * THIS WILL UNENROL USER FROM SUBSCRIPTION
     * 
     * core\event\user_password_updated.php	user_password_updated	core	user	updated	
    *core\event\user_enrolment_created	user_enrolled	core	user_enrolment	created	
    *core\event\user_enrolment_deleted	user_unenrolled	core	user_enrolment	deleted	
    *core\event\user_enrolment_updated	user_enrol_modified	core	user_enrolment	updated
     */



    // ENrol
    //courseid
    //usrid
    //price
    //cur
    //freq
    /**
     * THIS WILL CONFIRM PAYMENT
     * 
     * @paymentkey - got from Stripe transaction
     * 
     * 
     */
    public function verifyPayment($paymentkey){
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
        $mcurl = new curl();
        $mcurl->get($url, $data, '');
     //   if ($response = $mcurl->getResponse()) {
         if ($response) {
            if ($response->success) {
                return $response;
            }
        }
        return false;
    }


      
}