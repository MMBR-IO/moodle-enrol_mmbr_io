<?php

defined('MOODLE_INTERNAL') || die();
require_once $CFG->dirroot . '/user/profile/lib.php';


// in classes/observer.php
class enrol_mmbr_observer {
	public static function check_logged_user($event) {
        print_r("Event fired!");
        // $method = "POST";
        // $url = "https://webhook.site/d879f249-2604-409d-a666-fc268d56d176";
        // $data = "someKindOfAKey";
        // // $data = ['key' => 'someKindOfKey',
        // //         'teacher' => 'teachName',
        // //         'someUpdate' => 'UpdateData'];
        // $curl = curl_init();
        // curl_setopt($curl, CURLOPT_POST, 1);
        // if ($data)
        //     curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        // }
    
        // //Optional Authentication:
        // curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        // curl_setopt($curl, CURLOPT_USERPWD, "userDmitry:mypassword");
    
        // curl_setopt($curl, CURLOPT_URL, $url);
        // curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    
        // $result = curl_exec($curl);
    
        // curl_close($curl);
    }

    public static function newEnrolmentInstance($instance) {
        $method = "POST";
        $url = "http://localhost:3000/add_new_instance";
        $data = ['key' => $DB->get_record('enrol_mmbr', array('id'=>1)),
                'courseid' => $instance->courseid,
                'price' => $instance->price];
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        
        //Optional Authentication:
        // curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        // curl_setopt($curl, CURLOPT_USERPWD, "userDmitry:mypassword");
    
        // curl_setopt($curl, CURLOPT_URL, $url);
        // curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    
        $result = curl_exec($curl);

        curl_close($curl);
    }
}