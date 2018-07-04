<?php
if (($what == 'usersdet')&&($access['main_admin'])) {
    if ($sortby=='') {
        $sortby="date desc";
    }
                $tmp=$this->dates->F_date($this->html->readRQ('df'));
    if ($tmp <> '') {
        $sql = "$sql and date>='$tmp'";
        $titlestr.=" from ".$tmp;
    }
                $tmp=$this->dates->F_date($this->html->readRQ('dt'));
    if ($tmp <> '') {
        $sql = "$sql and date<='$tmp'";
        $titlestr.=" to ".$tmp;
    }
                $tmp=($this->html->readRQ('ip'));
    if ($tmp <> '') {
        $sql = "$sql and  ip ~* '$tmp'";
        $titlestr.=" where IP contains '".$tmp."'";
    }
                $tmp=($this->html->readRQ('action'));
    if ($tmp <> '') {
        $sql = "$sql and  lower(action) like lower('%$tmp%')";
        $titlestr.=" where Action contains '".$tmp."'";
    }

    if ($refid <> '') {
        $sql = "$sql and  userid=$refid";
    }

                $sqlfromwhere="from logs where id>0 $sql";
                $sql="SELECT id,ip, date,action $sqlfromwhere ORDER BY $sortby  limit $limit offset $offset";

                $select_data = $this->db->GetResults($sql);
                $tb = new Table();
                $tb->Data=$select_data;
                $tb->Icons=array($what,$what,$what);
                $tb->Cell_align=array('','','','','');
                $tb->Cell_total=array('','','','1','');
                $tb->Cell_count=array('',$what,'','','');
                $tb->Cell_link=array('','');
                $tb->Header_link=array('',"?$qry&sortby=ip$order","?$qry&sortby=date$order","?$qry&sortby=action$order");
                $out.=$tb->ShowAssos();
                $totalrows=$this->db->GetVal("select count(*) ".$sqlfromwhere);
                //$totaldays=$this->db->GetVar("select sum(daysstay) ".$sqlfromwhere);
    if ($dynamic>0) {
        $nav=$this->html->HT_ajaxpager($totalrows, $orgqry, "$titleorig.");
    } else {
        $nav=$this->html->HT_pager($totalrows, $orgqry);
    }

    if ($nopager=='') {
        $out.= "$nav";
    }
}

            
$body.=$out;
