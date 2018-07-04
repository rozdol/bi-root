<style media="screen" type="text/css">

.spaceman{
    margin-top:10px;
}
h1 {
    width:100%;
    height:60px;
    background-color:black;
    color:white;
    text-align:center;
    line-height:60px;
}
input {
    display:inline-block;
    height:40px;
    width:40px;
    min-width:20px;
    outline:none;
    border:none;
    vertical-align:middle;
}

.m{ display:flex; flex-wrap:nowrap;}
.menu {
    flex-shrink:0;
    text-align:center;
    padding:15px;
    background-color:rgb(204, 241, 170);
    min-height:400px;
    min-width:110px;
    display:inline-block;
}
*{-webkit-user-select:none;}
.mli {
    text-align:center;
    cursor:pointer;
    margin-top:5px;
    line-height:40px;
    list-style:none;
    border-radius:20px;
    height:40px;
    width:110px;
}
.menu li:hover { box-shadow: inset 0 0 10px black; cursor:move; } 
.color0 {background-color: aqua;}
.color1 {background-color: cadetblue;}
.color2 {background-color: darkgoldenrod;}
.color3 {background-color: darkorange;}
.color4 {background-color: honeydew;}


.con {
    cursor:default;
    -webkit-user-select:none;
    flex-grow:1;
    background-color:gray;
    padding:20px;
    color:white;
}
.con span {
    color:black;
    background-color:white;
    border:solid 1px;
    height:50px;
    
    display:inline-block;
    vertical-align:middle;
    text-align:center;
    
}
.i1 input {min-width:65px;}
.i1 {min-width:100px;}
.i2,.i3 {min-width:50px;}
.special {
    display:inline-block;
}
.drop-hover { background-color:pink;}
* {padding:0;
margin:0;}

</style>
<script src="https://code.jquery.com/jquery-2.1.4.min.js" type="text/javascript"></script>
<script src="https://code.jquery.com/ui/1.11.3/jquery-ui.min.js" type="text/javascript"></script>
<h1> Heading </h1>

<div class='m'>
	<div class='menu'>
		<ul>
			<li class='color0 mli'>Age</li>
			<li class='color1 mli'>Gender</li>
			<li class='color2 mli'>City</li>
			<li class='color3 mli'>Status</li>
			<li class='color4 mli'>Education</li>
		</ul>
	</div>
	<div class='con'>
		<div class='spaceman'>
			If ( <span class='i1'>
				<input type="text" name="condition">
				<span style="display:none"></span>
			</span>) :	
			<span class='i2'>
				<input type="text" name="val1">
				<span style="display:none"></span>
			</span> ;	<span class='i3'>
				<input type="text" name="val2">
				<span style="display:none"></span>
			</span> )
		</div> 
		<div class='spaceman'>
			If ( <span class='i1'>
				<input type="text" name="condition">
				<span style="display:none"></span>
			</span>) :	
			<span class='i2'>
				<input type="text" name="val1">
				<span style="display:none"></span>
			</span> ;	<span class='i3'>
				<input type="text" name="val2">
				<span style="display:none"></span>
			</span> )
		</div> 

		<div class='spaceman'>
			If ( <span class='i1'>
				<input type="text" name="condition">
				<span style="display:none"></span>
			</span>) :	
			<span class='i2'>
				<input type="text" name="val1">
				<span style="display:none"></span>
			</span> ;	<span class='i3'>
				<input type="text" name="val2">
				<span style="display:none"></span>
			</span> )
		</div> 
	</div>

</div>

<script>
$('.menu li').on('mousedown', function (e) {
    $(this).draggable({
        helper: "clone"
    }).css({
        opacity: '.7'
    });

});
var li={},i=0;
        
function enable(x) {
    x.droppable({
        hoverClass: "drop-hover",
        drop: function (e, ui) {
            $(ui.draggable).clone().prependTo(this).draggable().addClass('special').data('i',$(this).data('i'));
            $(this).droppable('destroy')
        }
    });
};
$('.i1').each(function(u,n){$(this).data('i',i);li[i]=$(this);i++;});
$('.i1').each(function(u,i){enable($(this));});
$('.m').droppable({
    accept: ".special",
    drop: function (e, ui) {
        var x = $(ui.draggable).data();
        $(ui.draggable).remove();
         enable(li[x.i]);
    }
});


$('input[type="text"]').keypress(function (e) {
    if (e.which !== 0 && e.charCode !== 0) { // only characters
        var c = String.fromCharCode(e.keyCode | e.charCode);
        $span = $(this).siblings('span').first();
        $span.text($(this).val() + c); // the hidden span takes 
        // the value of the input
        $inputSize = $span.width();
        $(this).css("width", $inputSize); // apply width of the span to the input
    }
});
</script>

<?php
if ($access['main_admin']){


echo $sriptf.$tbl;
}
$body.=$out;