<?php
/**
 * @package    enrol_mmbr
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     MMBR.IO
 */

$tasks = array(                                                                                                                     
    array(                                                                                                                          
        'classname' => 'enrol_mmbr\task\ping_mmbrio_server',                                                                            
        'blocking' => 0,                                                                                                            
        'minute' => '*',                                                                                                            
        'hour' => '*',                                                                                                              
        'day' => '*',                                                                                                               
        'dayofweek' => '*',                                                                                                         
        'month' => '*'                                                                                                              
    )
);