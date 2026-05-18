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
 * Reactive course state override for format_weeksrev.
 *
 * @package   format_weeksrev
 * @copyright 2026 Arnaud Trouvé <moodle@arnaudtrouve.fr>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace format_weeksrev\output\courseformat\state;

use core_courseformat\output\local\state\course as course_base;
use course_modinfo;
use renderer_base;
use stdClass;

/**
 * Reverses the reactive sectionlist for non-editing users.
 *
 * The HTML content already reverses the order via
 * {@see \format_weeksrev\output\courseformat\content::get_sections_to_display()},
 * but the course index drawer and other reactive consumers read the
 * order from the state. Without this override the sidebar would show
 * weeks in chronological order while the main content shows them
 * reversed, which is confusing.
 */
class course extends course_base {

    #[\Override]
    public function export_for_template(renderer_base $output): stdClass {
        $data = parent::export_for_template($output);

        // Keep the natural order while editing so drag-and-drop and
        // section management work intuitively.
        if ($this->format->show_editor()) {
            return $data;
        }

        $modinfo = course_modinfo::instance($this->format->get_course());
        $sectionidzero = null;
        $others = [];
        foreach ($data->sectionlist as $sid) {
            $section = $modinfo->get_section_info_by_id($sid);
            if ($section && $section->section == 0) {
                $sectionidzero = $sid;
            } else {
                $others[] = $sid;
            }
        }
        $data->sectionlist = $sectionidzero !== null
            ? array_merge([$sectionidzero], array_reverse($others))
            : array_reverse($others);

        return $data;
    }
}
