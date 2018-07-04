<?php
if ($what == 'stretch'){
		$pid=$this->html->readRQ("id");
		$pid=str_ireplace(".html","",$pid);
		$h=400; $w=1000;
		if($pid>0){
	$sql="select partnerid, shareholder from shareholders where shareholder=$pid or partnerid=$pid order by partnerid";
	$data="";
	$initname=$this->db->GetVal("select name from partners where id=$pid");
}
		if (!($cur = pg_query($sql))) {$this->html->SQL_error($sql);}
    $rows = pg_num_rows($cur);
    while ($row = pg_fetch_array($cur)) {
       $i+=1;
       //if($i>20){break;};
       $pname=$this->db->GetVal("select name from partners where id=$row[0]");
    	$intCounter=$intCounter+1;
		  if ($intCounter>=20){$intCounter=0;}
    	$data.="$initname-$pname/?act=report&what=stretch&id=$row[0],";
    }

$dataorig=$data;
$line=$data; $data="";

for ($i=0; $i<strlen($line); $i+=1){ $character=ord(substr($line,$i,1));$c= dechex( $character ); $data.= "%$c"; }
$chart="vsinet.swf";
	   $out.= "$dataorig";
   $out.= "<div align='center' class='text'>
   <object classid='clsid:d27cdb6e-ae6d-11cf-96b8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0' width='$w' height='$h' id='vs' align='middle'>
<param name='allowScriptAccess' value='sameDomain' />
<param name='movie' value='".APP_URI."/assets/swf/$chart?str=$data' />
<param name='quality' value='high' />
<param name='bgcolor' value='#ffffff' />
<embed src='".APP_URI."/assets/swf/$chart?mode=vis&&str=$data' quality='high' bgcolor='#ffffff' width='$w' height='$h' name='vs' align='middle' allowScriptAccess='sameDomain' type='application/x-shockwave-flash' pluginspage='http://www.macromedia.com/go/getflashplayer' />
</object>
   </div>";		
	}
	
$body.=$out;
