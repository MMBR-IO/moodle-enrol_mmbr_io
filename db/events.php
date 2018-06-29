<?php

$observers = array(
    array(
        'eventname' => '\core\event\user_loggedin',
        'includefile' => '/enrol/mmbr/classes/observer.php',
        'callback' => "enrol_mmbr_showmee::test_user_log",
    )

);