<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="zh-tw" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="zh-tw" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="zh-tw"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
	<meta charset="utf-8">
	<!--<meta http-equiv="content-type" content="text/html; charset=UTF-8">-->
	<title>CMNST Electric System</title>
	<meta content="width=device-width, initial-scale=1" name="viewport">
	<meta content="" name="Description">
	<meta content="" name="author">
	<meta http-equiv="x-ua-compatible" content="IE=edge">
	<link href="/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<!--<link href="/assets/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">-->
	<link href="/assets/bootstrap/css/bootstrap-fileupload.css" rel="stylesheet" />
	<link href="/assets/font-awesome/css/font-awesome.css" rel="stylesheet">
	
	
	<link href="/assets/fancybox/source/jquery.fancybox.css" rel="stylesheet">
	
	
	<link href="/assets/fullcalendar/fullcalendar/bootstrap-fullcalendar.css" rel="stylesheet">
	<link href="/assets/jqvmap/jqvmap/jqvmap.css" media="screen" rel="stylesheet" type="text/css">
	
	
	<link rel="stylesheet" type="text/css" href="/assets/gritter/css/jquery.gritter.css" />
	<link rel="stylesheet" type="text/css" href="/assets/uniform/css/uniform.default.css">
	<!--<link rel="stylesheet" type="text/css" href="/assets/chosen-bootstrap/chosen/chosen.css" />-->
	<link rel="stylesheet" type="text/css" href="/assets/bootstrap-chosen-1.1.0/chosen.css" />
	<link rel="stylesheet" type="text/css" href="/assets/jquery-tags-input/jquery.tagsinput.css" />    
	<link rel="stylesheet" type="text/css" href="/assets/clockface/css/clockface.css" />
	<link rel="stylesheet" type="text/css" href="/assets/bootstrap-wysihtml5/bootstrap-wysihtml5.css" />
	<link rel="stylesheet" type="text/css" href="/assets/bootstrap-datepicker/css/datepicker.css" />
	<link rel="stylesheet" type="text/css" href="/assets/bootstrap-timepicker/compiled/timepicker.css" />
	<link rel="stylesheet" type="text/css" href="/assets/bootstrap-colorpicker/css/colorpicker.css" />
	<link rel="stylesheet" href="/assets/bootstrap-toggle-buttons/static/stylesheets/bootstrap-toggle-buttons.css" />
	<link rel="stylesheet" href="/assets/data-tables/DT_bootstrap.css" />
	<link rel="stylesheet" href="/assets/FixedHeader-2.1.0/css/dataTables.fixedHeader.min.css" />
	<link rel="stylesheet" href="/assets/TableTools-2.2.0/css/dataTables.tableTools.min.css" />
	
	<link rel="stylesheet" type="text/css" href="/assets/bootstrap-daterangepicker/daterangepicker.css" />
	
	<!-- jquery slider -->
	<link rel="stylesheet" href="/assets/jslider/css/jslider.css" type="text/css">
	<link rel="stylesheet" href="/assets/jslider/css/jslider.blue.css" type="text/css">
	<link rel="stylesheet" href="/assets/jslider/css/jslider.plastic.css" type="text/css">
	<link rel="stylesheet" href="/assets/jslider/css/jslider.round.css" type="text/css">
	<link rel="stylesheet" href="/assets/jslider/css/jslider.round.plastic.css" type="text/css">
	<!-- end -->
	
<!--<meta http-equiv="Content-Type" content="text/html; charset=utf-8">-->
	<link href="/css/style.css" rel="stylesheet">
	<link href="/css/style_responsive.css" rel="stylesheet">
	<link href="/css/style_default.css" rel="stylesheet" id="style_color">
</head>

<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="">
	<script src="/js/jquery-1.8.3.min.js"></script>
  <!-- BEGIN HEADER -->
	<div id="header" class="navbar navbar-inverse">
	<!-- BEGIN LOGO -->
		<a class="" href="<?=site_url();?>/<?=$this->session->userdata('status');?>/main">
		    <img src="<?=base_url();?>/img/cmnst-logo.png" alt="">
		</a>
	</div>
  <!-- END HEADER -->
  <!-- BEGIN CONTAINER -->
  <div id="container" class="row-fluid">

