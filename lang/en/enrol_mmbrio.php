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
 * @package   enrol_mmbrio
 * @category  string
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright Dmitry Nagorny
 */

defined('MOODLE_INTERNAL') || die();

$string['mmbrio:config']        = 'Configure MMBR.IO enrol instances';
$string['mmbrio:manage']        = 'Manage user enrolments';
$string['mmbrio:manageapplications'] = 'Manage MMBR.IO enrolment';
$string['mmbrio:unenrol']       = 'Cancel users from the course';
$string['mmbrio:unenrolself']   = 'Cancel self from the course';

$string['pluginname']       = 'MMBR.IO Enrolment Plugin';
$string['pluginname_desc']  = "With this plugin, students can pay to enrol in a paid course.
  You set the cost of enrolment to your course: a one-time or recurring (subscription) payment.
  A MMBR.IO account is all you need to start accepting payments for your paid courses.
  Foxtrot is a product designed by MMBR.IO and is specifically made to work securely with the Moodle™ platform.";
$string['enrolname']        = 'MMBR.IO Enrolment Plugin';
$string['enrolname_short']  = 'MMBR.IO';

$string['enrolheading']     = 'Please select one of the following payment options and proceed to payment form';
$string['paymentheading']   = 'Please provide payment information to enrol in the course';
$string['enrolsuccess']     = "Thank you. You now enrolled in this course!";

// Tasks.
$string['pingserver']       = 'Ping MMBR.IO Server';

// Settings.
$string['mmbrio_set']       = "Foxtrot by MMBR.IO - Payment processing to enable enrolment into paid courses";
$string['mmbrio_set_desc']  = "Once you have a verified MMBR.IO account, get your Foxtrot key from: https://dashboard.mmbr.io/foxtrot/moodles";
$string['mmbrkey']          = "Enter your Foxtrot Public Key";
$string['mmbrkey_desc']     = "The Foxtrot public key authorizes payments to your MMBR.IO merchant account and synchronizes your enrolled students.";
$string['set_currency']     = 'Enter default currency';
$string['enrolupdated']     = "Enrolment updated";

// Payment options.
$string['cost']             = "Price for this enrolment";
$string['cost_help']        = "If Subscription option selected this price will be charged once a month";
$string['currency']         = "Select Currency";
$string['enrolmentoption']  = "Selected enrolment option: ";

// Enrolment instance names.
$string['instancename0']  = "One-time Payment";
$string['instancename1']  = "Monthly Subscription";

$string['role']           = "Select role once enroled";
$string['status']         = "Enrolment status";
$string['active']         = "Active";
$string['suspended']      = "Suspended";
$string['status_help']    = 'Suspended enrolment won\'t be available to use until Activated';
$string['fullaccess']     = 'Get Full Access';
$string['subscription']   = 'Subscription';

// Unenrolment.
$string['unenrolselfonetime']   = "This course was paid in full, if you unenrol yourself you will lose access to it.";
$string['unenrolselfsubscribe'] = "If you unenrol yourself, you will loose access to this course and you credit card won't be charged";

// Errors.
$string['mmbriodeferror']   = 'Sorry, but error occurred in MMBR.IO Plugin.';
$string['costnumerror']     = 'Price should be numeric values';
$string['costnullerror']    = 'Minimum charge should be $2';
$string['mmbriokeyerror']   = 'MMBR.IO Public Key Error. Make sure you entered right key in plugin setting';
$string['mmbrioservererror'] = 'Look like MMBR.IO Server down for maintenance. Please, try later.';
$string['mmbriokeymiserror'] = 'This plugin missing MMBR.IO Public Key in settings. Please enter it.';
$string['mmbriovaliderror'] = 'Colnd\'t validate your enrolment';
$string['mmbriocustomerkey'] = 'Right now you can not enroll in this course. Contact Moodle manager. (Error: mmbrkey)';
$string['unernolfailed']    = 'Error happened while cancelling you subscription. Please contact administrator for more information';

// Privacy API.
// Temp String to explaine why we don't store any data.
$string['privacy:metadata:mmbrio_server']            = 'In order to link payment to user we store some user id and email in MMBR.IO database';
$string['privacy:metadata:mmbrio_server:userid']     = 'Unique user id helps identify user';
$string['privacy:metadata:mmbrio_server:email']      = 'Allows to communicate with user and send receipt';
$string['privacy:metadata:mmbrio_server:enrolments'] = 'In order to validate enrolment and payment status we synchronise this data with MMBR.IO database';
