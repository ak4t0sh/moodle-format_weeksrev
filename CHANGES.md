5.2.0
-----

* Moodle 5.2 technical modernisation:
  * Switch the rendering pipeline to the modern `core_courseformat` output classes (`format.php` now renders the `content` output, the legacy `print_*_section_page()` calls are gone).
  * Reverse section order moved from the legacy renderer override to `format_weeksrev\output\courseformat\content::get_sections_to_display()`.
  * Reverse order also propagated to the reactive course state via `format_weeksrev\output\courseformat\state\course`, so the course index drawer matches the main content.
  * Adopt the 5.2 format hooks: `uses_course_index`, `uses_indentation`, `page_title`, `get_section_highlighted_name`, `get_required_jsfiles`, `supports_components`.
  * Add plugin settings `indentation` and `maxinitialsections` (parity with `format_weeks` 5.2).
  * Replace the deprecated `hiddensections_help` button with a `choicedropdown` backed by `choicelist`.
  * Update `get_view_url()` for Moodle 5.2 subsection handling.
  * Drop the YUI `format.js`, the unused legacy CSS files, the pre-Moodle 3.3 backup compatibility code, and the dead `weeklyoutline` / `futureweek` / `futureweeks` language strings.
  * Bump `$plugin->requires` to Moodle 5.2.0 (2026041000).

4.0.0
-----

* Moodle 4.5+ compatibility. Thanks to 1katoda(@1katoda), Vanja Dimkovska (@vanjadi) and Martin Božič (@bozicm).

3.2.0
-----

* Fix privacy provider `get_reason` declaration. This leads to drop PHP5 (Moodle 3.3 and prior) support

v3.1.0
------
* Add new function to return the course format settings for external clients via Web Services in Moodle 3.5 and higher.  
See [MDL-61198](https://tracker.moodle.org/browse/MDL-61198).
* Add privacy implementation / GDPR compliance.

v3.0.0
------
* Add support for Moodle versions 3.3 and higher by complete rewrite using Moodle 3.3 format_weeks as code base
* Add "Hide future week" settings
