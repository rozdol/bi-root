<?php
if ($what == 'bindpartners'){
	
$out.= "<h1>Fix File</h1><form action='?csrf=$GLOBALS[csrf]&act=save&what=bindpartners' method='post' name='bindpartners'>
<table class='table table-bordered table-morecondensed table-notfull'>


<tr><td class='green'>Upload ID<br>will remain</td><td class='m'><input type='text' name='id' tabindex='1' size='3' maxlength='9'  value='$res[0]' onchange='itemid=this.value;ajaxFunction(\"partnerid_\",\"?csrf=$GLOBALS[csrf]&act=append&what=getvaluelinked&fromtable=partners&field=name&id=\"+itemid);'><span id='partnerid_'></span> </td></tr>
<tr><td class='red'>Partner ID secondary<br>will be deleted</td><td class='m'><input type='text' name='ids' tabindex='1' size='3' maxlength='9'  value='$res[0]' onchange='itemid=this.value;ajaxFunction(\"partnerid2_\",\"?csrf=$GLOBALS[csrf]&act=append&what=getvaluelinked&fromtable=partners&field=name&id=\"+itemid);'><span id='partnerid2_'></span> </td></tr>

  <tr><td class='m'></td><td class='m'><input type='submit' tabindex='2' name='act' value='Bind' class='btn btn-primary' id='button' onClick='document.getElementById(\"button\").innerHTML=\"Wait...\";'></td></tr>

</table>
</form>";			
}


$body.=$out;