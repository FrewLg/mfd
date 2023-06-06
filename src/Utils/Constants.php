<?php
namespace App\Utils;

class Constants{

    //roles
     const ROLE_ADMIN="ROLE_ADMIN";
     const  PRESIDENT= "ROLE_PRESIDENT";
     const  VICE_PRESIDENT= "ROLE_VICEPRESIDENT";
     const  ROLE_DIRECTORATE= "ROLE_DIRECTORATE";
     const  ROLE_COLLEGECOORDINATOR= "ROLE_COLLEGECOORDINATOR";
     const  ROLE_WORK_UNIT= "ROLE_WORKUNIT";
     const  ROLE_REVIEWER= "ROLE_REVIEWER";
     const  ROLE_BOARD_MEMBER= "ROLE_BOARD_MEMBER";
     
     const  SUBMISSION_STATUS_DECLINED= 1;
     const  SUBMISSION_STATUS_ACCEPTED_WITH_MAJOR_REVISION= 2;
     const  SUBMISSION_STATUS_ACCEPTED_WITH_MINOR_REVISION= 3;
     const  SUBMISSION_STATUS_ACCEPTED= 4;
     const  SUBMISSION_STATUS_CLOSED= 5;
     const  SUBMISSION_STATUS_ABORTED= 6;


    //  research types
    const RESEARCH_TYPE_MEGA=1;
    const RESEARCH_TYPE_COMMUNITY_SERVICE=2;
    const RESEARCH_TYPE_TECHNOLOGY_TRANSFER=3;
    const RESEARCH_TYPE_FEMALE_GRANT=4;
    const RESEARCH_TYPE_YOUTH_GRANT=5;
    const RESEARCH_TYPE_PG_STUDENT=6;
         

     //email keys
     const EMAIL_KEY_SUBMISSION_ACKNOWLEDGEMENT="SUBMISSION_SUCCESS";

     //general setting types
     const SETTINGS_TYPE_BOOLEAN = 1;
     const SETTINGS_TYPE_JSON = 2;
     const SETTINGS_TYPE_SINGLE = 3;

}