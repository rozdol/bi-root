<?php
if ($what == 'dbchanges') {
            $res=$this->db->GetRow("select * from $what where id=$id");
            $date=$this->dates->F_date($this->html->readRQ('date'), 1);
            $link=$this->data->detalize($res[tablename], $res[ref_id]);
                $user=$this->db->GetVal("select u.firstname||' '||u.surname as user from users u where id=$res[user_id]");
            $out.= "<h1>$res[name]</h1>
	";
    //$res[changes]='ok';
    //$res[before]=json_decode($res[before],TRUE);
    //$res[after]=json_decode($res[after],TRUE);
    $res[changes]=json_decode($res[changes], true);
    foreach ($res[changes] as $key => $val) {
        $changes.="$key=>$val<br>";
    }
    $changes=$this->html->array_display2D($res[changes]);
    
    
            $out.= "<table class='table table-morecondensed table-notfull'>";
    $out.="<tr><td class='mr'><b>Id: </b></td><td class='mt'>$res[id]</td></tr>";
    $out.="<tr><td class='mr'><b>Date: </b></td><td class='mt'>$res[date]</td></tr>";
    $out.="<tr><td class='mr'><b>Tablename: </b></td><td class='mt'>$res[tablename]</td></tr>";
    $out.="<tr><td class='mr'><b>Ref id: </b></td><td class='mt'>$res[ref_id] ($link)</td></tr>";
    $out.="<tr><td class='mr'><b>User id: </b></td><td class='mt'>$res[user_id] ($user)</td></tr>";
    //$out.="<tr><td class='mr'><b>Before: </b></td><td class='mt'>".$this->html->pre_display($res[before])."</td></tr>";
    //$out.="<tr><td class='mr'><b>After: </b></td><td class='mt'>".$this->html->pre_display($res[after])."</td></tr>";
    //$out.="<tr><td class='mr'><b>changes: </b></td><td class='mt'>".$this->html->pre_display($res[changes])."</td></tr>";
    $out.="<tr><td class='mr'><b>changes: </b></td><td class='mt'>$changes</td></tr>";
    $out.="<tr><td class='mr'><b>Action: </b></td><td class='mt'>$res[action]</td></tr>";
    $out.="<tr><td class='mr'><b>Descr: </b></td><td class='mt'>$res[descr]</td></tr>";
    $out.="</table>";
    if ($res[descr]) {
        $out.= "Description:<br><pre>$res[descr]</pre>";
    }
    $out.=$this->data->details_bar($what, $id);
        $out.="<div class='alert alert-info'>$fav :: <a href='?act=edit&table=$what&id=$id'><img src='".ASSETS_URI."/assets/img/custom/edit.png'> Edit </a> :: <a href='?act=edit&table=notify&refid=$id&tablename=$what'><img src='".ASSETS_URI."/assets/img/custom/MailSend.png'> Notify </a>$isnotified</div>";

        ;
}
    
    
$body.=$out;
