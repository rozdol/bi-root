<?php
//tools delete_user
if ($access['main_admin']){
$new_id=8;
$old_id=6;
$sql="
update logs set userid=$new_id where userid=$old_id;
update favorites set userid=$new_id where userid=$old_id;
update tableaccess set userid=$new_id where userid=$old_id;
update uploads set userid=$new_id where userid=$old_id;
update useralerts set userid=$new_id where userid=$old_id;
update useralerts set fromuserid=$new_id where fromuserid=$old_id;
update user_group set userid=$new_id where userid=$old_id;
update docact2users set userid=$new_id where userid=$old_id;
update schedules set userid=$new_id where userid=$old_id;
update warnings set userid=$new_id where userid=$old_id;
update comments set userid=$new_id where userid=$old_id;
update docact2users set userid=$new_id where userid=$old_id;
update documents set creator=$new_id where creator=$old_id;
update documents set executor=$new_id where executor=$old_id;
update events set creator=$new_id where creator=$old_id;
update events set executor=$new_id where executor=$old_id;
update documentactions set creator=$new_id where creator=$old_id;
update documentactions set executor=$new_id where executor=$old_id;
update clientrequests set receivedby=$new_id where receivedby=$old_id;
update clientrequests set approvedby=$new_id where approvedby=$old_id;
update clientrequests set confirmedby=$new_id where confirmedby=$old_id;
update assesments set createdby=$new_id where createdby=$old_id;
update assesments set approvedby=$new_id where approvedby=$old_id;
update transactions set confirmedby=$new_id where confirmedby=$old_id;
update transactions set createdby=$new_id where createdby=$old_id;
";
echo $this->db->getVal($sql);
//echo $this->db->getVal("delete from users where id=$old_id");
}
/*
--select count(*) from logs where userid=6;
--select count(*) from favorites where userid=6;
--select count(*) from tableaccess where userid=6;
--select count(*) from uploads where userid=6;
--select count(*) from useralerts where userid=6;
--select count(*) from useralerts where fromuserid=6;
--select count(*) from user_group where userid=6;
--select count(*) from docact2users where userid=6;
--select count(*) from schedules where userid=6;
--select count(*) from warnings where userid=6;
--select count(*) from comments where userid=6;
--select count(*) from docact2users where userid=6;
--select count(*) from documents where creator=6;
--select count(*) from documents where executor=6;
--select count(*) from events where creator=6;
--select count(*) from events where executor=6;
--select count(*) from documentactions where creator=6;
--select count(*) from documentactions where executor=6;
--select count(*) from clientrequests where receivedby=6;
--select count(*) from clientrequests where approvedby=6;
--select count(*) from clientrequests where confirmedby=6;
--select count(*) from assesments where createdby=6;
--select count(*) from assesments where approvedby=6;
--select count(*) from transactions where confirmedby=6;
--select count(*) from transactions where createdby=6;
*/