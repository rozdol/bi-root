<?php
    //Show messages
    if($sortby==''){$sortby="id desc";}

    $tmp=$this->html->readRQcsv('ids');
    if ($tmp!=''){$sql.=" and id in ($tmp)";}

    $tmp=$this->html->readRQn('list_id');
    if ($tmp>0){$sql.=" and list_id=$tmp";}

    $sql1="select *";
    $sql=" from $what a where id>0 $sql";
    $sqltotal=$sql;
    $sql = "$sql order by $sortby";
    $sql2=" limit $limit offset $offset;";
    $sql=$sql1.$sql.$sql2;
    //$out.= $sql;
    $fields=['id','name','date','type','function','source','destination','stage','user','message'];
    //$sort= $fields;
    $out=$this->html->tablehead($what,$qry, $order, 'no_addbutton', $fields,$sort);

    if (!($cur = pg_query($sql))) {$this->html->HT_Error( pg_last_error()."<br><b>".$sql."</b>" );}
    $rows=pg_num_rows($cur);if($rows>0)$csv.=$this->data->csv($sql);
    while ($row = pg_fetch_array($cur)) {
        $i++;
        $class='';
        $type=$this->data->get_name('listitems',$row[type_id]);
        $stage=$this->data->get_name('listitems',$row[stage_id]);
        $user=$this->data->username($row[user_id]);
        //$partner=$this->data->detalize('partners',$row[partner_id]);
        $descr="$row[descr]<hr>$row[addinfo]";
        $message=$row[message];
        $text = strip_tags($message);
        $text = str_replace("&rsquo;","'", $text);
        $content = preg_replace("/&#?[a-z0-9]{2,8};/i","",$text );
        $message = join("\n", array_map("ltrim", explode("\n", $content )));
        //echo $this->html->pre_display($message,"message");
        $message_short=$this->html->shorter($message, 500, false);
        //$message_slong=$this->utf8->utf8_cutByPixel($message, 600, false);
        //$message_slong=$this->html->shorter($message, 1500, false);
        if($row[id]==0)$class='d';
        $out.= "<tr class='$class'>";
        $out.= $this->html->edit_rec($what,$row[id],'ved',$i);
        $out.= "<td id='$what:$row[id]' class='cart-selectable' reference='$what'>$row[id]</td>";
        $out.= "<td onMouseover=\"showhint('$descr', this, event, '400px');\">$row[name]</td>";
        $out.= "<td>$row[date]</td>";
        $out.= "<td>$type</td>";
        $out.= "<td>$row[function]</td>";
        $out.= "<td>$row[source]</td>";
        $out.= "<td>$row[destination]</td>";
        $out.= "<td>$stage</td>";
        $out.= "<td>$user</td>";
        $out.= "<td>$message_short</td>";
        //$out.= "<td class='n'>".$this->html->money($row[amount])."</td>";
        //$out.= $this->html->HT_editicons($what, $row[id]);
        $out.= "</tr>";
        $totals[2]+=$row[qty];
        if ($allids) $allids.=','.$what.':'.$row[id]; else $allids.=$what.':'.$row[id];
        $this->livestatus(str_replace("\"","'",$this->html->draw_progress($i/$rows*100)));
    }
    $this->livestatus('');
    include(FW_DIR.'/helpers/end_table.php');
