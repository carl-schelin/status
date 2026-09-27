<?php
# Script: progress.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# Description: Retrieve data and update the database with the new info. Prepare and display the table

  header('Content-Type: text/javascript');

  include('settings.php');
  $called = 'yes';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');

  if (isset($_SESSION['username'])) {
    $package = "progress.mysql.php";
    $formVars['update']        = clean($_GET['update'],        10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel($db, $AL_Admin)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['id']            = clean($_GET['id'],            10);
        $formVars['pro_name']      = clean($_GET['pro_name'],     255);
        $formVars['pro_desc']      = clean($_GET['pro_desc'],      10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }

        if (strlen($formVars['pro_name']) > 0) {
          logaccess($db, $_SESSION['username'], $package, "Building the query.");

          $q_string =
            "pro_name      = \"" . $formVars['pro_name']      . "\"," . 
            "pro_desc      = \"" . $formVars['prod_desc']     . "\"";

          if ($formVars['update'] == 0) {
            $query = "insert into st_process set pro_id = NULL," . $q_string;
          }
          if ($formVars['update'] == 1) {
            $query = "update st_process set " . $q_string . " where pro_id = " . $formVars['id'];
          }

          logaccess($db, $_SESSION['username'], $package, "Saving Changes to: " . $formVars['pro_name']);

          mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysqli_error($db)));
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      logaccess($db, $_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-widget-content\">\n";
      if (check_userlevel($db, $AL_Admin)) {
        $output .= "  <th class=\"ui-state-default\" width=\"160\">Delete Progress</th>";
      }
      $output .= "  <th class=\"ui-state-default\">ID</th>\n";
      $output .= "  <th class=\"ui-state-default\">Description</th>\n";
      $output .= "  <th class=\"ui-state-default\">Help</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select pro_id,pro_name,pro_desc ";
      $q_string .= "from st_progress ";
      $q_string .= "order by pro_id";
      $q_st_progress = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_st_progress) > 0) {
        while ($a_st_progress = mysqli_fetch_array($q_st_progress)) {

          $linkstart = "<a href=\"#\" onclick=\"show_file('progress.fill.php?id=" . $a_st_progress['pro_id'] . "');jQuery('#dialogUpdate').dialog('open');return false;\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onclick=\"delete_line('progress.del.php?id="  . $a_st_progress['pro_id'] . "');\">";
          $linkend   = "</a>";

          $output .= "<tr>\n";
          if (check_userlevel($db, $AL_Admin)) {
            $output .= "  <td class=\"ui-widget-content delete\">" . $linkdel   . "</td>";
          }
          $output .= "  <td class=\"ui-widget-content\">" . $linkstart . $a_st_progress['pro_id']   . $linkend . "</td>\n";
          $output .= "  <td class=\"ui-widget-content\">" . $linkstart . $a_st_progress['pro_name'] . $linkend . "</td>\n";
          $output .= "  <td class=\"ui-widget-content\">" . $linkstart . $a_st_progress['pro_desc'] . $linkend . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"4\">No records found.</td>\n";
        $output .= "</tr>\n";
      }
      $output .= "</table>\n";

      mysqli_free_result($q_st_progress);

      print "document.getElementById('table_mysql').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n\n";

      print "document.formUpdate.pro_name.value = '';\n";
      print "document.formUpdate.pro_desc.value = '';\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
