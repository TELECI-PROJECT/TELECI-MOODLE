<?php
//moodleform is defined in formslib.php
require_once("$CFG->libdir/formslib.php");
 
class advancedexportdata_form extends moodleform {
    
//    $actionurl = "";
//    
//    public function __construct($actionurl) {
//        $this->actionurl = $actionurl;
//    }

    //Add elements to form
    public function definition() {
        global $CFG;
        global $DB;
 
        $mform = $this->_form; // Don't forget the underscore! 
     //   $mform->addElement('hidden', 'action', $this->actionurl);
      //  $mform->addElement('select', 'id', "Course ID"); 
        $query = "SELECT id, fullname, shortname from {course}";
        $courses = [];
        $courselist = $DB->get_records_sql($query);
        foreach ($courselist as $course) {
            $courses[$course->id] = $course->shortname;
        }
        
        
        
        $mform->addElement('select', 'id', "Course ID", $courses, array());
        
        $mform->addElement('date_time_selector', 'fromdate', 'Date from');
        $mform->addElement('date_time_selector', 'todate', 'Date to');
        
      //  $mform->addElement('text', 'email', get_string('email')); // Add elements to your form
        //$mform->setType('email', PARAM_NOTAGS);                   //Set type of element
        ///$mform->setDefault('email', 'Please enter email');        //Default value
        $this->add_action_buttons(false, "Export");    
    }
    //Custom validation should be added here
    function validation($data, $files) {
        return array();
    }
}