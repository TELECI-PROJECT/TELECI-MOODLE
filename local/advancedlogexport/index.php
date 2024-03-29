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

require_once '../../config.php';
require_once 'grade_export_newgradeexport.php';
require_once 'exportform.php';

$id = required_param('id', PARAM_INT); // course id

$PAGE->set_url('/local/advancedlogexport/index.php', array('id'=>$id));

if (!$course = $DB->get_record('course', array('id'=>$id))) {
    print_error('nocourseid');
}

require_login($course);
$context = context_course::instance($id);

require_capability('moodle/grade:export', $context);
require_capability('advancedlogexport/logdata:view', $context);

print_grade_page_head($COURSE->id, 'export', 'newgradeexport', "TELECI Advanced log data export");
export_verify_grades($COURSE->id);

if (!empty($CFG->gradepublishing)) {
    $CFG->gradepublishing = has_capability('advancedlogexport/logdata:publish', $context);
}

$actionurl = new moodle_url('/local/advancedlogexport/export.php');
$formoptions = array(
    'includeseparator'=>true,
    'publishing' => true,
    'simpleui' => true,
    'multipledisplaytypes' => true
);

//$mform = new grade_export_form($actionurl, $formoptions);
$mform = new advancedexportdata_form($actionurl, $formoptions);

$groupmode    = groups_get_course_groupmode($course);   // Groups are being used.
$currentgroup = groups_get_course_group($course, true);
if (($groupmode == SEPARATEGROUPS) &&
        (!$currentgroup) &&
        (!has_capability('moodle/site:accessallgroups', $context))) {

    echo $OUTPUT->heading(get_string("notingroup"));
    echo $OUTPUT->footer();
    die;
}

groups_print_course_menu($course, 'index.php?id='.$id);
echo '<div class="clearer"></div>';

$mform->display();

echo $OUTPUT->footer();
