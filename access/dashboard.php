<?php

if (!isset($path_to_root) || isset($_GET['path_to_root']) || isset($_POST['path_to_root']))
		die(_("Restricted access"));
	include_once($path_to_root . "/includes/ui.inc");
	include_once($path_to_root . "/includes/page/header.inc");

	$js = "<script language='JavaScript' type='text/javascript'>
function defaultCompany()
{
	document.forms[0].company_login_name.options[".$_SESSION["wa_current_user"]->company."].selected = true;
}
</script>";
	add_js_file('login.js');

	if (!isset($def_coy))
		$def_coy = 0;
	$def_theme = "default";

	$login_timeout = $_SESSION["wa_current_user"]->last_act;

	$title = $app_title." ".$version." - "._("Password reset");
	$encoding = isset($_SESSION['language']->encoding) ? $_SESSION['language']->encoding : "iso-8859-1";
	$rtl = isset($_SESSION['language']->dir) ? $_SESSION['language']->dir : "ltr";
	$onload = !$login_timeout ? "onload='defaultCompany()'" : "";

	page_header(_("Dashboard"), false, false, '');

	div_start('_page_body');
		echo '<div class="row" id="main_content">';
	    	echo '<div class="medium-12 columns">';

				//content  start here
	        	div_start('_page_body');
	        		echo "<p>Welcome to Smart Inventory System!</p>";
	        	div_end();
	        	
	        echo '</div>';
  		echo '</div>';
	div_end();
	end_page(false, true);
?>