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
 * @package     enrol_mmbr
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright   Dmitry Nagorny
 */

defined('MOODLE_INTERNAL') || die();

// The base class 'enrol_plugin' can be found at lib/enrollib.php. Override
// methods as necessary.

/**
 * Class enrol_mmbr_plugin.
 */
class enrol_mmbr_plugin extends enrol_plugin
{
     /**
     * Returns name of this enrol plugin
     * @return string
     */
    public function get_name() {
        // second word in class is always enrol name, sorry, no fancy plugin names with _
        $words = explode('_', get_class($this));
        return $words[1];
    }

    /**
     * We are a good plugin and don't invent our own UI/validation code path.
     *
     * @return boolean
     */
    public function use_standard_editing_ui() {
        return false;
    }
    /**
     *
     * All plugins allowing this must implement 'enrol/xxx:manage' capability
     *
     * @param stdClass $instance course enrol instance
     * @return bool - true means it is possible to change enrol period and status in user_enrolments table
     */
    public function allow_manage(stdClass $instance)
    {
        return has_capability('enrol/mmbr:manage', context_course::instance($instance->courseid));
    }
/**
     * Returns optional enrolment information icons.
     *
     * This is used in course list for quick overview of enrolment options.
     *
     * We are not using single instance parameter because sometimes
     * we might want to prevent icon repetition when multiple instances
     * of one type exist. One instance may also produce several icons.
     *
     * @param array $instances all enrol instances of this type in one course
     * @return array of pix_icon
     */
    public function get_info_icons(array $instances) {
        $found = false;
        foreach ($instances as $instance) {
            if ($instance->enrolstartdate != 0 && $instance->enrolstartdate > time()) {
                continue;
            }
            if ($instance->enrolenddate != 0 && $instance->enrolenddate < time()) {
                continue;
            }
            $found = true;
            break;
        }
        if ($found) {
            return array(new pix_icon('icon', get_string('pluginname', 'enrol_mmbr'), 'enrol_mmbr'));
        }
        return array();
    }
    /**
     * Is it possible to hide/show enrol instance via standard UI?
     * @param  stdClass $instance
     * @return bool
     */
    public function can_hide_show_instance($instance)
    {
        $context = context_course::instance($instance->courseid);
        return has_capability('enrol/mmbr:config', $context);
    }

    /**
     * Is it possible to delete enrol instance via standard UI?
     *
     * @param stdClass $instance
     * @return bool
     */
    public function can_delete_instance($instance)
    {
        $context = context_course::instance($instance->courseid);
        return has_capability('enrol/mmbr:config', $context);
    }
    /**
     * Return whether or not, given the current state, it is possible to edit an instance
     * of this enrolment plugin in the course. Used by the standard editing UI
     * to generate a link to the edit instance form if editing is allowed.
     *
     * @param stdClass $instance
     * @return boolean
     */
    public function can_edit_instance($instance) {
        $context = context_course::instance($instance->courseid);
        return has_capability('enrol/' . $instance->enrol . ':config', $context);
    }

   /**
     * Defines if 'enrol me' link will be shown on course page.
     * @param stdClass $instance of the plugin
     * @return bool(true or false)
     */
    public function show_enrolme_link(stdClass $instance) {
        return ($instance->status == ENROL_INSTANCE_ENABLED);
    }
    /**
     * Does this plugin allow manual unenrolment of all users?
     * All plugins allowing this must implement 'enrol/xxx:unenrol' capability
     *
     * @param stdClass $instance course enrol instance
     * @return bool - true means user with 'enrol/xxx:unenrol' may unenrol others freely, false means nobody may touch user_enrolments
     */
    public function allow_unenrol(stdClass $instance)
    {
        return true;
    }
    /**
     * This add 'Edit' icon on admin panel to allow edit existing instance
     * Has possibility to add more icons for additional functionality 
     * Create icon and add to $icons array 
     *
     * @param stdClass $instance course enrol instance
     * @return icons - List on icons that will be added to plugin instance
     */
    public function get_action_icons(stdClass $instance) {
        global $OUTPUT;

        if ($instance->enrol !== 'mmbr') {
            throw new coding_exception('invalid enrol instance!');
        }
        $context = context_course::instance($instance->courseid);

        $icons = array();

        if (has_capability('enrol/mmbr:config', $context)) {
            $editlink = new moodle_url("/enrol/mmbr/edit.php", array('courseid' => $instance->courseid, 'id' => $instance->id));
            $icons[] = $OUTPUT->action_icon($editlink, new pix_icon(
                't/edit',
                get_string('edit'),
                'core',
                array('class' => 'iconsmall')));
        }
        return $icons;
    }
    /**
     * Sets up navigation entries.
     *
     * @param stdClass $instancesnode
     * @param stdClass $instance
     * @return void
     */
    public function add_course_navigation($instancesnode, stdClass $instance) {
        if ($instance->enrol !== 'mmbr') {
             throw new coding_exception('Invalid enrol instance type!');
        }

        $context = context_course::instance($instance->courseid);
        if (has_capability('enrol/mmbr:config', $context)) {
            $managelink = new moodle_url('/enrol/mmbr/edit.php', array('courseid' => $instance->courseid, 'id' => $instance->id));
            $instancesnode->add($this->get_instance_name($instance), $managelink, navigation_node::TYPE_SETTING);
        }
    }
    /**
     * Does this plugin allow manual unenrolment of a specific user?
     * All plugins allowing this must implement 'enrol/xxx:unenrol' capability
     *
     * This is useful especially for synchronisation plugins that
     * do suspend instead of full unenrolment.
     *
     * @param stdClass $instance course enrol instance
     * @param stdClass $ue record from user_enrolments table, specifies user
     *
     * @return bool - true means user with 'enrol/xxx:unenrol' may unenrol this user, false means nobody may touch this user enrolment
     */
    public function allow_unenrol_user(stdClass $instance, stdClass $ue)
    {
        return $this->allow_unenrol($instance);
    }

