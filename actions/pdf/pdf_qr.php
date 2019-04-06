<?php

global $uid, $gid;
$left = 71;
$leftmost = 20;
$right = 530;
$rightmost = 575;
$fontsize = 11;
$linefontsize = 9;
$pagewidth = 72;
$pageheight = 72;

$acttag=$this->html->readRQ('acttag');
$whattag=$this->html->readRQ('whattag');
$id=$this->html->readRQn('id');

/* This is where font/image/PDF input files live. Adjust as necessary. */
$searchpath =  DATA_DIR;

//$imagefile = $searchpath."invsign.gif";
$months = array( "January", "February", "March", "April", "May", "June",
"July", "August", "September", "October", "November", "December");

try {
	$p = new PDFlib();


	$p->set_parameter("errorpolicy", "return");
	$p->set_parameter("SearchPath", $searchpath);
	$p->set_parameter("hypertextencoding", "winansi");
	if ($p->begin_document("", "") == 0) {
		die("Error: " . $p->get_errmsg());
	}

	$p->set_info("Creator", "DB");
	$p->set_info("Author", "IT Dpt.");
	$p->set_info("Title", "INVOICE No $docname");

	
	$p->set_parameter("textformat", "utf8");  

	/* Establish coordinates with the origin in the upper left corner. */
	$p->begin_page_ext($pagewidth, $pageheight, "topdown");

	$table=$whattag;
	
	if($table=='d')$table='documents';
	if($table=='i')$table='invoices';
	if($table=='p')$table='partners';
	$name=$this->data->get_name($table,$id);
	
	$fontdir=CLASSES_DIR.'/fonts';
	$p->set_parameter("textformat", "utf8");  
	$p->set_parameter("FontOutline", "ArialNormal=$fontdir/arial.ttf");
	
	$regularfont = $p->load_font("ArialNormal", "unicode", "");if ($regularfont == 0) {die("Error: " . $p->get_errmsg());}
	
	
	$optlist = sprintf("%s font %d ", "fontsize 6", $regularfont);
	
	

	/////QR
	include(CLASSES_DIR.'/BarcodeQR.php');
	$qr = new BarcodeQR();
	$qr->url("?act=$acttag&what=$whattag&id=$id");
	//$qr->draw();
	$file=DATA_DIR."/docs/".uniqid().".png";
	$qr->draw(300, $file);
	if(file_exists($file)){
		$imagefile = $file;
		$image = $p->load_image("auto", $imagefile, "");
		if (!$image) {
			die("Error: " . $p->get_errmsg());
		}

		$p->fit_image($image, 36, 42, "scale {.13}");
		
		$p->fit_image($image, 1, 73, "scale {.13}");
		
		$p->close_image($image);

		unlink($file);
	}
	////END QR
	$name="$table\n$name\nID:$id";
	$textflow = $p->create_textflow($name, $optlist); if ($textflow == 0){ die("Error: " . $p->get_errmsg());}
	$p->fit_textflow($textflow, 4, 4, 38, 38, "fitmethod=auto");
	$p->delete_textflow($textflow);
	
	$textflow = $p->create_textflow($name, $optlist); if ($textflow == 0){ die("Error: " . $p->get_errmsg());}
	$p->fit_textflow($textflow, 42, 38, $pagewidth-4, $pageheight-4, "fitmethod=auto orientate west");
	$p->delete_textflow($textflow);


	$p->end_page_ext("");
	//End PDF===============================================================
	$p->end_document("");


	$buf = $p->get_buffer();
	$len = strlen($buf);

	$disposition="inline";
	if($save<>""){$disposition="attachment";}
	header("Content-type: application/pdf");
	header("Content-Length: $len");
	header("Content-Disposition: $disposition; filename=QR.pdf");
	print $buf;    



}
catch (PDFlibException $e) {
	die("PDFlib exception occurred while creating PDF:\n" .
		"[" . $e->get_errnum() . "] " . $e->get_apiname() . ": " .
		$e->get_errmsg() . "\n");
}
catch (Exception $e) {
	die($e);
}

$p = 0;
?>
