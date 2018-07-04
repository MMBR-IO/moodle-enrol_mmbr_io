<?php
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
 * Web service local plugin template external functions and service definitions.
 *
 * @package    localwstemplate
 * @copyright  2011 Jerome Mouneyrac
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
// We defined the web service functions to install.
$services = array(
    'mmbr_service' => array(                                                //the name of the web service
        'functions' => array ('moodle_to_mmbr'), //web service functions of this service
        'requiredcapability' => '',                //if set, the web service user need this capability to access 
                                                                            //any function of this service. For example: 'some/capability:specified'                 
        'restrictedusers' =>0,                                             //if enabled, the Moodle administrator must link some user to this service
                                                                            //into the administration
        'enabled'=>1,                                                       //if enabled, the service can be reachable on a default installation
     )
);

$functions = array(
  'moodle_to_mmbr' => array(         //web service function name
      'classname'   => 'enrol_mmbr_showmee',  //class containing the external function
      'methodname'  => 'helloMember',          //external function name
      'classpath'   => 'enrol/mmbr/classes/observer.php',  //file containing the class/external function
      'description' => 'Say hello to member',    //human readable description of the web service function
      'type'        => 'write',                  //database rights of the web service function (read, write)
      'services' => array(MOODLE_OFFICIAL_MOBILE_SERVICE)    // Optional, only available for Moodle 3.1 onwards. List of built-in services (by shortname) where the function will be included.  Services created manually via the Moodle interface are not supported.
  ),
);