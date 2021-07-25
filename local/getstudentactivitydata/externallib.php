<?php

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
 * External Web Service Template
 *
 * @package    localwstemplate
 * @copyright  2011 Moodle Pty Ltd (http://moodle.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once($CFG->libdir . "/externallib.php");

class local_getstudentactivitydata_external extends external_api {

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function functiongetstudentdata_parameters() {
        return new external_function_parameters(
//                array("json" => new external_value(PARAM_RAW, "The JSON input"))
//                array()
            
                
            array('date_from' => new external_value(PARAM_INT, 'Date from timestamp', VALUE_DEFAULT, strtotime("1 month ago")),
                  
                  'date_to' => new external_value(PARAM_INT, 'Date to timestamp', VALUE_DEFAULT, time()),
                  
            'welcomemessage' => new external_value(PARAM_TEXT, 'The welcome message. By default it is "Hello world,"', VALUE_DEFAULT, 'Hello world, '),

			'phone' => new external_value(PARAM_TEXT, 'The user name. By default it is ","', VALUE_DEFAULT, ''))
        );
    }

    /**
     * Returns welcome message
     * @return string welcome message
     */
    public static function functiongetstudentdata($date_from, $date_to, $welcomemessage = 'Hello world, ', $phone = '') {
        global $USER;

        //Parameter validation
        //REQUIRED
        $params = self::validate_parameters(self::functiongetstudentdata_parameters(),
                array('welcomemessage' => $welcomemessage,
			'phone' => $phone)
);

        //Context validation
        //OPTIONAL but in most web service it should present
        $context = get_context_instance(CONTEXT_USER, $USER->id);
        self::validate_context($context);

        //Capability checking
        //OPTIONAL but in most web service it should present
        if (!has_capability('moodle/user:viewdetails', $context)) {
            throw new moodle_exception('cannotviewprofile');
        }


	global $DB;
        
    //$databaseq = $DB->get_records_sql('SELECT * FROM {lesson_activity_log} where courseid = ? AND timestamp >= ? AND timestamp <= ?', array($id, strtotime($fromdate_string), strtotime($todate_string)));
    $courseid = 2;
    
    $fromdate_string = $date_from; 
    $todate_string = $date_to;    
    $databaseq = $DB->get_records_sql('SELECT * FROM {lesson_activity_log} where courseid = ? AND timestamp >= ? AND timestamp <= ?', array($courseid, ($fromdate_string), ($todate_string)));
    
        
    $course_context = context_module::instance($courseid);




        
        
        
    $rows = [];   
    foreach ($databaseq as $key => $logitem) {
	
        $row = [];
        $row["id"] = $key;
        $cm_object = json_decode($logitem->cm_object);
        $row["courseid"] = (int) $cm_object->course;
        $row["lessonid"] = (int) $cm_object->id;
        $row["section"] = (int) $cm_object->section;
        
        $roles = get_user_roles($course_context, $logitem->userid, true);
       // $roleassignments = $DB->get_records('role_assignments', ['userid' => $logitem->userid]);
        $this_user_roles = [];
        foreach ($roles as $role) { 
            $this_user_roles[] = $role->shortname;
        }

        
        $row["user"] = ["id" => (int) $logitem->userid, "roles" => $this_user_roles];

        $row["datetime"] = date("d-m-Y H:i:s", $logitem->timestamp);
        $row["timestamp"] = (int) $logitem->timestamp;

        $page_object = json_decode($logitem->page_object);

        $multichoice_result = new stdClass();
        if (isset($logitem->multichoice_result)) {
            $multichoice_result = json_decode($logitem->multichoice_result);
        }
        
        switch ($logitem->type) {
            case 1:
                $row["type"] = "content";
                $row["itemid"] = (int) $page_object->id;
                $row["title"] = $page_object->title;
                $row["question"] = "";
                $row["answer"] = "";
                $row["answer_submitted"] = "";
                $row["correct_answer"] = "";
                break;

            case 2:
                $row["type"] = "multiplechoice";
                $row["itemid"] = (int) $page_object->id;
                $row["title"] = $page_object->title;
                $row["question"] = "";
                $row["answer"] = "";
                $row["answer_submitted"] = "";
                $row["correct_answer"] = "";
                break;

            case 3:
                $row["type"] = "result";
                $row["itemid"] = (int) $page_object->id;
                $row["title"] = $page_object->title;
                $row["question"] = strip_tags($page_object->contents);
                $row["answer"] = strip_tags($multichoice_result->studentanswer);
                $row["answer_submitted"] = $multichoice_result->noanswer ? "false" : "true";
                $row["correct_answer"] = $multichoice_result->correctanswer ? "true" : "false";
                break;    

            default:
                $row["type"] = "N/A";
                $row["itemid"] = "N/A";
                $row["title"] = "N/A";
                $row["question"] = "N/A";
                $row["answer"] = "N/A";
                $row["answer_submitted"] = "N/A";
                $row["correct_answer"] = "N/A";
                break;
        }
  
        
        
        $rows[] = $row;
        
    }   
        
        //print_r($databaseq);
	//$user = $DB->get_record_sql('SELECT id FROM {user} WHERE idnumber = ?', array($params['phone']));
    // $databaseq = ["var" => "hey"];   
	if ($databaseq) {
        
            $result = [];
        
            $result['data'] = $rows;
            //$result['code'] = '200';
            header('Content-Type: application/json');
            print json_encode($result); 
            die();
        
        
        
        
        
	   //return json_encode($databaseq);
    } else {

	   return FALSE; }

    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function functiongetstudentdata_returns() {
        return new external_value(PARAM_RAW, 'The updated JSON output'); 
    }
    
    
//    public static function list_updated($prevtime){
//   
//      if(!empty($list_course_array)){           
//            $result['result'] = $list_course_array;
//            $result['code'] = '200';
//            header('Content-Type: application/json');
//            print json_encode($result); 
//            die();
//        }
//        return $result;      
//    } 




}
