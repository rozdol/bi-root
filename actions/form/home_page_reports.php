<?php

if($this->data->table_exists('homepages')){
	$sql="SELECT * FROM reports";
	$sorting=100;
	if (!($cur = pg_query($sql))) {$this->html->SQL_error($sql);}
	$rows=pg_num_rows($cur);$start_time=$this->utils->get_microtime();
	while ($row = pg_fetch_array($cur,NULL,PGSQL_ASSOC)){
		$i++;
		$homepage_id=$this->db->getval("SELECT id from homepages where user_id=$GLOBALS[uid] and report_id=$row[id]")*1;
		if($homepage_id==0){
			$vals=[
				'name'=>$row[name],
				'user_id'=>$GLOBALS[uid],
				'report_id'=>$row[id],
				'sorting'=>$sorting
			];
			$sorting+=100;
			$homepage_id=$this->db->insert_db('homepages', $vals);
			$homepage=$this->data->detalize('homepages',$homepage_id);
			$out.="$i $row[name] -> $homepage<br>";
		}
		$homepage=$this->data->detalize('homepages',$homepage_id);
		$this->progress($start_time, $rows, $i, "$i / $rows");
		//$name=$this->data->detalize('table',$row[id]);
		//echo "$i $row[name] -> $homepage<br>";
	}
	$form_opt['title']="Reports on my Home Page";
	//$form_opt['url']="?act=save&what=$what";

	$out.=$this->html->form_start($what, $id, '', $form_opt);
	$out.="<hr>";

	//$out.=$this->html->form_hidden('reflink', $reflink);
	//$out.=$this->html->form_hidden('id', $id);
	//$out.=$this->html->form_hidden('reference', $reference);
	//$out.=$this->html->form_hidden('refid', $refid);

	$i=0;
	$fields=array('#','id','name','sorting','a');
	//$out.=$this->html->tag("Reports on my Home Page",'foldered');
	$out.=$this->html->tablehead('',$qry, $order, $addbutton, $fields,'autosort');

	$sql="SELECT r.name, r.descr, r.link, hp.active, hp.sorting, r.id as report_id ,hp.id as homepage_id
	FROM reports r
	LEFT JOIN homepages hp ON r.id=hp.report_id
	WHERE hp.user_id=$GLOBALS[uid]
	ORDER BY hp.active DESC, hp.sorting ASC, r.name ASC";
	$inline_edit=1;
	if (!($cur = pg_query($sql))) {$this->html->SQL_error($sql);}
	$rows=pg_num_rows($cur);$start_time=$this->utils->get_microtime();
	while ($row = pg_fetch_array($cur,NULL,PGSQL_ASSOC)){
		$i++;
		$homepage=$this->data->detalize('homepages',$row[homepage_id]);
		$this->progress($start_time, $rows, $i, "$i / $rows");
		//$name=$this->data->detalize('table',$row[id]);
		// if ($inline_edit>0) {
		//     $submitdata=array(
		//         'table'=>'homepages',
		//         'field'=>'sorting',
		//         'id'=>$row[homepage_id],
		//     );

		//     $class="#homepages_sorting_1_".$row[homepage_id];
		//     $js1.=$this->utils->inline_js($class, $submitdata, 0);

		//     $submitdata=array(
		//         'table'=>'homepages',
		//         'field'=>'active',
		//         'id'=>$row[homepage_id],
		//     );

		//     $class="#homepages_active_1_".$row[homepage_id];
		//     $js1.=$this->utils->inline_js($class, $submitdata, 0);
		// }
		//$active_chk=$this->html->form_chekbox('active',$row[active],' ');

		$sorting=$this->html->form_text('sorting_'.$row[homepage_id], $row[sorting], ' ', '', 0, '', 'width: 40px;margin-bottom: 1px; padding: 0px 3px; text-align: right;');
		$active=$this->html->form_chekbox('active_'.$row[homepage_id], $row[active], ' ', '', 0, 'span12');
		$active_chk=($row[active]=='t')?'1':'0';
		//$sorting= "<span id='homepages_sorting_1_".$row[homepage_id]."'>$row[sorting]</span>";
		//$active= "<span id='homepages_active_1_".$row[homepage_id]."'>$active_chk</span>";
		$class='d';
		if($row[active]=='t'){$class='bold';}
		$link="<a href='$row[link]'>$row[name]</a>";
		$out.= "<tr class='$class'>";
		$out.= "<td>$i</td>";
		$out.= "<td id='homepages:$row[homepage_id]' class='cart-selectable' reference='homepages'>$row[homepage_id]</td>";
		$out.= "<td onMouseover=\"showhint('$row[descr]', this, event, '400px');\">$link</td>";
		$out.= "<td>$sorting</td>";
		$out.= "<td>$active</td>";
		//$out.=$this->html->HT_editicons($what, $row[id]);
		$out.= "</tr>";
	}
	$this->livestatus('');
	$out.=$this->html->tablefoot($i, $totals, $totalrecs);



	    //$out.=$this->html->form_confirmations();
	    $out.=$this->html->form_submit('Save');
	    $out.=$this->html->form_end();
}else{
	$this->html->warn('error');
}
//$form_opt['well_class']="span11 columns form-wrap";






$body.=$out;