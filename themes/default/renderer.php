<?php
/**********************************************************************
    Copyright (C) FrontAccounting, LLC.
	Released under the terms of the GNU General Public License, GPL, 
	as published by the Free Software Foundation, either version 3 
	of the License, or (at your option) any later version.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
    See the License here <http://www.gnu.org/licenses/gpl-3.0.html>.
***********************************************************************/

	class renderer
	{
		function get_icon($category)
		{
			global  $path_to_root, $show_menu_category_icons;

			if ($show_menu_category_icons)
				$img = $category == '' ? 'right.gif' : $category.'.png';
			else	
				$img = 'right.gif';
			return "<img src='$path_to_root/themes/default/images/$img' style='vertical-align:middle;' border='0'>&nbsp;&nbsp;";
		}

		function wa_header()
		{
			page(_($help_context = "Main Menu"), false, true);
		}

		function wa_footer()
		{
			end_page(false, true);
		}


		function menu_header($title, $no_menu, $is_index)
		{
			global $path_to_root, $help_base_url, $db_connections,$app_title;

			$local_path_to_root = $path_to_root;

			echo '<nav class="top-bar" data-topbar>
				    <ul class="title-area">
				      <!-- Title Area -->
				      <li class="name">
				        <h1><a  href="'.$path_to_root.'">'.$app_title.'</a></h1>
				      </li>
				      <li class="toggle-topbar menu-icon"><a href="#"><span>menu</span></a></li>
				    </ul>
				    <section class="top-bar-section">
				      <!-- left Nav Section -->
				      <ul class="left">
						<li>
						<span style="color:#fff;"></span>
				        </li>
				 	  </ul>

				 	  <ul class="right">';

				 	if (!$no_menu)
					{
						$applications = $_SESSION['App']->applications;
						$local_path_to_root = $path_to_root;

						foreach($applications as $app)
						{
		                    if ($_SESSION["wa_current_user"]->check_application_access($app))
		                    {
		                        $acc = access_string($app->name);
		                        echo "<li class='divider'></li>";
		                        echo "<li class='has-dropdown'>
			                        <a href='#'$acc[1]>" .$acc[0] . "</a>";
			                    $this->menu_sub_header($app->id);
			                    echo "</li>";
		                    }
						}
				 	  	
				    
						$img = "<img src='$local_path_to_root/themes/default/images/login.gif' width='14' height='14' border='0' alt='"._('Logout')."'>&nbsp;&nbsp;";
						echo "<li class='divider'></li>";
				        echo "<li class='has-dropdown'>";
				        		echo "<a href='#'>" .$img . "</a>";
				        		echo "<ul class='dropdown'>";
									echo "<li><a class='shortcut' href='$local_path_to_root/access/logout.php?'>" . _("Logout") . "</a></li>";
									echo "<li><a class='shortcut' href='$path_to_root/admin/change_current_user_password.php?selected_id=" . $_SESSION["wa_current_user"]->username . "'>" . _("Change password") . "</a></li>";
									echo "<li><a class='shortcut' href='$path_to_root/admin/display_prefs.php?'>" . _("Preferences") . "</a></li>";

									
								echo "</ul>";
						echo "</li>";
					}

			    	echo '</ul>
					
					 </section>
				  </nav>';

				  
				if ($no_menu)
					echo "<br>";
				else				
					echo '<h5>'.$title.'</h5>';
				


			/*
				echo '<div class="row" id="main_content" >';
    			echo '<div class="medium-12 columns">';

    			//content
    			
    			echo '<div class="row">';
        		echo '<div class="medium-12 columns">';
				//if (isset($content)) echo $content;
				// if (isset($this->print_page)) echo $this->print_page;  

      			echo '</div>';
   				echo '</div>';
			*/
				
    				
			
		}

		function menu_sub_header($appid)
		{
			global $path_to_root;
			$local_path_to_root = $path_to_root;
			include_once("includes/session.inc");
			$app = &$_SESSION["App"];
			$app->selected_application = $appid;
			if($app->get_application($appid)){
				$selected_app = $app->get_selected_application();
				echo "<ul class='dropdown'>";
				foreach ($selected_app->modules as $module)
				{
					echo "<li class='has-dropdown'><a href='#'> ".$module->name."</a>
							<ul class='dropdown'>";
					foreach ($module->lappfunctions as $appfunction)
					{
						echo "<li>".menu_link("http://smart_inventory.local/".$appfunction->link, $appfunction->label)."</li>";
					}
					if (sizeof($module->rappfunctions) > 0)
					{
						
						foreach ($module->rappfunctions as $appfunction)
						{
							echo "<li>".menu_link("http://smart_inventory.local/".$appfunction->link, $appfunction->label)."</li>";
						}
					}
					echo "</ul>
							</li>
			              <li class='divider'></li>";
			    }
			    echo " </ul>";
		    }
		}

		function display_dashboard()
		{
			page(_($help_context = ""), false, true);

			//main content start
			echo '<div class="row" id="main_content">';
		    	echo '<div class="medium-12 columns">';

		    		echo '<div class="row">';
		    			echo "<h5>Dashboard</h5>";
		    		echo '</div>';

		    		echo '<div class="row">';
						//content  start here
		        		div_start('_page_body');
		        			echo "Welcome to Smart Inventory System!";
		        		div_end();
		        	echo '</div>';

		        echo '</div>';
      		echo '</div>';

			
			
		}

		function menu_footer($no_menu, $is_index)
		{
			global $version, $allow_demo_mode, $app_title, $power_url, 
				$power_by, $path_to_root, $Pagehelp, $Ajax;

			echo '<footer class="row">';
        	echo '<div class="medium-12 columns"><hr>';
            echo '<div class="row">';
			echo '<div class="medium-6 columns">';
            echo '<small><a target="_blank" href="'.$power_url.'" tabindex="-1">Copyright &copy; '.$power_by.'. All Rights Reserved.</a></small>';
            echo '</div>';

            echo '<div class="medium-6 small-12 columns">';
            echo '<ul class="inline-list right">';

            echo '</ul>';
            echo '</div>';
			echo '</div>';
			echo '</div>';
      		echo '</footer>';
			
		}

		/*function menu_header($title, $no_menu, $is_index)
		{
			global $path_to_root, $help_base_url, $db_connections;
			echo "<table class='callout_main' border='0' cellpadding='0' cellspacing='0'>\n";
			echo "<tr>\n";
			echo "<td colspan='2' rowspan='2'>\n";

			echo "<table class='main_page' border='0' cellpadding='0' cellspacing='0'>\n";
			echo "<tr>\n";
			echo "<td>\n";
			echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'>\n";
			echo "<tr>\n";
			echo "<td class='quick_menu'>\n"; // tabs
			if (!$no_menu)
			{
				$applications = $_SESSION['App']->applications;
				$local_path_to_root = $path_to_root;
				$sel_app = $_SESSION['sel_app'];
				echo "<table cellpadding=0 cellspacing=0 width='100%'><tr><td>";
				echo "<div class=tabs>";
				foreach($applications as $app)
				{
                    if ($_SESSION["wa_current_user"]->check_application_access($app))
                    {
                        $acc = access_string($app->name);
                        echo "<a class='".($sel_app == $app->id ? 'selected' : 'menu_tab')
                            ."' href='$local_path_to_root/index.php?application=".$app->id
                            ."'$acc[1]>" .$acc[0] . "</a>";
                    }
				}
				echo "</div>";
				echo "</td></tr></table>";

				// top status bar
				$img = "<img src='$local_path_to_root/themes/default/images/login.gif' width='14' height='14' border='0' alt='"._('Logout')."'>&nbsp;&nbsp;";
				$himg = "<img src='$local_path_to_root/themes/default/images/help.gif' width='14' height='14' border='0' alt='"._('Help')."'>&nbsp;&nbsp;";
				echo "<table class=logoutBar>";
				echo "<tr><td class=headingtext3>" . $db_connections[$_SESSION["wa_current_user"]->company]["name"] . " | " . $_SERVER['SERVER_NAME'] . " | " . $_SESSION["wa_current_user"]->name . "</td>";
				$indicator = "$path_to_root/themes/".user_theme(). "/images/ajax-loader.gif";
				echo "<td class='logoutBarRight'><img id='ajaxmark' src='$indicator' align='center' style='visibility:hidden;'></td>";
				echo "  <td class='logoutBarRight'><a class='shortcut' href='$path_to_root/admin/display_prefs.php?'>" . _("Preferences") . "</a>&nbsp;&nbsp;&nbsp;\n";
				echo "  <a class='shortcut' href='$path_to_root/admin/change_current_user_password.php?selected_id=" . $_SESSION["wa_current_user"]->username . "'>" . _("Change password") . "</a>&nbsp;&nbsp;&nbsp;\n";

				if ($help_base_url != null)
				{
					echo "$himg<a target = '_blank' onclick=" .'"'."javascript:openWindow(this.href,this.target); return false;".'" '. "href='". help_url()."'>" . _("Help") . "</a>&nbsp;&nbsp;&nbsp;";
				}
				echo "$img<a class='shortcut' href='$local_path_to_root/access/logout.php?'>" . _("Logout") . "</a>&nbsp;&nbsp;&nbsp;";
				echo "</td></tr><tr><td colspan=3>";
				echo "</td></tr></table>";
			}
			echo "</td></tr></table>";

			if ($no_menu)
				echo "<br>";
			elseif ($title && !$is_index)
			{
				echo "<center><table id='title'><tr><td width='100%' class='titletext'>$title</td>"
				."<td align=right>"
				.(user_hints() ? "<span id='hints'></span>" : '')
				."</td>"
				."</tr></table></center>";
			}
		}
		

		function menu_footer($no_menu, $is_index)
		{
			global $version, $allow_demo_mode, $app_title, $power_url, 
				$power_by, $path_to_root, $Pagehelp, $Ajax;
			include_once($path_to_root . "/includes/date_functions.inc");

			echo "</td></tr></table>\n"; // 'main_page'
			if ($no_menu == false) // bottom status line
			{
				if ($is_index)
					echo "<table class=bottomBar>\n";
				else
					echo "<table class=bottomBar2>\n";
				echo "<tr>";
				if (isset($_SESSION['wa_current_user'])) {
					$phelp = implode('; ', $Pagehelp);
					echo "<td class=bottomBarCell>" . Today() . " | " . Now() . "</td>\n";
					$Ajax->addUpdate(true, 'hotkeyshelp', $phelp);
					echo "<td id='hotkeyshelp'>".$phelp."</td>";
				}
				echo "</td></tr></table>\n";
			}
			echo "</td></tr> </table>\n"; // 'callout_main'
			echo "</table>\n";
			if ($no_menu == false)
			{
				echo "<table align='center' id='footer'>\n";
				
					echo "<tr>\n";
					echo "<td align='center' class='footer'><a target='_blank' href='$power_url' tabindex='-1'>
						 <a target='_blank' href='$power_url' tabindex='-1'><font>";
					echo "Copyright &copy; $power_by. All Rights Reserved.</font></a></td>\n";
					echo "</tr>\n";
				
				echo "</table><br><br>\n";
			}
		}
		*/



		function display_applications(&$waapp)
		{
			global $path_to_root;

			$selected_app = $waapp->get_selected_application();
			if (!$_SESSION["wa_current_user"]->check_application_access($selected_app))
				return;

			if (method_exists($selected_app, 'render_index'))
			{
				$selected_app->render_index();
				return;
			}

			echo "<table width=100% cellpadding='0' cellspacing='0'>";
			foreach ($selected_app->modules as $module)
			{
        		if (!$_SESSION["wa_current_user"]->check_module_access($module))
        			continue;
				// image
				echo "<tr>";
				// values
				echo "<td valign='top' class='menu_group'>";
				echo "<table border=0 width='100%'>";
				echo "<tr><td class='menu_group'>";
				echo $module->name;
				echo "</td></tr><tr>";
				echo "<td class='menu_group_items'>";

				foreach ($module->lappfunctions as $appfunction)
				{
					$img = $this->get_icon($appfunction->category);
					if ($appfunction->label == "")
						echo "&nbsp;<br>";
					elseif ($_SESSION["wa_current_user"]->can_access_page($appfunction->access)) 
					{
							echo $img.menu_link($appfunction->link, $appfunction->label)."<br>\n";
					}
					elseif (!$_SESSION["wa_current_user"]->hide_inaccessible_menu_items())
					{
							echo $img.'<span class="inactive">'
								.access_string($appfunction->label, true)
								."</span><br>\n";
					}
				}
				echo "</td>";
				if (sizeof($module->rappfunctions) > 0)
				{
					echo "<td width='50%' class='menu_group_items'>";
					foreach ($module->rappfunctions as $appfunction)
					{
						$img = $this->get_icon($appfunction->category);
						if ($appfunction->label == "")
							echo "&nbsp;<br>";
						elseif ($_SESSION["wa_current_user"]->can_access_page($appfunction->access)) 
						{
								echo $img.menu_link($appfunction->link, $appfunction->label)."<br>\n";
						}
						elseif (!$_SESSION["wa_current_user"]->hide_inaccessible_menu_items())
						{
								echo $img.'<span class="inactive">'
									.access_string($appfunction->label, true)
									."</span><br>\n";
						}
					}
					echo "</td>";
				}

				echo "</tr></table></td></tr>";
			}
			echo "</table>";
  	}
}
?>