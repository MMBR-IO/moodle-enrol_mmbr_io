<?php

defined('MOODLE_INTERNAL') || die();
require_once $CFG->dirroot . '/user/profile/lib.php';


// in classes/observer.php
class enrol_mmbr_showmee {
	public static function test_user_log($event) {
        $method = "POST";
        $url = "https://webhook.site/d879f249-2604-409d-a666-fc268d56d176";
        $data = "someKindOfAKey";
        // $data = ['key' => 'someKindOfKey',
        //         'teacher' => 'teachName',
        //         'someUpdate' => 'UpdateData'];
        $curl = curl_init();

        switch ($method)
        {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
    
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_PUT, 1);
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }
    
        //Optional Authentication:
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, "userDmitry:mypassword");
    
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    
        $result = curl_exec($curl);
    
        curl_close($curl);
    }

    public static function helloMember($user) {
        print_r($user);
    }
}