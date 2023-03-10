<?php
# Script: levels.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# Description: Retrieve data and update the database with the new info. Prepare and display the table

  header('Content-Type: text/javascript');

  include('settings.php');
  $called = 'yes';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');

  if (isset($_SESSION['username'])) {
    $package = "levels.mysql.php";
    $formVars['update']        = clean($_GET['update'],        10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel($db, $AL_Admin)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['id']            = clean($_GET['id'],            10);
        $formVars['lvl_name']      = clean($_GET['lvl_name'],     255);
        $formVars['lvl_level']     = clean($_GET['lvl_level'],     10);
        $formVars['lvl_disabled']  = clean($_GET['lvl_disabled'],  10);
        $formVars['lvl_changedby'] = clean($_SESSION['uid'],       10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['lvl_level'] == '') {
          $formVars['lvl_level'] = 0;
        }

        if (strlen($formVars['lvl_name']) > 0) {
          logaccess($db, $_SESSION['username'], $package, "Building the query.");

          $q_string =
            "lvl_name      = \"" . $formVars['lvl_name']      . "\"," . 
            "lvl_level     =   " . $formVars['lvl_level']     . "," . 
            "lvl_disabled  =   " . $formVars['lvl_disabled']  . "," . 
            "lvl_changedby =   " . $formVars['lvl_changedby'];

          if ($formVars['update'] == 0) {
            $query = "insert into st_levels set lvl_id = NULL," . $q_string;
            $message = "Level added.";
          }
          if ($formVars['update'] == 1) {
            $query = "update st_levels set " . $q_string . " where lvl_id = " . $formVars['id'];
            $message = "Level updated.";
          }

          logaccess($db, $_SESSION['username'], $package, "Saving Changes to: " . $formVars['lvl_name']);

          mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysqli_error($db)));

          print "alert('" . $message . "');\n";
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      logaccess($db, $_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Level Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('level-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"level-listing-help\" style=\"display: none\">\n";

      $output .= "<div class=\"main-help ui-widget-content\">\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>Level Listing</strong>\n";
      $output .= "  <ul>\n";
      $output .= "    <li><strong>Delete (x)</strong> - Click here to delete this access level from the Inventory. It's better to disable the level.</li>\n";
      $output .= "    <li><strong>Editing</strong> - Click on a level to toggle the form and edit the level.</li>\n";
      $output .= "    <li><strong>Highlight</strong> - If a level has been <span class=\"ui-state-error\">highlighted</span>, then the level has been disabled and will not be visible in any selection menus and will restrict access to areas that were accessible in the past.</li>\n";
      $output .= "  </ul></li>\n";
      $output .= "</ul>\n";

      $output .= "</div>\n";

      $output .= "</div>\n";

      $output .= "<table class=\"ui-styled-table\">";
      $output .= "<tr>";
      if (check_userlevel($db, $AL_Admin)) {
        $output .= "  <th class=\"ui-state-default\">Del</th>";
      }
      $output .= "  <th class=\"ui-state-default\">Id</th>";
      $output .= "  <th class=\"ui-state-default\">Access Level</th>";
      $output .= "  <th class=\"ui-state-default\">Level Name</th>";
      $output .= "</tr>";

      $q_string  = "select lvl_id,lvl_name,lvl_level,lvl_disabled ";
      $q_string .= "from st_levels ";
      $q_string .= "order by lvl_level,lvl_name";
      $q_st_levels = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_st_levels) > 0) {
        while ($a_st_levels = mysqli_fetch_array($q_st_levels)) {

          $linkstart = "<a href=\"#\" onclick=\"show_file('levels.fill.php?id=" . $a_st_levels['lvl_id'] . "');jQuery('#dialogLevel').dialog('open');\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onclick=\"delete_level('levels.del.php?id=" . $a_st_levels['lvl_id'] . "');\">";
          $linkend = "</a>";

          $class = "ui-widget-content";
          if ($a_st_levels['lvl_disabled']) {
            $class = "ui-state-error";
          }

          $output .= "<tr>";
          if (check_userlevel($db, $AL_Admin)) {
            $output .= "  <td class=\"" . $class . " delete\">" . $linkdel   . "</td>";
          }
          $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_st_levels['lvl_id']        . $linkend . "</td>";
          $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_st_levels['lvl_level']     . $linkend . "</td>";
          $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_st_levels['lvl_name']      . $linkend . "</td>";
          $output .= "</tr>";
        }
      } else {
        $output .= "<tr>";
        $output .= "  <td class=\"" . $class . "\" colspan=\"4\">No records found.</td>";
        $output .= "</tr>";
      }

      $output .= "</table>";

      mysqli_free_result($q_st_levels);

      print "document.getElementById('table_mysql').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n\n";

      print "document.levels.lvl_name.value = '';\n";
      print "document.levels.lvl_level.value = '';\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
