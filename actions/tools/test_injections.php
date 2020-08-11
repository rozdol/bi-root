<?php
if (!$access['main_admin'])$this->html->error('Honey pot');
	$out="
			<label>$name_field</label>
			<textarea class='span11' name='name' id='teststring'></textarea><br>

			<span id='chk_name' onclick='
			tryname=document.getElementById(\"teststring\").value;
			$(\"#checkresult\").html(\"<img src=".ASSETS_URI."/assets/img/loadingsmall.gif>\");

			$.ajaxq (\"queue1\", {
			    url: \" ?csrf=$GLOBALS[csrf]&act=append&what=chk_injection&value=\"+tryname,
			    cache: false,
			    success: function(html)
			    {
			        $(\"#checkresult\").html(html);
			    }
			});
			' 
			class='icon-search tooltip-test' data-original-title='Check id match'></span>


		<br>
		<span id='checkresult'></span>
	";
	echo $out;

