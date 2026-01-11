<?php

echo "<script> 
	function CheckAll()
{
	count = document.accesslevel.elements.length;
	for (i=0; i < count; i++) 
	{
		document.accesslevel.elements[i].checked = 1;
	}
}
function UncheckAll()
{
	count = document.accesslevel.elements.length;
	for (i=0; i < count; i++) 
	{
		document.accesslevel.elements[i].checked = 0;
	}
}
function inverse()
{
	count = document.accesslevel.elements.length;
	for (i=0; i < count; i++) 
	{
		if(document.accesslevel.elements[i].checked == 1)
			{document.accesslevel.elements[i].checked = 0; }
		else {document.accesslevel.elements[i].checked = 1;}
	}
}
</script>
	";
$active=$this->html->readRQn('active');
$refid=$this->html->readRQn('refid');
$type=$this->html->readRQ("type");
$filt=$this->html->readRQ("filt");
$accessid=$this->html->readRQn("accessid");
$delimiter=";";
if ($active >0) {
    $sql = "$sql and al.access='1' ";
}
if ($active <0) {
    $sql = "$sql and al.access='0' ";
}
if ($type <> '') {
    $sql = "$sql and ai.name like '$type%' ";
}
if ($filt <> '') {
    $sql = "$sql and ai.name like '%$filt' ";
}
if ($accessid >0) {
    $sql = "$sql and ai.id=$accessid ";
}
if ($refid >0) {
    $sql = "$sql and al.groupid=$refid ";
}
$sql="SELECT ai.id, ai.name, al.access, gr.name as group, gr.id as groupid  FROM accesslevel al, accessitems ai, groups gr WHERE al.accessid=ai.id and al.groupid=gr.id $sql order by ai.name, gr.name, ai.id";
//echo $sql;

echo "<form action='?csrf=$GLOBALS[csrf]&act=save&what=accesslevel&refresh=1&print=1&forcedesign=1' method='post' name='accesslevel'>
	<input type='hidden' name='refid' value='$refid'>
	<input type='hidden' name='type' value='$type'>
	<input type='hidden' name='delimiter' value='$delimiter'>
	<input type='submit' tabindex='2' name='act' value='save'><br>
	<input name='btn' type='button' onclick='CheckAll()' value='Check All'> 
	<input name='btn' type='button' onclick='UncheckAll()' value='Uncheck All'>

	<table class='table table-bordered table-striped-tr table-morecondensed tooltip-demo  table-notfull' id='sortableTable'>\n";
echo "<tr class='c'>
	<td>Permissions</td>
	<td colspan='2'>Access</td><td>Group</td>
	</tr>\n";

if (!($cur = pg_query($sql))) {
    $this->html->SQL_error($sql);
}
$nbrow=0;
$tbl="";
while ($row = pg_fetch_array($cur)) {
    $value=$row['access'];
    $name=$row['name'];
    $groupid=$row['groupid'];
    $accid=$row['id'];
    //if ($value) {$value=0;} else {$value=1;}
    $tblnow=substr($name, strpos($name, "_")+1, strlen($name)-strpos($name, "_"));
    if ($tblnow<>$tbl) {
        $nbrow++;
        $tbl=$tblnow;
    }
    $col_col = "";
    $users=$this->utils->F_tostring($this->db->GetResults("select '<a href=\"?act=edit&table=users&id='||id||'\">'||username||'</a>' from users where id in (select userid from user_group where groupid=$row[groupid])"), 1);
    
    if (strlen(strstr($name, "user"))>0) {
        $col_col = "blackout2";
    }
    if (strlen(strstr($name, "group"))>0) {
        $col_col = "blackout2";
    }
    if (strlen(strstr($name, "admin"))>0) {
        $col_col = "blackout";
    }
    if (strlen(strstr($name, "access"))>0) {
        $col_col = "blackout2";
    }
    if (strlen(strstr($name, "config"))>0) {
        $col_col = "blackout2";
    }
    if ($row[2]) {
        $checked='checked';
    } else {
        $checked='';
    }
    
    echo "\t<tr  class='$col_col' onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='$col_col'\">\n";
    echo "\t\t<td><a href='?act=details&what=groupaccess&accessid=$accid'>$name</a></td>\n";
    $name="$name$delimiter$groupid$delimiter$accid";
    echo "\t\t<td><label><input type='hidden' name='$name' value='0'><input type='checkbox' name='$name' value='1' $checked /></label>\n";

    // echo "\t\t<td><label><input type='text' name='$name' id='_$name' size=1 value='$value'/><input type='checkbox' name='dummy' value='1' tabindex='$j' $checked onClick='
    //          document.getElementById(\"_$name\").value=\"0\";
    //          document.getElementById(\"_$name\").value=this.value;
    //          '/></label>\n";
    echo "\t\t<td>$value</td><td>$row[group] ($users)</td>\n";
    //echo HT_editicons2($what, $line[0]);
    echo "\t</tr>\n";
}
echo "</table>
	</form>";
// Permissions
