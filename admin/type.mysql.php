<?php
# Script: type.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# Description: Retrieve data and update the database with the new info. Prepare and display the table

  header('Content-Type: text/javascript');

  include('settings.php');
  $called = 'yes';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');

  if (isset($_SESSION['username'])) {
    $package = "type.mysql.php";
    $formVars['update']        = clean($_GET['update'],        10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel($db, $AL_Admin)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['id']              = clean($_GET['id'],           10);
        $formVars['typ_name']        = clean($_POST['type'],        70);
        $formVars['typ_desc']        = clean($_POST['desc'],        70);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }

        if (strlen($formVars['typ_name']) > 0) {
          logaccess($db, $_SESSION['username'], $package, "Building the query.");

          $q_string =
            "typ_name     = \"" . $formVars['typ_name']     . "\"," .
            "typ_desc     = \"" . $formVars['typ_desc']     . "\" ";

          if ($formVars['update'] == 0) {
            $query = "insert into st_type set typ_id = NULL," . $q_string;
          }
          if ($formVars['update'] == 1) {
            $query = "update st_type set " . $q_string . " where typ_id = " . $formVars['id'];
          }

          logaccess($db, $_SESSION['username'], $package, "Saving Changes to: " . $formVars['typ_name']);

          mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysqli_error($db)));
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      logaccess($db, $_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";

      $output .= "<table class=\"ui-widget-content\">\n";
      $output .= "<tr>\n";
      if (check_userlevel($db, $AL_Admin)) {
        $output .= "  <th class=\"ui-state-default\" width=\"160\">Delete Type</th>";
      }
      $output .= "  <th class=\"ui-state-default\">Name</th>\n";
      $output .= "  <th class=\"ui-state-default\">Description</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select typ_id,typ_name,typ_desc ";
      $q_string .= "from st_type ";
      $q_string .= "order by typ_id";
      $q_st_type = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_st_type) > 0) {
        while ($a_st_type = mysqli_fetch_array($q_st_type)) {

          $linkstart = "<a href=\"#\" onclick=\"show_file('type.fill.php?id=" . $a_st_type['typ_id'] . "');jQuery('#dialogUpdate').dialog('open');return false;\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onclick=\"delete_line('type.del.php?id="  . $a_st_type['typ_id'] . "');\">";
          $linkend   = "</a>";

          $output .= "<tr>\n";
          if (check_userlevel($db, $AL_Admin)) {
            $output .= "  <td class=\"ui-widget-content delete\">" . $linkdel   . "</td>";
          }
          $output .= "  <td class=\"ui-widget-content\">" . $linkstart . $a_st_type['typ_name'] . $linkend . "</td>\n";
          $output .= "  <td class=\"ui-widget-content\">"              . $a_st_type['typ_desc']            . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"3\">No records found.</td>\n";
        $output .= "</tr>\n";
      }

      mysqli_free_result($q_st_type);

      $output .= "</table>\n";

      print "document.getElementById('table_mysql').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n\n";

      print "document.formUpdate.typ_name.value = '';\n";
      print "document.formUpdate.typ_desc.value = '';\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
