<?php
//Show tableaccess


if ($sortby == '') {
	$sortby = "id desc";
}
$tmp = ($this->html->readRQn('refid') * 1);
if ($tmp > 0) {
	$sql = "$sql and refid=$tmp";
}
$tmp = ($this->html->readRQn('userid') * 1);
if ($tmp > 0) {
	$sql = "$sql and userid=$tmp";
}

$tmp = ($this->html->readRQ('tablename'));
if ($tmp != '') {
	$sql = "$sql and tablename='$tmp'";
}

$sql1 = "select *";
$sql = " from $what a where id>0 $sql";
$sqltotal = $sql;
$sql = "$sql order by $sortby";
$sql2 = " limit $limit offset $offset;";
$sql = $sql1 . $sql . $sql2;
//$out.= $sql;
$fields = array('time', 'user', 'table', 'object', 'descr', 'ip');
//$sort= $fields;
$out = $this->html->tablehead($what, $qry, $order, $addbutton, $fields, $sort);

if (!($cur = pg_query($sql))) {
	$this->html->SQL_error($sql);
}
$rows = pg_num_rows($cur);
if ($rows > 0) $csv .= $this->data->csv($sql);
while ($row = pg_fetch_array($cur)) {
	$i++;
	$row['time'] = substr($row['time'], 0, 19);
	$user = $this->data->username($row['userid']);
	$object = $this->data->detalize($row['tablename'], $row['refid']);
	$class = '';

	//$type=$this->data->get_name('listitems',$row[type]);
	if ($row['id'] == 0) $class = 'd';
	$out .= "<tr class='$class'>";
	$out .= "<td>$i</td>";
	$out .= "<td id='$what:$row[id]' class='cart-selectable' reference='$what'>$row[time]</td>";
	$out .= "<td onMouseover=\"showhint('$row[addinfo]', this, event, '400px');\">$user</td>";
	$out .= "<td>$row[tablename]</td>";

	$out .= "<td>$object</td>";
	$out .= "<td>$row[descr]</td>";

	$out .= $this->html->HT_editicons($what, $row['id']);
	$out .= "</tr>";
	$totals[2] = (int) $totals[2] + (int) $row['qty'];
	if ($allids) $allids .= ',' . $what . ':' . $row['id'];
	else $allids .= $what . ':' . $row['id'];
	$this->livestatus(str_replace("\"", "'", $this->html->draw_progress($i / $rows * 100)));
}
$this->livestatus('');
include(FW_DIR . '/helpers/end_table.php');