    /**
     * Returns link to page which may be used to add new instance of enrolment plugin in course.
     * @param int $courseid
     * @return moodle_url page url
     */
    public function get_newinstance_link($courseid) {
        $context = context_course::instance($courseid, MUST_EXIST);
        if (!has_capability('moodle/course:enrolconfig', $context) or !has_capability('enrol/mmbr:config', $context)) {
            return null;
        }
        // Multiple instances supported - different cost for different roles.
        return new moodle_url('/enrol/mmbr/edit.php', array('courseid' => $courseid));
    }
    
     /**
     * Creates course enrol form, checks if form submitted
     * and enrols user if necessary. It can also redirect.
     *
     * @param stdClass $instance
     * @redirect redirects to the custom enrolment page
     */
    public function enrol_page_hook(stdClass $instance) { 
        global $CFG, $OUTPUT, $SESSION, $USER, $DB;
        // Guest can't enrol in paid courses
        if (isguestuser()) {
            return null;
        }
       
        // Get all instances for this course 
        $instances = self::enrol_get_instances($instance->courseid, true);
        $fid = key($instances);

        if ($instance->id == $fid){
            $courseid = $instance->courseid;
            $url = new moodle_url('/enrol/mmbr/enrol.php', array("courseid" => $courseid));
            redirect($url);
        }
        
    }   

