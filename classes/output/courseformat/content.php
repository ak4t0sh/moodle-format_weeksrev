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
 * Course output overrides for format_weeksrev.
 *
 * @package   format_weeksrev
 * @copyright 2026 Arnaud Trouvé <moodle@arnaudtrouve.fr>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace format_weeksrev\output\courseformat;

use core_courseformat\output\local\content as content_base;
use course_modinfo;

/**
 * Course content output class for format_weeksrev.
 *
 * Renders the standard course content but reverses the order of listed
 * sections for non-editing users so the most recent week appears first.
 * Section 0 (general) is kept at the top.
 */
class content extends content_base {

    #[\Override]
    protected function get_sections_to_display(course_modinfo $modinfo): array {
        $sections = parent::get_sections_to_display($modinfo);

        // Preserve standard order when editing so teachers manipulate sections naturally.
        if ($this->format->show_editor()) {
            return $sections;
        }

        // Keep section 0 (general) at the top; reverse the remainder.
        $general = [];
        $others = [];
        foreach ($sections as $section) {
            if ($section->section == 0) {
                $general[] = $section;
            } else {
                $others[] = $section;
            }
        }
        return array_merge($general, array_reverse($others));
    }
}
