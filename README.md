moodle-format_weeksrev
=====================

[![Build Status](https://travis-ci.org/ak4t0sh/moodle-format_weeksrev.svg?branch=master)](https://travis-ci.org/ak4t0sh/moodle-format_weeksrev)

Compatibility
-------------
This branch targets **Moodle 5.2** and later. Support for Moodle versions prior to 3.3 has been dropped.

Overview
--------
This is a clone of the Moodle weekly course format except the weeks are listed in reverse order (most recent week first) for non-editing users. In editing mode teachers see the natural chronological order to keep section management intuitive. The reverse order is propagated to the course index drawer (sidebar) as well, so both views stay in sync.

Plugin settings
---------------
* __Allow indentation on course page__ (default: yes): same semantic as the standard weekly format, lets users with the manage activities capability indent items on the course page.
* __Maximum number of sections__ (default: 52): caps the "Number of sections" select when creating a new course.

Course-level options
--------------------
* Settings from the standard weekly format
* __Hide future weeks__ (yes/no - default: yes): hide all future weeks from the student view so that the current week is shown at the top of the list.
When enabled it will completely override the course setting "Hidden sections" for future weeks.
As a teacher you can see future weeks and should leave them unhidden so that they do become visible when that week arrives.
* __Calculate the end date from the number of sections__ (default: yes): automatically sets the course end date to the last week's last day.

Installation
------------
Install the plugin, like any other plugin.
See http://docs.moodle.org/en/Installing_plugins for details on installing Moodle plugins.

Useful links
---------------
* [Bug tracker](https://github.com/ak4t0sh/moodle-format_weeksrev/issues)

Contributors
------------
* Original author: Mat Cannings
* Currently maintained by Arnaud Trouvé