<?php
//Details messages
    $res=$this->db->GetRow("select * from $what where id=$id");
    $partner=$this->data->detalize('partners', $res[partner_id]);
    $date=$this->html->readRQd('date',1);
    $out.= "<h1>$res[name]</h1>";
    $out.=$this->data->details_bar($what,$id);

    $out.= "<table class='table table-morecondensed table-notfull'>";$out.="<tr><td class='mr'><b>Id: </b></td><td class='mt'>$res[id]</td></tr>";
$out.="<tr><td class='mr'><b>Name: </b></td><td class='mt'>$res[name]</td></tr>";
$out.="<tr><td class='mr'><b>Ref name: </b></td><td class='mt'>$res[ref_name]</td></tr>";
$out.="<tr><td class='mr'><b>Ref id: </b></td><td class='mt'>$res[ref_id]</td></tr>";
$out.="<tr><td class='mr'><b>Type id: </b></td><td class='mt'>$res[type_id]</td></tr>";
$out.="<tr><td class='mr'><b>Stage id: </b></td><td class='mt'>$res[stage_id]</td></tr>";
$out.="<tr><td class='mr'><b>Date: </b></td><td class='mt'>$res[date]</td></tr>";
$out.="<tr><td class='mr'><b>Send date: </b></td><td class='mt'>$res[send_date]</td></tr>";
$out.="<tr><td class='mr'><b>User id: </b></td><td class='mt'>$res[user_id]</td></tr>";
$out.="<tr><td class='mr'><b>Descr: </b></td><td class='mt'>$res[descr]</td></tr>";
$out.="<tr><td class='mr'><b>Addinfo: </b></td><td class='mt'>$res[addinfo]</td></tr>";
$out.="<tr><td class='mr'><b>Message: </b></td><td class='mt'>$res[message]</td></tr>";
$out.="<tr><td class='mr'><b>Data json: </b></td><td class='mt'>$res[data_json]</td></tr>";
$out.="</table>";

    if($res[descr])$out.= "Description:<br><pre>$res[descr]</pre>";

    $dname=$this->data->docs2obj($id,$what);
    $out.="<b>Documents:</b> $dname<br>";
    $out.=$this->show_docs2obj($id, $what);

    $_POST[tablename]=$what;
    $_POST[refid]=$id;
    $_POST[reffinfo]="&tablename=$what&refid=$id";
    $out.=$this->show('schedules');
    $out.=$this->show('comments');
    $out.=$this->report('posts');
    $out.=$this->report('db_changes');
    $body.=$out;
