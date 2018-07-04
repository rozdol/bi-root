<?php
if (!$access['main_admin']) {
    $this->html->error('Honey pot');
}
if ($access['main_admin']) {
    $tbl='	<table id="myTable4"> 
	<thead> 
	<tr> 
	    <th>Last Name</th> 
	    <th>First Name</th> 
	    <th>Email</th> 
	    <th>Due</th> 
	    <th>Web Site</th> 
	</tr> 
	</thead> 
	<tbody> 
	<tr> 
	    <td>Smith</td> 
	    <td>John</td> 
	    <td>jsmith@example.com</td>
	    <td>$50.00</td> 
	    <td>http://www.jsmith.com</td> 
	</tr> 
	<tr> 
	    <td>Bach</td> 
	    <td>Frank</td> 
	    <td>fbach@yahoo.com</td> 
	    <td>$50.00</td> 
	    <td>http://www.frank.com</td> 
	</tr> 
	<tr> 
	    <td>Doe</td> 
	    <td>Jason</td> 
	    <td>jdoe@hotmail.com</td> 
	    <td>$100.00</td> 
	    <td>http://www.jdoe.com</td> 
	</tr> 
	<tr> 
	    <td>Conway</td> 
	    <td>Tim</td> 
	    <td>tconway@earthlink.net</td> 
	    <td>$50.00</td> 
	    <td>http://www.timconway.com</td> 
	</tr> 
	</tbody> 
	</table>';

    $sript='
<script>
$(document).ready(function() 
    { 
        $("#myTable3").tablesorter(); 
 		$("#myTable3").tablesorter( {sortList: [[0,0], [1,0]]} ); 
    } 
);
</script>';

    echo $sriptf.$tbl;
}
$body.=$out;
