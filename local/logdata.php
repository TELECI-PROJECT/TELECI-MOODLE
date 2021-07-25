<?php
/** Require the specific libraries */
require_once("../config.php");
require_once($CFG->dirroot.'/mod/lesson/locallib.php');

$id = required_param('id', PARAM_INT);

$cm = get_coursemodule_from_id('lesson', $id, 0, false, MUST_EXIST);
$course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
//$lesson = new lesson($DB->get_record('lesson', array('id' => $cm->instance), '*', MUST_EXIST), $cm, $course);

require_login($course, false, $cm);
//require_sesskey();


$databaseq = $DB->get_records_sql('SELECT * FROM {lesson_activity_log}', array());
//$data = ['success'];



header('Content-Type: application/json');
echo json_encode($databaseq);