
# TELECI-MOODLE repository: Moodle LMS installation with modifications for TELECI Project<br>
<br>
This file repository contains MOODLE 3.8.1 (Build 20200113) LMS installation files with code modifications for TELECI Project.
<br>
<br>
<br>
# Description<br>
TELECI Project Moodle LMS code modifications provides data collection, pre-processing and delivery services for TELECI data visualization web application (https://github.com/zanis-timsans/TELECI-APP).<br>
Key features of this modified Moodle LMS are as follows:<br>
<br>
1) Collection of user behaviour data based on specific events triggered by Moodle user activities:<br>
a. Content item opened,<br>
b. Multiple choice opened,<br>
c. Multiple choice submitted.<br>
<br>
Data collected includes timestamps, user id, and other content specific variables. All data is stored in a database table created specifically for this.<br>
2) Data storage and export:<br>
a. Moodle with TELECI modifications maintains a table in Moodle database for storing user behaviour data.<br>
b. MS Excel export functionality is available form Moodle Administration panel.<br>
3) Data access API: Moodle with TELECI modifications provides a REST API endpoint returning JSON formatted output for TELECI data visualization web application. Data can be queried using multiple parameters (course id and datetime).<br>
<br><br>
Moodle with TELECI modifications implements user interface improvements providing better multiple choice layout and instant feedback on answer submission (for Lesson activity plugin). Some theme elements are removed to simplify overall layout.<br>



<br>
<br>
Contacts:<br>
RTU Distance Education Study Center<br>
This is a part of the TELECI project<br>
Code edits by TELECI Team 2019
