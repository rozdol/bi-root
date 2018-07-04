<?php

$reftype=($this->html->readRQ('reftype'))*1;
$refid=($this->html->readRQ('refid'))*1;
$tablename=($this->html->readRQ('tablename'));
if ($tablename=="documents") {
    $row=$this->db->GetRow("select * from documents where id=$refid");
    $fdate=$this->dates->F_YMDate($row[datefrom]);
    $ftype=$this->db->GetVal("select name from listitems where id=$row[type]");
    //$partnersnamelist=$this->utils->F_tostring($this->db->GetResults("select substr(p.name,0,7) as name from partners p, docs2partners d where d.docid=$refid and d.partnerid=p.id"));
    $partnersnamelist=substr($partnersnamelist, 0, strlen($partnersnamelist)-1);
    $fsum=$row[amount];
    $newfname="$fdate-$ftype-$partnersnamelist-$fsum";
}
if ($act=='edit') {
        $sql="select * from uploads WHERE id='$id'";
        $res=$this->utils->escape($this->db->GetRow($sql));
        
        $descr="<label>File</label><input type='text' name='name' maxlength='255'  value='$res[name]' disabled>
  <label>Descr</label><input type='text' name='descr' size='30' maxlength='255'  value='$res[descr]'>
  <label>Tags:</label><textarea cols=60 rows=6 name='tags' >$res[tags]</textarea>
		<input type='hidden' name='id' value='$id'>
		<input type='hidden' name='update' value='$id'>";
} else {
    $id='';
    $descr="<label>File</label><input name='webcam' type='file' id='webcam' class='webcam'>
  <label>Descr</label><input type='text' name='descr' size='30' maxlength='255'>
   <label>Tags:</label><textarea cols=60 rows=6 name='tags' ></textarea>";
}
    
 $out.= " 
	<div id='stylized' class='well'>
<form action='?csrf=$GLOBALS[csrf]&act=save&what=uploads' method='post' name='uploads' enctype='multipart/form-data' > 
<h1>Upload File</h1>
   <p>Large file may take longer time</p>
  <input type='hidden' name='refid' value='$refid'>
  <input type='hidden' name='tablename' value='$tablename'>
  <input type='hidden' name='reftype' value='$reftype'>
<input type='hidden' name='newfname' value='$newfname'>
  $descr
	".$this->html->form_confirmations()."
		<button type='submit' name='act' value='save' id='button' class='btn btn-primary'  onClick='document.getElementById(\"button\").innerHTML=\"Wait...\";'>Save</button>
  <div class='spacer'></div>
</form>
</div>";



$body.=$out;
