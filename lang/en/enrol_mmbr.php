<?php
// This file is part of Moodle - http://moodle.org/
//
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
 * Plugin strings are defined here.
 *
 * @package     enrol_mmbr
 * @category    string
 * @copyright   2018 DmitryN defrakcija123@gmail.com
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['mmbr:config'] = 'Configure MMBR enrol instances';
$string['mmbr:manage'] = 'Manage user enrolments';
$string['mmbr:manageapplications'] = 'Manage MMBR enrolment';
$string['mmbr:unenrol'] = 'Cancel users from the course';
$string['mmbr:unenrolself'] = 'Cancel self from the course';

$string['pluginname'] = 'MMBR Enrolment Plugin';
$string['pluginname_desc'] = "With this plugin student can pay for paid course with Stripe. All payments are synchronized with MMBR Account";
$string['enrolname'] = 'MMBR Enrolment Plugin';

$string['userloggedin'] = 'Something has happened';

// Settings
$string['mmbrkey'] = "Enter you MMBR key";
$string['mmbrkey_desc'] = "MMBR key is used to sync all payment with your MMBR account";
// Payment options
$string['setoneprice'] = "Set one time payment amount (CAD)";
$string['setoneprice_help'] = "One time payment gives permanent access to this course";
$string['setrecprice'] = "Set subscription payment amount (CAD)";
$string['setrecprice_help'] = "Subscription option. By default users get charged every 4 week. You can change frequency below. Subscription won't be available if you leave this blank.";
$string['setpeymentfreq'] = "Change payment frequency";
$string['setpeymentfreq_help'] = "This help message for this option";

$string['role'] = "Select role once enroled";
$string['status'] = "Enrolment status";
$string['active'] = "Active";
$string['suspended'] = "Suspended";
$string['status_help'] = 'Active - user gets access to course once paid, Suspended meeans enrol needs to be activated by Teacher';
$string['fullaccess'] = 'Get Full Access';
$string['subscription'] = 'Subscription';

// Not sure why I need this, but if they miising -> ERROR!
$string['applymanage'] = 'Manage enrolment applications';
$string['show_standard_user_profile'] = 'Show standard user profile fields on enrolment screen';
$string['show_extra_user_profile'] = 'Show extra user profile fields on enrolment screen';
$string['defaultperiod'] = 'Default enrolment duration';
$string['defaultperiod_desc'] = 'Default length of time that the enrolment is valid. If set to zero, the enrolment duration will be unlimited by default.';
$string['defaultperiod_help'] = 'Default length of time that the enrolment is valid, starting with the moment the user is enrolled. If disabled, the enrolment duration will be unlimited by default.';
$string['notify_heading'] = 'Notification settings';
$string['notify_desc'] = 'Define who gets notified about new enrolment applications.';
$string['notifycoursebased'] = "New enrolment application notification (instance based, eg. course teachers)";
$string['notifycoursebased_desc'] = "Default for new instances: Notify everyone who have the 'Manage apply enrolment' capability for the corresponding course (eg. teachers and managers)";
$string['notifyglobal'] = "New enrolment application notification (global, eg. global managers and admins)";
$string['notifyglobal_desc'] = "Define who gets notified about new enrolment applications for any course.";
