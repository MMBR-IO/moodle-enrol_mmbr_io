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
 * The enrol plugin mmbr is defined here.
 *
 * @package     enrol_mmbr
 * @copyright   2018 DmitryN defrakcija123@gmail.com
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
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
     * Add new instance of enrol plugin with default settings.
     * @param object $course
     * @return int id of new instance
     */
    public function add_default_instance($course)
    {
        $fields = $this->get_instance_defaults();
        return $this->add_instance($course, $fields);
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
     * Does this plugin allow manual enrolments?
     *
     * @param stdClass $instance course enrol instance
     * All plugins allowing this must implement 'enrol/xxx:enrol' capability
     *
     * @return bool - true means user with 'enrol/xxx:enrol' may enrol others freely, false means nobody may add more enrolments manually
     */
    public function allow_enrol(stdClass $instance)
    {
        return true;
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
     * Does this plugin allow manual changes in user_enrolments table?
     *
     * All plugins allowing this must implement 'enrol/xxx:manage' capability
     *
     * @param stdClass $instance course enrol instance
     * @return bool - true means it is possible to change enrol period and status in user_enrolments table
     */
    public function allow_manage(stdClass $instance)
    {
        return true;
    }

    /**
     * Sets up navigation entries.
     *
     * @param navigation_node $instancesnode
     * @param stdClass $instance
     * @return void
     */
    // public function add_course_navigation($instancesnode, stdClass $instance) {
    //     if ($instance->enrol !== 'mmbr') {
    //          throw new coding_exception('Invalid enrol instance type!');
    //     }
    //     $context = context_course::instance($instance->courseid);
    //     if (has_capability('enrol/mmbr:config', $context)) {
    //         $managelink = new moodle_url('/enrol/mmbr/edit.php', array('courseid' => $instance->courseid, 'id' => $instance->id));
    //         $instancesnode->add($this->get_instance_name($instance), $managelink, navigation_node::TYPE_SETTING);
    //     }
    // }
    /**
     * Returns link to page which may be used to add new instance of enrolment plugin in course.
     * @param int $courseid
     * @return moodle_url page url
     */
    // public function get_newinstance_link($courseid) {
    //     $context = context_course::instance($courseid, MUST_EXIST);
    //     if (!has_capability('moodle/course:enrolconfig', $context) or !has_capability('enrol/mmbr:config', $context)) {
    //         return null;
    //     }
    //     // Multiple instances supported - different cost for different roles.
    //     return new moodle_url('/enrol/mmbr/edit.php', array('courseid' => $courseid));
    // }
    // Detecting when user select a course and check is user is enroled or not. 
    //If not send him to MMBR Payment form
    public function enrol_page_hook(stdClass $instance)
    { 
        global $CFG, $OUTPUT, $SESSION, $USER, $DB;
      //  require_once "$CFG->dirroot/enrol/mmbr/test.php";


        // Guest can't enrol in paid courses
        if (isguestuser()) {
            return null;
        }

        if ($DB->record_exists('user_enrolments', array('userid' => $USER->id, 'enrolid' => $instance->id))) {
            return $OUTPUT->notification('You are applied on the course', 'notifysuccess');
        }

        if ($instance->customint3 > 0) {
            // Max enrol limit specified.
            $count = $DB->count_records('user_enrolments', array('enrolid' => $instance->id));
            if ($count >= $instance->customint3) {
                // Bad luck, no more self enrolments here.
                return '<div class="alert alert-error">' . get_string('maxenrolledreached_left', 'enrol_mmbr') . " (" . $count . ") " . get_string('maxenrolledreached_right', 'enrol_mmbr') . '</div>';
            }
        }

        /*
            mmbr_form defines how enrol page looks like
        */
        require_once "$CFG->dirroot/enrol/mmbr/mmbr_form.php";

        $form = new enrol_mmbr_apply_form(null, $instance);

        if ($data = $form->get_data()) {
            //Only process when form submission is for this instance (multi instance support).
            if ($data->instance == $instance->id) {
                $timestart = 0;
                $timeend = 0;
                $roleid = $instance->roleid;

                $this->enrol_user($instance, $USER->id, $roleid, $timestart, $timeend, ENROL_USER_ACTIVE);
                $userenrolment = $DB->get_record(
                    'user_enrolments',
                    array(
                        'userid' => $USER->id,
                        'enrolid' => $instance->id),
                    'id', MUST_EXIST);

                redirect("$CFG->wwwroot/course/view.php?id=$instance->courseid");
            }
        }

        $output = $form->render();

        return $OUTPUT->box($output);
    }    
}
