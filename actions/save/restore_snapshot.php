<?php
$id=$this->html->readRQn('id');
$db_data=$this->data->get_row('dbchanges',$id);
echo $this->html->pre_display($db_data); 
$vals=json_decode($db_data[after],true);
unset($vals['id']);
echo $this->html->pre_display($vals);
$this->db->update_db($db_data[tablename],$db_data[ref_id],$vals);
