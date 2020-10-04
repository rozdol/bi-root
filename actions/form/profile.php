<?php
if ($what == 'profile') {
        $id=$userid;
                $sql="SELECT * FROM users WHERE id=$uid";
                $res=$this->utils->escape($this->db->GetRow($sql));

    $list=array(
    			'English'=>'en',
    			'Русский'=>'ru',
    			'Ελληνικά'=>'el',
    			'中文'=>'zh-CN',
    			'Deutsche'=>'de'
    		);
    $lang=$this->html->dropdown_list_array("Language", "lang", $list,$res[lang]);

    $edit_link="?act=form&what=file_json&where=LANG&plain=&filename=".LANGUAGE.".json";
    $text=\util::l('edit')." ".\util::l('transalation');

    $form_opt['well_class']="span11 columns form-wrap";
    $out.=$this->html->form_start($what,$id,'',$form_opt);
    $out.=$this->html->form_hidden('id',$id);
    $out.="<hr>
    <p>User $res[firstname] $res[surname] ($res[username])</p>";

    $out.=$this->html->form_text('firstname',$res[firstname],'firstname','',0,'span12');
    $out.=$this->html->form_text('surname',$res[surname],'surname','',0,'span12');
    $out.="<a href='$edit_link'>$text</a>";
    $out.=$lang;
    $out.=$this->html->form_password('password','','Password','8 chars CAPS & digits','','','span12');
    $out.=$this->html->form_password('password2','','Re-type password','retype','','','span12');
    $out.=$this->html->form_text('email',$res[email],'email','',0,'span12');
    $out.=$this->html->form_text('mobile',$res[mobile],'mobile','',0,'span12');
    $out.=$this->html->form_text('rows',$res[rows],'rows','',0,'span12');
    $out.=$this->html->form_text('maxdescr',$res[maxdescr],'maxdescr','',0,'span12');

    $out.=$this->html->form_confirmations();
    $out.=$this->html->form_submit('Save');
    $out.=$this->html->form_end();


    $out2.= "
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