    /**
     * Store user_enrolments changes and trigger event.
     * Get used for subscription, if payment occures extend 'timeend' if no suspend enrolment; 
     *
     * @param stdClass $instance
     * @param int $userid
     * @param int $status
     * @param int $timestart
     * @param int $timeend
     * @return void
     */
    public function update_user_enrol(stdClass $instance, $userid, $status = NULL, $timestart = NULL, $timeend = NULL) {
        global $DB, $USER, $CFG;
        $name = $this->get_name();
        if ($instance->enrol !== $name) {
            throw new coding_exception('invalid enrol instance!');
        }
        if (!$ue = $DB->get_record('user_enrolments', array('enrolid'=>$instance->id, 'userid'=>$userid))) {
            // weird, user not enrolled
            return;
        }
        $modified = false;
        if (isset($status) and $ue->status != $status) {
            $ue->status = $status;
            $modified = true;
        }
        if (isset($timestart) and $ue->timestart != $timestart) {
            $ue->timestart = $timestart;
            $modified = true;
        }
        if (isset($timeend) and $ue->timeend != $timeend) {
            $ue->timeend = $timeend;
            $modified = true;
        }
        if (!$modified) {
            // no change
            return;
        }
        $ue->modifierid = $USER->id;
        $ue->timemodified = time();
        $DB->update_record('user_enrolments', $ue);
        context_course::instance($instance->courseid)->mark_dirty(); // reset enrol caches
        // Invalidate core_access cache for get_suspended_userids.
        cache_helper::invalidate_by_definition('core', 'suspended_userids', array(), array($instance->courseid));
        // Trigger event.
        $event = \core\event\user_enrolment_updated::create(
                array(
                    'objectid' => $ue->id,
                    'courseid' => $instance->courseid,
                    'context' => context_course::instance($instance->courseid),
                    'relateduserid' => $ue->userid,
                    'other' => array('enrol' => $name)
                    )
                );
        $event->trigger();
        require_once($CFG->libdir . '/coursecatlib.php');
        coursecat::user_enrolment_changed($instance->courseid, $ue->userid,
                $ue->status, $ue->timestart, $ue->timeend);
    }

/**
 * Return enrolment instance by id
 * @param int   $instanceid
 * @param bool  $enable
 * @return stdClass $instance
 */
function enrol_get_instance($instanceid, $enable){
    global $DB, $CFG;
    $status = ($enable)?0:1;
    try {
        $result = $DB->get_records('enrol', array('id' => $instanceid, 'status' => $status)); 
    } catch (Exception $e) {
        throw new coding_exception( "<b>Exception:</b> " .$exception->getMessage());
    }
    $instance = new stdClass();
    foreach ($result as $inst) {
        foreach ($inst as $key => $value) {
            if (is_array($value)) {
                $value = convertToObject($value);
            }
            $instance->$key = $value;
        }
    }
    return $instance;
}

/**
 * Returns enrolment instances in given course.
 * @param int $courseid
 * @param bool $enabled
 * @return array of enrol instances
 */
function enrol_get_instances($courseid, $enabled) {
    global $DB, $CFG;
    if (!$enabled) {
        return $DB->get_records('enrol', array('courseid'=>$courseid), 'sortorder,id');
    }
    $result = $DB->get_records('enrol', array('courseid'=>$courseid, 'status'=>ENROL_INSTANCE_ENABLED), 'sortorder,id');
    $enabled = explode(',', $CFG->enrol_plugins_enabled);
    foreach ($result as $key=>$instance) {
        if (!in_array($instance->enrol, $enabled)) {
            unset($result[$key]);
            continue;
        }
        if (!file_exists("$CFG->dirroot/enrol/$instance->enrol/lib.php")) {
            // broken plugin
            unset($result[$key]);
            continue;
        }
        if ($instance->enrol === 'manual'){
            // We dont need this 
            unset($result[$key]);
            continue;
        }
    }
    return $result;
}

/**
 * Lists all currencies available for plugin.
 * @return $currencies
 */
public function get_currencies() {
    $codes = array(
        'AUD', 'BRL', 'CAD', 'CHF', 'CZK', 'DKK', 'EUR', 'GBP', 'HKD', 'HUF', 'ILS', 'JPY',
        'MXN', 'MYR', 'NOK', 'NZD', 'PHP', 'PLN', 'RUB', 'SEK', 'SGD', 'THB', 'TRY', 'TWD', 'USD');
    $currencies = array();
    foreach ($codes as $c) {
        $currencies[$c] = new lang_string($c, 'core_currencies');
    }
    return $currencies;
}

/**
 * Returns all available enrolment options
 * If null passed returns all possible options
 * If id passed returns options name
 * @return $options
 */
public function get_enrolment_options($id = NULL) {
    if($id == NULL) {
    $options = array();
        for ($i = 0; $i< 4; $i++) {
            $options[] = get_string('instancename'.$i.'', 'enrol_mmbr');
        }
    return $options;
    } else {
        return get_string('instancename'.$id.'', 'enrol_mmbr');
    }
}

public function confirm_enrolment($key, $instanceid){
    global $DB, $CFG, $USER;
    // Confirm with MMBR.IO that payment successful  
    require('classes/observer.php');
    $observer = new enrol_mmbr_observer();
    if ($response = $observer->verify_payment($key)) {
        $timestart  = 0;
        $timeend    = 0;
        if($response->enrolment['expiry'] > 0){
            $timeend = $response->enrolment['expiry'];
        }
        // Get instance 
        $instance = $this->enrol_get_instance($instanceid, true);
        $roleid = $instance->roleid;
        // Enrol user in the course
        $this->enrol_user($instance, $USER->id, $roleid, $timestart, $timeend, ENROL_USER_ACTIVE);
        $userenrolment = $DB->get_record(
            'user_enrolments',
            array(
                'userid' => $USER->id,
                'enrolid' => $instance->id),
            'id', MUST_EXIST);

        redirect("$CFG->wwwroot/course/view.php?id=$instance->courseid");
    } else {
        die("Couldn't verify payment");
    }
}


/**
 * Converts all currency to cent value
 * @param $cost reqular price
 * @return $cents price value in cents
 */
public function get_cost_cents($cost) {
    if (is_string($cost)) {
        $cost = floatval($cost);
    }
    $cents = round($cost,2)*100;
    return $cents;
}

/**
 * Converts cost from cent value to dollar
 * @param $cost cost in cents
 * @return $full cost in dollars
 */
public function get_cost_full($cost) {
    if (is_string($cost)) {
        $cost = floatval($cost);
    }
    $full = round($cost,2)/100;
    return $full;
}
/**
 * Get public key 
 * @return $key - public key for this instance
 */
public function get_mmbr_io_key() {
    global $DB;
    $keyrecord = $DB->get_record_select('config_plugins',"plugin = 'enrol_mmbr' AND name = 'mmbrkey'");
    return $keyrecord->value;
}

}
