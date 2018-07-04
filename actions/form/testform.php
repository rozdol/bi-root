<?php
if ($what == 'testform'){
		$out.="<div class='well columns form-wrap'>
		  <form class='' action='?act=register' method='post' name='login'> 
		    <h1>Form</h1>
		    <p>Example</p>
		<hr>
		<fieldset>
		    <label>Test Field</label>
		    <input type='text' name='username' value='' class='span12'	placeholder='Placeholder'/>
		
			<label>Disabled Field</label>
		    <input type='text' name='username' value='' class='span12'	placeholder='Placeholder' disabled/>

			<label>Password Field</label>
		    <input type='password' name='password' value='' class='span12'/>
		</fieldset>
		<fieldset>
			<label>Date Picker1</label>
			<input data-datepicker='datepicker' class='span4' type='text' value='' placeholder='DD.MM.YYYY'/> 
            
			<label>Date Picker2</label>
            <input data-datepicker='datepicker' class='span4' type='text' value='' placeholder='DD.MM.YYYY'/>
			<p> </p>
		     ".$this->html->form_confirmations()."
		<button type='submit' class='btn btn-primary' name='act' value='save'>Submit</button> 
		    <div class='spacer'></div>
		</fieldset>
		  </form>
		</div>";
				
	}
	
$body.=$out;
