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
 * Specialised restore for format_weeksrev
 *
 * @package   format_weeksrev
 * @category  backup
 * @copyright 2018 Arnaud Trouvé <moodle@arnaudtrouve.fr>
 *            based on code from 2017 Marina Glancy
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Specialised restore for format_weeksrev.
 *
 * @package   format_weeksrev
 * @category  backup
 * @copyright 2018 Arnaud Trouvé <moodle@arnaudtrouve.fr>
 *            based on code from 2017 Marina Glancy
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class restore_format_weeksrev_plugin extends restore_format_plugin {

    /**
     * Handles setting the automatic end date for a restored course.
     *
     * @param int $enddate The end date in the backup file.
     */
    protected function update_automatic_end_date($enddate) {
        global $DB;

        // At this stage the 'course_format_options' table will already have a value set for this option as it is
        // part of the course format and the default will have been set.
        // Get the current course format option.
        $params = [
            'courseid' => $this->step->get_task()->get_courseid(),
            'format' => 'weeksrev',
            'sectionid' => 0,
            'name' => 'automaticenddate'
        ];
        $cfoid = $DB->get_field('course_format_options', 'id', $params);

        $update = new stdClass();
        $update->id = $cfoid;
        if (empty($enddate)) {
            $update->value = 1;
            $DB->update_record('course_format_options', $update);

            // Now, let's update the course end date.
            format_weeksrev::update_end_date($this->step->get_task()->get_courseid());
        } else {
            $update->value = 0;
            $DB->update_record('course_format_options', $update);

            // The end date may have been changed by observers during restore, ensure we set it back to what was in the backup.
            $DB->set_field('course', 'enddate', $enddate, ['id' => $this->step->get_task()->get_courseid()]);
        }
    }

    /**
     * Creates a dummy path element in order to be able to execute code after restore.
     *
     * @return restore_path_element[]
     */
    public function define_course_plugin_structure() {
        // Dummy path element is needed in order for after_restore_course() to be called.
        return [new restore_path_element('dummy_course', $this->get_pathfor('/dummycourse'))];
    }

    /**
     * Dummy process method.
     */
    public function process_dummy_course() {

    }

    /**
     * Executed after course restore is complete.
     */
    public function after_restore_course() {
        $backupinfo = $this->step->get_task()->get_info();
        if ($backupinfo->original_course_format !== 'weeksrev') {
            // Backup from another course format.
            return;
        }

        $data = $this->connectionpoint->get_data();

        // Backup may not include the end date, so set it to 0.
        $enddate = isset($data['tags']['enddate']) ? $data['tags']['enddate'] : 0;

        // Set the automatic end date setting and the course end date (if applicable).
        $this->update_automatic_end_date($enddate);
    }
}
