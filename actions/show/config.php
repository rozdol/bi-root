<?php
// if (($what == 'config')&&($access['main_admin'])){
// 				$sql="SELECT name as id, name, value from config order by name";
// 				$select_data = $this->db->GetResults($sql);
// 				$tb = new Table();
// 				$tb->Data=$select_data;
// 				$tb->Icons=array($what,$what,$what);
// 				$tb->Cell_align=array('','','','right','right');
// 				$tb->Cell_total=array('','','','1','1');
// 				$tb->Cell_count=array('',$what,'','','');
// 				$tb->Cell_link=array('','?act=show');
// 				$out.=$tb->ShowAssos();
// 				//$out.= "<a href='?act=add&what=importtrips'>Import</a> .::. <a href='?act=tools&what=export	trips'>Export</a>"; 

// 			}
			
// $body.=$out;


// Show config

if (!$access['main_admin']) $this->html->error('No access');
$sql="SELECT name as id, name, value from config order by name";
//$out.= $sql;
$fields=array('name','value',);
//$sort= $fields;
$out=$this->html->tablehead($what,$qry, $order, $addbutton, $fields,$sort);
		
if (!($cur = pg_query($sql))) {$this->html->HT_Error( pg_last_error()."<br><b>".$sql."</b>" );}
$rows=pg_num_rows($cur);if($rows>0)$csv.=$this->data->csv($sql);
while ($row = pg_fetch_array($cur)) {
	$i++;
	$class='';
	//if($row[id]==0)$class='d';
	
	$out.= "<tr class='$class'>";
	$out.= "<td>$i</td>";
	$out.= "<td onMouseover=\"showhint('$row[value]', this, event, '400px');\">$row[name]</td>";
	$row[value]=$this->utf8->utf8_cutByPixel($row[value], 400, false);
	$out.= "<td><a href=''>$row[value]</td>";
	$out.=$this->html->HT_editicons($what, $row[id]);
	$out.= "</tr>";
	$totals[2]+=$row[qty];
	if ($allids) $allids.=','.$what.':'.$row[id]; else $allids.=$what.':'.$row[id];			
	$this->livestatus(str_replace("\"","'",$this->html->draw_progress($i/$rows*100)));	
}
$this->livestatus('');
include(FW_DIR.'/helpers/end_table.php');
