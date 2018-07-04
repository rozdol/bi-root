<?php
if ($what == 'sendmail'){
		  
  $out.= "<form action='?csrf=$GLOBALS[csrf]&act=save&what=sendmail' method='post' name='sendattach'>";
  $reftype=($this->html->readRQ('reftype'))*1;
		//$tablename=$this->html->readRQ('tablename');
		//$refid=($this->html->readRQ('refid'))*1;	
		$tmp=$this->html->readRQ("refid")*1;
		if ($tmp <> '0'){$sql = "$sql and refid=$tmp"; $refid=$tmp;}
    $tmp=$this->html->readRQ("id")*1;
		if ($tmp <> '0'){$sql = "$sql and id=$tmp"; $id=$tmp;}
		$tmp=$this->html->readRQ("tablename");
		if ($tmp <> ''){$sql = "$sql and tablename='$tmp'"; $tablename=$tmp;}
			
		$sql="SELECT 
		  id, name, path, filename, filetype, filesize, link, tablename, refid, descr 
		  FROM uploads
		  WHERE id>0 $sql";
		//$out.= $sql;
		if (!($cur = pg_query($sql))) {$this->html->SQL_error($sql);}
	  $out.= "<table>\n";
	  $out.= "<tr class='c'>
          <td>Name</td>
          <td>File</td>
          <td>Descr</td>
          <td>Size</td>
          <td>Link</td>
          <td>Ref</td>
          <td> </td>
        </tr>\n";
    $nbrow=0;
   while ($res = pg_fetch_array($cur)) {
    	$nbrow++;
        if ($nbrow%2 == 0) {$col_col = "a";} else {$col_col = "b";}
         if ($res[id]==$recentid){$col_col = "h";}
        $out.= "\t<tr  class='$col_col' onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='$col_col'\">\n";	
        $out.= "\t\t<td>$res[name]</td>\n";
        $out.= "\t\t<td>$res[filename]</td>\n";
        $out.= "\t\t<td>$res[descr]</td>\n";
        $out.= "\t\t<td>$res[filesize]</td>\n";
        $out.= "\t\t<td><a href='?act=details&what=uploads&opt=nowrap&id=$res[id]'><img src='".APP_URI."/assets/img/custom/download.gif'></a></td>\n";
        $out.= "\t\t<td>$res[tablename] [$res[refid]]</td>\n";
        $out.= "\t\t<td><label><input type='checkbox' name='item$res[id]' value='$res[id]' $checked /></label></td>\n";
        $out.= "\t</tr>\n";
       
    }
    $textarea="Please see the attachment.

Best regards.
";
    $out.= "<tr class='t'><td></td><td>$nbrow $what</td><td colspan=15> </td></tr></table>";

     $out.= "Send to specific address<br><textarea cols=60 rows=4 name='email' ></textarea><br>";
     $out.= "Subject<input type='text' name='subject'  value='Message from $username' size='40'><br>";
     $out.= "<textarea cols=60 rows=4 name='descr' >$textarea</textarea><br>";
  $out.= "<input type='submit'  name='act' value='save'>
  </form>";
	}
	
	
$body.=$out;
