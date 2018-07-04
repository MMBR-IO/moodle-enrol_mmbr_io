<?php
// This file is NOT a part of Moodle - http://moodle.org/
//
// This client for Moodle 2 is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
/**
 * REST client for Moodle 2
 * Return JSON or XML format
 *
 * @authorr Jerome Mouneyrac
 */
/// SETUP - NEED TO BE CHANGED
$token = '8390455bca6dfda8dda4aaade59800a3';
$domainname = 'http://192.168.33.10/moodle35/';
$functionname = 'core_user_create_users';


//http://192.168.33.10/moodle35/webservice/rest/server.php?wstoken=8390455bca6dfda8dda4aaade59800a3&wsfunction=moodle_to_mmbr

$user = array(
    "username" => "username", // must be unique.
    "password" => "pass",

);

$users = array($user); // must be wrapped in an array because it's plural.

$param = array("users" => $users); // the paramater to send


$serverurl = $domainname . '/webservice/rest/server.php'. '?wstoken=' . 
             $token . '&wsfunction='.$functionname;

require_once('curl.php'); // You can put it in the top.
$curl = new curl;

$restformat = ($restformat == 'json')?'&moodlewsrestformat=' . 
               $restformat:'';

$resp = $curl->post($serverurl . $restformat, $param);