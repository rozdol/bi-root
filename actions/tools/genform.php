<?php
if (($what == 'genform')&&($access['main_admin'])) {
    $tablename=$this->html->readRQ('tablename');
    if ($this->data->table_exists($tablename)) {
        $out= "<div class='well columns form-wrap'>
		<form class='' action='?csrf=$GLOBALS[csrf]&act=save&what=genform' method='post' name='add'>
		<input type='hidden' name='tablename' value='$tablename'>";
        
        $out.= "<h2>Show</h2><a href='?act=show&what=$tablename'>$tablename</a>";
        $out.=$this->utils->genshow($tablename);
        //gendetails($tablename);
        $out.= "<h2>Form</h2><a href='?act=add&what=$tablename'>$tablename</a>";
        $out.=$this->utils->genform($tablename);
        $out.= "<h2>Save</h2>";
        $out.=$this->utils->gensave($tablename);
        $out.= "<h2>Details</h2><a href='?act=details&what=$tablename&id=1'>$tablename</a>";
        $out.=$this->utils->gendetails($tablename);
        
        $out.="<button type='submit' class='btn btn-primary' name='act' value='save'>Submit</button>";
    } else {
        $out.=$this->html->error("Table $tablename does not exist.");
    }
        
    $body.=$out;
}
