<?php
if ($what == 'passchange'){
		$id=$userid;
		$out.= "<form action='?csrf=$GLOBALS[csrf]&act=save&what=$what'  method='post' name='Form1' id='Form1'>
		<table class='m'>      
      <input type='hidden' name='id' value='$id'>
        <tr><td class='mr'>Old password</td><td class='m'><input type='password' name='oldpass'  size='40' maxlength='255'  value=''> </td></tr>
        <tr><td class='mr'>New password</td><td class='m'><input type='password' name='pass1'  size='40' maxlength='255'  value=''> </td></tr>
        <tr><td class='mr'>Repeat password</td><td class='m'><input type='password' name='pass2'  size='40' maxlength='255'  value=''> </td></tr>
        <tr><td class='m'></td><td class='m'><input type='submit'  name='act' value='save'></td></tr>     
    </table>
    </form>";		
	}
	
	
$body.=$out;
