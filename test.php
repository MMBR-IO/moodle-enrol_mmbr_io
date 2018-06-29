<?php
defined('MOODLE_INTERNAL') || die();
global $CFG, $OUTPUT, $SESSION, $USER, $DB;
//$count = $DB->get('config_plugins', array('plugin' => "enrol_stripepayment"));

$table = 'config_plugins';
$select = "name = 'secretkey'"; //is put into the where clause
$result = $DB->get_records_select($table,$select);
print_r(__DIR__);
die();

