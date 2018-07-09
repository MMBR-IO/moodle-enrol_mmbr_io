<?php

$observers = array(
    array(
        'eventname' => '\core\event\user_loggedin',
        'includefile' => '/enrol/mmbr/classes/observer.php',
        'callback' => "enrol_mmbr_observer::check_logged_user",
    ),
    array(
        'eventname' => '\core\event\course_module_instance_list_viewed',
        'includefile' => '/enrol/mmbr/classes/observer.php',
        'callback' => "enrol_mmbr_observer::course_viewed",
    ),

);