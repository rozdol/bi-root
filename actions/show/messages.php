<?php
    //Show messages
// echo $this->html->pre_display($_POST,"_POST");
    if($sortby==''){$sortby="id desc";}

    $tmp=$this->html->readRQcsv('ids');
    if ($tmp!=''){$sql.=" and id in ($tmp)";}

    $tmp=$this->html->readRQn('type_id');
    if ($tmp>0){$sql.=" and type_id=$tmp";}

    $tmp = $this->html->readRQ("text");
    if ($tmp <> '') {
        $sql = "$sql and lower(message) like lower('%$tmp%')";
    }

    $tmp = $this->html->readRQ("attachment");
    if ($tmp <> '') {
        $sql = "$sql and lower(attachments) like lower('%$tmp%')";
    }

    $tmp = $this->html->readRQ("destination");
    if ($tmp <> '') {
        $sql = "$sql and lower(destination) like lower('%$tmp%')";
    }

    $sql1="select *";
    $sql=" from $what a where id>0 $sql";
    $sqltotal=$sql;
    $sql = "$sql order by $sortby";
    $sql2=" limit $limit offset $offset;";
    $sql=$sql1.$sql.$sql2;
    // echo $this->html->pre_display($sql,"sql");
    //$out.= $sql;
    $fields=['id','name','date','time','type','status','source','destination','subject','attachments','function','user','message'];
    //$sort= $fields;
    $out=$this->html->tablehead($what,$qry, $order, 'no_addbutton', $fields,$sort);

    if (!($cur = pg_query($sql))) {$this->html->HT_Error( pg_last_error()."<br><b>".$sql."</b>" );}
    $rows=pg_num_rows($cur);if($rows>0)$csv.=$this->data->csv($sql);
    while ($row = pg_fetch_array($cur)) {
        $i++;
        $class='';

        $class_status=($row[stage_id]==4006)?'label-important':'label-success';
        $class_type=($row[type_id]==4207)?'bold':'';
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
        $timestamp =strtotime($row[send_date]);
        $time = date('H:i:s', $timestamp);
        //$time = date('d.m.Y', $timestamp);
        //$row[attachments]="/private/data/finsola//docs/Invoice_F-21-0020.pdf,/private/data/finsola//docs/Invoice_F-21-0021.pdf";
        $attachments=explode(',',$row[attachments]);
        unset($files);
        foreach ($attachments as $attachment) {
            $file=explode('/',$attachment);
            $filename=$file[count($file)-1];
            //echo $this->html->pre_display($file,"file $filename");
            $files[]=$filename;
        }
        $filelist=implode(', ',$files);
        if($row[id]==0)$class='d';
        $out.= "<tr class='$class'>";
        $out.= $this->html->edit_rec($what,$row['id'],'ved',$i);
        $out.= "<td id='$what:$row[id]' class='cart-selectable' reference='$what'>$row[id]</td>";
        $out.= "<td onMouseover=\"showhint('$descr', this, event, '400px');\">$row[name]</td>";
        $out.= "<td>$row[date]</td>";
        $out.= "<td>$time</td>";
        $out.= "<td class='$class_type'>$type</td>";
        $out.= "<td><span class='label $class_status'>$stage</span></td>";
        $out.= "<td>$row[source]</td>";
        $out.= "<td>$row[destination]</td>";
        $out.= "<td>$row[subject]</td>";
        $out.= "<td>$filelist</td>";
        $out.= "<td>$row[function]</td>";
        $out.= "<td>$user</td>";
        $out.= "<td>$message_short</td>";
        //$out.= "<td class='n'>".$this->html->money($row[amount])."</td>";
        //$out.= $this->html->HT_editicons($what, $row[id]);
        $out.= "</tr>";
        $totals[2]+=$row[qty];
        if ($allids) $allids.=','.$what.':'.$row['id']; else $allids.=$what.':'.$row['id'];
        $this->livestatus(str_replace("\"","'",$this->html->draw_progress($i/$rows*100)));
    }
    $this->livestatus('');
    include(FW_DIR.'/helpers/end_table.php');
