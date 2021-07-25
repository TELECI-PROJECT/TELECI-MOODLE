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
require_once 'lib.php';

$id = required_param('id', PARAM_INT); // course id
//$todate = required_param_array('fromdate'); // course id
//$fromdate = required_param_array('todate'); // course id

$fromdate = optional_param_array('fromdate', null, PARAM_INT);
$fromdate_string = $fromdate['day'] . '-' . $fromdate['month'] . '-' . $fromdate['year'] . ' ' . $fromdate['hour'] . ':' . $fromdate['minute'];
    
$todate = optional_param_array('todate', null, PARAM_INT);
$todate_string = $todate['day'] . '-' . $todate['month'] . '-' . $todate['year'] . ' ' . $todate['hour'] . ':' . $todate['minute'];
//Array
//(
//    [day] => 26
//    [month] => 4
//    [year] => 2020
//    [hour] => 17
//    [minute] => 35
//)

//echo ($fromdate_string);
//echo ($todate_string);
////
//echo (strtotime($fromdate_string));
//
//echo (strtotime($todate_string));



if (!$course = $DB->get_record('course', array('id'=>$id))) {
    print_error('nocourseid');
}

require_login($course);
$context = context_course::instance($id);
$groupid = groups_get_course_group($course, true);

require_capability('moodle/grade:export', $context);
require_capability('advancedlogexport/logdata:view', $context);


$headers = ['A' => ['title' => 'ID', 'width' => 'auto', 'format' => 'number'], 
                'B' => ['title' => 'Course ID', 'width' => 'auto', 'format' => 'number'],
                'C' => ['title' => 'Section ID', 'width' => 'auto', 'format' => 'number'],
                'D' => ['title' => 'Lesson ID', 'width' => 'auto', 'format' => 'number'], 
                'E' => ['title' => 'User ID', 'width' => 'auto', 'format' => 'number'], 
                'F' => ['title' => 'Role', 'width' => 'auto', 'format' => 'number'], 
                'G' => ['title' => 'Datetime', 'width' => 'auto'], 
                'H' => ['title' => 'Type', 'width' => 'auto'],
                'I' => ['title' => 'Item ID', 'width' => 'auto', 'format' => 'number'], 
                'J' => ['title' => 'Title', 'width' => 'auto'], 
                'K' => ['title' => 'Question', 'width' => '50'],
                'L' => ['title' => 'Answer', 'width' => '50'],
                'M' => ['title' => 'Answer submitted', 'width' => 'auto'],
                'N' => ['title' => 'Correctanswer', 'width' => 'auto'],
                ];

$databaseq = $DB->get_records_sql('SELECT * FROM {lesson_activity_log} where courseid = ? AND timestamp >= ? AND timestamp <= ?', array($id, strtotime($fromdate_string), strtotime($todate_string)));


$course_context = context_module::instance($id);



$rows = [];
foreach ($databaseq as $key => $logitem) {
    $row = [];
    $row[] = $key;
    $cm_object = json_decode($logitem->cm_object);
    $row[] = $cm_object->course;
    $row[] = $cm_object->section;
    $row[] = $cm_object->id;

	$row[] = $logitem->userid;
    
        $roles = get_user_roles($course_context, $logitem->userid, true);
       // $roleassignments = $DB->get_records('role_assignments', ['userid' => $logitem->userid]);
        $this_user_roles = [];
        foreach ($roles as $role) { 
            $this_user_roles[] = $role->shortname;
        }
    
    $row[] = implode(", ", $this_user_roles);
    
    $row[] = date("d-m-Y H:i:s", $logitem->timestamp);
    
    $page_object = json_decode($logitem->page_object);
    
    $multichoice_result = new stdClass();
    if (isset($logitem->multichoice_result)) {
        $multichoice_result = json_decode($logitem->multichoice_result);
    }
    
     
switch ($logitem->type) {
	case 1:
		$row[] = "content";
        $row[] = $page_object->id;
        $row[] = $page_object->title;
        $row[] = "";
        $row[] = "";
        $row[] = "";
        $row[] = "";
		break;
        
    case 2:
		$row[] = "multiplechoice";
        $row[] = $page_object->id;
        $row[] = $page_object->title;
        $row[] = "";
        $row[] = "";
        $row[] = "";
        $row[] = "";
		break;
        
    case 3:
		$row[] = "result";
        $row[] = $page_object->id;
        $row[] = $page_object->title;
        $row[] = strip_tags($page_object->contents);
        $row[] = strip_tags($multichoice_result->studentanswer);
        $row[] = $multichoice_result->noanswer ? "false" : "true";
        $row[] = $multichoice_result->correctanswer ? "true" : "false";
		break;    
	
	default:
		$row[] = "N/A";
        $row[] = "N/A";
        $row[] = "N/A";
        $row[] = "N/A";
        $row[] = "N/A";
        $row[] = "N/A";
        $row[] = "N/A";
		break;
}
    
    
    
    
    
    
    //$row[] = 
    
    
    $rows[] = $row;
}

    




$data = [];
$data['headers'] = $headers;
$data['comment_text'] = "TELECI Export";
$data['report_title'] = "TELECI Export";
$data['cell_values'] = $rows;    
$data['worksheet_title'] = "TELECI Export";
$data['full_filename'] = "Teleci-export.xlsx";


$export = new advancedexportlib_xls;
$export->exportExcel($data);

return $export;

$params = array(
    'includeseparator'=>true,
    'publishing' => true,
    'simpleui' => true,
    'multipledisplaytypes' => true
);
$mform = new grade_export_form(null, $params);
$data = $mform->get_data();

// Print all the exported data here.
$export = new grade_export_newgradeexport($course, $groupid, $data);
$export->print_grades();
