<!DOCTYPE html>
<html>
	<head>
		<title><?php wp_title('|', true, 'right') ?> <?php bloginfo('name'); ?></title>
	
			
		<script type="text/javascript" src="/script.js"></script>
		<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>"/style.css />
			
		<link rel="stylesheet" href="<?php bloginfo( 'stylesheet_url' ); ?>"/mobilestyle.css
		media="all and (min-device-width : 320px)
		and (max-device-width : 480px)">
		
		<link href='http://fonts.googleapis.com/css?family=Alegreya' rel='stylesheet' type='text/css'>
	</head>
	<body>
		<div id="wrapper">
			<div class="header">
			
				<div class="sub_header">
					<h1><?php bloginfo('name'); ?></h1>
					<h3>Ful<span class="bluespan">fil your cup of tea </span>tacking needs.</h3>
					
				</div>
				 
			</div>
			<div class="style3" class="navigationmenu">
					<?php wp_nav_menu(); ?>
				</div>		    
