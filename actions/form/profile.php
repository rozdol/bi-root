<?php
if ($what == 'profile') {
        $id=$userid;
                $sql="SELECT * FROM users WHERE id=$uid";
                $res=$this->utils->escape($this->db->GetRow($sql));
    $out.= "
		<div id='stylized' class='well'>
		  <form action='?csrf=$GLOBALS[csrf]&act=save&what=$what'  method='post' name='Form1' id='Form1'>
		<input type='hidden' name='id' value='$id'>
		    <h1>$action Profile</h1>
		    <p>User $res[firstname] $res[surname] ($res[username])</p>
		   	<label>Firstname
		        
		    </label>
		    <input type='text' name='firstname' id='firstname' value='$res[firstname]'/>

			<label>Surname
		        
		    </label>
		    <input type='text' name='surname' id='surname' value='$res[surname]'/>

		    <label>Language</label>
		    <input type='text' name='lang' id='lang' value='$res[lang]'/>
		
			<label>New Password
		       
		    </label>
		    <input type='password' name='password' id='password' value=''/>

			<label>Verify
		        
		    </label>
		    <input type='password' name='password2' id='password2' value=''/>

			<label>E-mail
		        
		    </label>
		    <input type='text' name='email' id='email' value='$res[email]'/>
		
			<label>Mobile No
		        
		    </label>
		    <input type='text' name='mobile' id='mobile' value='$res[mobile]'/>
		
			
		
			<label>Rows per page
		        
		    </label>
		    <input type='text' name='rows' id='rows' value='$res[rows]'/>
		
			<label>Max. Description
		        
		    </label>
		    <input type='text' name='maxdescr' id='maxdescr' value='$res[maxdescr]'/>
		
			
	<div class='spacer'></div>
			
		    ".$this->html->form_confirmations()."
				<button type='submit' name='act' value='save' id='button' class='btn btn-primary'  onClick='document.getElementById(\"button\").innerHTML=\"Wait...\";'>Save</button>
		   
		  </form>
		</div>
		";
}
        
$body.=$out;
