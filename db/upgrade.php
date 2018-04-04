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
 * Upgrade scripts for course format "weeksrev"
 *
 * @package    format_weeksrev
 * @copyright  2018 Arnaud Trouvé <moodle@arnaudtrouve.fr>
 *             based on code from 2017 Marina Glancy
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Upgrade script for format_weeksrev
 *
 * @param int $oldversion the version we are upgrading from
 * @return bool result
 */
function xmldb_format_weeksrev_upgrade($oldversion) {
    global $CFG, $DB;

    require_once($CFG->dirroot . '/course/format/weeksrev/lib.php');

    if ($oldversion < 2018040700) {
        // Go through the existing courses using the weeksrev format with no value set for the 'hidefuture'.
        $sql = "SELECT c.id, cfo.id as cfoid
                  FROM {course} c
             LEFT JOIN {course_format_options} cfo
                    ON cfo.courseid = c.id
                   AND cfo.format = c.format
                   AND cfo.name = :optionname
                   AND cfo.sectionid = 0
                 WHERE c.format = :format
                   AND cfo.id IS NULL";
        $params = ['optionname' => 'hidefuture', 'format' => 'weeksrev'];
        $courses = $DB->get_recordset_sql($sql, $params);
        foreach ($courses as $course) {
            $option = new stdClass();
            $option->courseid = $course->id;
            $option->format = 'weeksrev';
            $option->sectionid = 0;
            $option->name = 'hidefuture';
            $option->value = 1;
            $DB->insert_record('course_format_options', $option);
        }
        $courses->close();

        upgrade_plugin_savepoint(true, 2018040700, 'format', 'weeksrev');
    }
    return true;
}
