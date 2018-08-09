<?php
if ($what == 'profile'){
		$id=$uid;
		$res=$this->db->GetRow("select * from employees where userid=$id");
		$id=$res[id]*1;
		if($id==0){$out.= "Your data is not inserted yet. <a href='?act=edit&table=$what&id=$id'><img src='".ASSETS_URI."/assets/img/custom/edit.png'> Edit User</a>";exit;}
		$typename=$this->db->GetVal("select name from listitems where id=$res[type]");
		$out.="<h1>$res[name] $res[surname] ($res[occupation])</h1>\n";
		$out.= "Tel: <b>$res[tel]</b><br>";
		$out.= "email: <b><a href='mailto:$res[email]'>$res[email]</a></b><br>";
		$out.= "Resposibilities:<pre>$res[responsibilities]</pre><br>";
		$out.= "Description:<pre>$res[descr]</pre><br>";
		$out.= "Addinfo:<pre>$res[addinfo]</pre><br>";		
		$out.= "<div class='alert alert-info'><a href='?act=edit&table=$what&id=$id'><img src='".ASSETS_URI."/assets/img/custom/edit.png'> Edit User</a> .::. <a href='?act=edit&table=employees&id=$id'><img src='".ASSETS_URI."/assets/img/custom/edit.png'> Edit Employee</a></div>";

		$out.= "<hr>";
		$h=round(400);
		$w=round(1000);
	 
	  $strDataURL = "?act=graphdata&what=employeetaskchart&opt=nowrap&next=-1&monthespane=12&id=$res[userid]";
	  $strDataURL = urlencode($strDataURL);
	  $chart="Gantt.swf";
   $out.= "<div align='left' class='text'><OBJECT classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0' WIDTH=$w HEIGHT=$h id='FusionCharts' ALIGN=''>
     <PARAM NAME='FlashVars' value='&dataURL=$strDataURL'>
     <PARAM NAME=movie VALUE='".ASSETS_URI."/assets/swf/$chart?chartWidth=$w&chartHeight=$h'>
     <PARAM NAME=quality VALUE=high>
     <PARAM NAME=bgcolor VALUE=#FFFFFF>
     <EMBED src='".ASSETS_URI."/assets/swf/$chart?chartWidth=$w&chartHeight=$h' FlashVars='&dataURL=$strDataURL' quality=high bgcolor=#FFFFFF WIDTH=$w HEIGHT=$h NAME='FusionCharts' ALIGN='' TYPE='application/x-shockwave-flash' PLUGINSPAGE='http://www.macromedia.com/go/getflashplayer'></EMBED>
   </OBJECT></div>";
		$_POST[type]=1658;
		$_POST[executor]=$uid;
		$_POST[complete]="f";
		$_POST[title]="Current Internal Orders";
		$out.=$this->show('documents');
		unset($_POST);
		$_POST[tablename]=$what;
		$_POST[refid]=$id;
		//$_POST[title]="Tasks";
		//$out.=$this->show('emptasks');
		$_POST[title]="";
		$out.=$this->show('marks');
		$out.=$this->show('remunerations');
		$out.=$this->show('vacations');
		$_POST[reffinfo]="&tablename=$what&refid=$id";
		//$out.=$this->show('schedules');
		//if($gui<=4)$out.=$this->show('comments');
	}
	
	
	
$body.=$out;
