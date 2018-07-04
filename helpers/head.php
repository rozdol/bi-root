<?php
$html = <<< EOF
<!DOCTYPE html>
<html lang="en">
	<head>
	<meta charset="utf-8">
	<title>{$content['header']['title']}</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">
	{$content['header']['meta']}
	{$content['header']['links']}
	{$content['header']['scripts']}
	<style type="text/css">
	    body {
	      padding-top: 0px;
	      padding-bottom: 0px;
	    }
	    .sidebar-nav {
	      padding: 9px 0;
	    }
	  </style>
	</head>
	<body>

EOF;
