<?php
$html = <<< EOF
<div style='width: 350px;'>
	<form class="form-vertical well" action="?act=login&what=user" method="post" name="login"> 
	<h1>Please sign in</h1> 
<hr>
<br>
<div class="controls">
	<div class="input-prepend">
	<span class="add-on"><i class="icon-user"></i></span>
<input type="text" class="form-control" placeholder="User Name" name="username" id="username">  
	</div>
	<br>
<label> </label>  
<div class="input-prepend">
	<span class="add-on"><i class="icon-lock"></i></span>
<input type="password" class="form-control" placeholder="Password" name="password" id="password">  
	</div>
<br>
{$content_html}
<button type="submit" class="btn btn-primary btn-large">Sign in</button>
</br></br>
</div>
<small style="color: #aaaaaa;font-size: 8px;line-height: 9px;">{$info}<br></small>
</form>
</div>

EOF;
