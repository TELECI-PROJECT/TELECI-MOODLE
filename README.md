# TELECI-MOODLE<br>
# TELECI-MOODLE files with adaptations for TELECI Project <br>
<br>
This file repository contains MOODLE 3.8.1 (Build 20200113) LMS installation files with code adaptations for TELECI Project.
<br>
<br>
<br>
# Description
TELECI Project Moodle LMS code modifications provides data collection, pre-processing and delivery services for TELECI data visualization web application (https://github.com/zanis-timsans/TELECI-APP).
Key features of this modified Moodle LMS are as follows:
1) Collection of user behaviour data based on specific events triggered by Moodle user activities:
a. Content item opened,
b. Multiple choice opened,
c. Multiple choice submitted.
Data collected includes timestamps, user id, and other content specific variables. All data is stored in a database table created specifically for this.
2) Data storage and export:
a. Moodle with TELECI modifications maintains a table in Moodle database for storing user behaviour data.
b. MS Excel export functionality is available form Moodle Administration panel.
3) Data access API: Moodle with TELECI modifications provides a REST API endpoint returning JSON formatted output for TELECI data visualization web application. Data can be queried using multiple parameters (course id and datetime).

Moodle with TELECI modifications implements user interface improvements providing better multiple choice layout and instant feedback on answer submission (for Lesson activity plugin). Some theme elements are removed to simplify overall layout.



<br>
<br>
Contacts:<br>
RTU Distance Education Study Center<br>
This is a part of the TELECI project<br>
Code edits by TELECI Team 2019
