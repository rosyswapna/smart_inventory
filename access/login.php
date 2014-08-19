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
	// Display demo user name and password within login form if "$allow_demo_mode" is true
	if ($allow_demo_mode == true)
	{
	    $demo_text = _("Login as user: demouser and password: password");
	}
	else
	{
		$demo_text = _("Please login here");
    if (@$allow_password_reset) {
      $demo_text .= " "._("or")." <a href='$path_to_root/index.php?reset=1'>"._("request new password")."</a>";
    }
	}

	if (check_faillog())
	{
		$blocked_msg = '<span class=redfg>'._('Too many failed login attempts.<br>Please wait a while or try later.').'</span>';

	    $js .= "<script>setTimeout(function() {
	    	document.getElementsByName('SubmitUser')[0].disabled=0;
	    	document.getElementById('log_msg').innerHTML='$demo_text'}, 1000*$login_delay);</script>";
	    $demo_text = $blocked_msg;
	}
	if (!isset($def_coy))
		$def_coy = 0;
	$def_theme = "default";

	$login_timeout = $_SESSION["wa_current_user"]->last_act;

	$title = $login_timeout ? _('Authorization timeout') : $app_title." ".$version." - "._("Login");
	$encoding = isset($_SESSION['language']->encoding) ? $_SESSION['language']->encoding : "iso-8859-1";
	$rtl = isset($_SESSION['language']->dir) ? $_SESSION['language']->dir : "ltr";
	$onload = !$login_timeout ? "onload='defaultCompany()'" : "";


	//header
	page_header('',true);


	//main content start
	echo '<div class="row" id="main_content" >';
    	echo '<div class="medium-12 columns">';
      		echo '<div class="row">';
        		echo '<div class="medium-12 columns">';
				
        			//content  start here
        			div_start('_page_body');
        			echo '<h3>Login</h3>';
					echo '<fieldset>';
						echo '<div class="row" >';
							echo '<div class="medium-4 columns">
									<a target="_blank" href="$power_url"><h3>SMART INVENTORY</h3></a>
									</div>';
							echo '<div class="medium-8 columns">';

								//form
								start_form(false, false, $_SESSION['timeout']['uri'], "loginform");
									echo "<input type='hidden' id=ui_mode name='ui_mode' value='".$_SESSION["wa_current_user"]->ui_mode."' />\n";

									//message
									if ($login_timeout) { 
										echo '<div class="row">';
										echo "<center><font size=4>"._('Authorization timeout')."</font></center>";
										echo '</div>';
									}

									//login inputs
									$value = $login_timeout ? $_SESSION['wa_current_user']->loginname : ($allow_demo_mode ? "demouser":"");
									echo '<div class="medium-12 columns ">';
										start_table(false,"width='80%'");

											$value = $login_timeout ? $_SESSION['wa_current_user']->loginname : ($allow_demo_mode ? "demouser":"");

											text_row(_("User name:"), "user_name_entry_field", $value, 20, 30);

											$password = $allow_demo_mode ? "password":"";

											password_row(_("Password:"), 'password', $password);

											if ($login_timeout) {
												hidden('company_login_name', $_SESSION["wa_current_user"]->company);
											} else {
												if (isset($_SESSION['wa_current_user']->company))
													$coy =  $_SESSION['wa_current_user']->company;
												else
													$coy = $def_coy;
												if (!@$text_company_selection) {
													echo "<tr><td class='label'>"._("Company:")."</td><td><select name='company_login_name'>\n";
													for ($i = 0; $i < count($db_connections); $i++)
														echo "<option value=$i ".($i==$coy ? 'selected':'') .">" . $db_connections[$i]["name"] . "</option>";
													echo "</select>\n";
													echo "</td></tr>";
												} else {
										//			$coy = $def_coy;
													text_row(_("Company"), "company_login_nickname", "", 20, 50);
												}
												start_row();
												label_cell($demo_text, "colspan=2 align='center' id='log_msg'");
												end_row();
											}; 
											end_table(1);
											echo "<center><input type='submit' value='Sign In' name='SubmitUser'"
												.($login_timeout ? '':" onclick='set_fullmode();'").(isset($blocked_msg) ? " disabled" : '')." class='button' /></center>\n";

											foreach($_SESSION['timeout']['post'] as $p => $val) {
												// add all request variables to be resend together with login data
												if (!in_array($p, array('ui_mode', 'user_name_entry_field', 
													'password', 'SubmitUser', 'company_login_name'))) 
													echo "<input type='hidden' name='$p' value='$val'>";
											}

									echo '</div>';




								end_form(1);
							
							echo '</div>';
						echo '</div>';
					echo '</fieldset>';
      			echo '</div>';
      		echo '</div>';
      		//content ends here


      		//footer start here
      		if (isset($_SESSION['wa_current_user'])) 
				$date = Today() . " | " . Now();
			else	
				$date = date("m/d/Y") . " | " . date("h.i am");

      		echo '<footer class="row">
			        <div class="medium-12 columns"><hr>
			            <div class="row">

			              <div class="medium-6 columns">
			                  <small> '.$date.' &nbsp;&nbsp;&nbsp;&nbsp; Copyright &copy; Acube Innovations Private Limitted. All Rights Reserved.</small>
			              </div>

			              <div class="medium-6 small-12 columns">
			                  <ul class="inline-list right">

			                  </ul>
			              </div>

			            </div>
			        </div>
			      </footer>';
			//footer ends here

      	echo '</div>';
    echo '</div>';
    //main content end


?>