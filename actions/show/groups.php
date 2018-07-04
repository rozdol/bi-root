<?php
if (($what == 'groups')&&($access['main_admin'])) {
    $sql="SELECT 
		u.id, u.name, u.descr
		FROM groups u
		ORDER BY u.id";
    if (!($cur = pg_query($sql))) {
        $this->html->SQL_error($sql);
    }
    $out.= "<table class='table table-bordered table-striped-tr table-morecondensed tooltip-demo  table-notfull' id='sortableTable'>\n";
    $out.= "<tr class='c'>
		<td>ID</td>
	<td>Name</td>
	<td>Description</td>
	<td>M</td>
	<td>Access</td>
	<td>users</td>
	<td> </td>
	</tr>\n";
    $nbrow=0;
    while ($line = pg_fetch_array($cur)) {
        $nbrow++;
        $col_col = "";
        //$hits=DB_lookup("select count(*) from logs where userid=$line[0] or action like '%$line[1]'");
        $users=$this->utils->F_tostring($this->db->GetResults("select '<a href=\"?act=edit&table=users&id='||id||'\">'||username||'</a>' from users where id in (select userid from user_group where groupid=$line[0])"), 1);
        $fastmenu=$this->db->GetVal("select id from fastmenu where gid=$line[0]")*1;
        $out.= "\t<tr  class='$col_col' onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='$col_col'\">\n";
        $out.= "\t\t<td><a href='?act=edit&table=groups&id=$line[0]'>$line[0]</a></td>\n";
        $out.= "\t\t<td><a href='?act=details&what=usersstat&id=$line[0]'>$line[1]</a></td>\n";
        $out.= "\t\t<td>$line[2]</td>\n";
        $out.= "\t\t<td><a href='?csrf=$GLOBALS[csrf]&act=save&what=gen_fast_menu&id=$line[0]'>$fastmenu</td>\n";
        $out.= "\t\t<td><a href='?act=details&what=groupaccess&id=$line[0]&type=main'>Main</a> |                        
		<a href='?act=details&what=groupaccess&id=$line[0]&type=view'>View</a> |  
		<a href='?act=details&what=groupaccess&id=$line[0]&type=edit'>Edit</a> | 
		<a href='?act=details&what=groupaccess&id=$line[0]&type=repo'>Reports</a> |
		<a href='?act=details&what=groupaccess&id=$line[0]&active=1'>Active</a> |  
		<a href='?act=details&what=groupaccess&id=$line[0]'>All</a></td>\n";
        $out.= "\t\t<td>$users</td>\n";
        $out.= $this->html->HT_editicons($what, $line[0]);
        $out.= "\t</tr>\n";
    }
    $out.= "<tr class='t'><td> </td><td>$nbrow $what</td><td colspan='5'> </td></tr></table>";
    $out.= "\t\t<a href='?csrf=$GLOBALS[csrf]&act=save&what=gen_fast_menu_all'>Regenerate menu for all</td>\n";
}

$body.=$out;
