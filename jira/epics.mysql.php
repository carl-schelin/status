<?php
# Script: epics.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# Description: Retrieve data and update the database with the new info. Prepare and display the table

  header('Content-Type: text/javascript');

  include('settings.php');
  $called = 'yes';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');

  if (isset($_SESSION['username'])) {
    $package = "epics.mysql.php";
    $formVars['update'] = clean($_GET['update'], 10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel($db, $AL_Admin)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['id']               = clean($_GET['id'],                10);
        $formVars['epic_jira']        = clean($_GET['epic_jira'],         60);
        $formVars['epic_title']       = clean($_GET['epic_title'],       255);
        $formVars['epic_closed']      = clean($_GET['epic_closed'],       10);
        $formVars['epic_user']        = $_SESSION['uid'];

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['epic_closed'] == 'true') {
          $formVars['epic_closed'] = 1;
        } else {
          $formVars['epic_closed'] = 0;
        }
    
        if (strlen($formVars['epic_jira']) > 0) {
          logaccess($db, $_SESSION['uid'], $package, "Building the query.");

          $q_string =
            "epic_jira        = \"" . $formVars['epic_jira']        . "\"," .
            "epic_title       = \"" . $formVars['epic_title']       . "\"," .
            "epic_user        =   " . $formVars['epic_user']        . "," . 
            "epic_closed      =   " . $formVars['epic_closed'];

          if ($formVars['update'] == 0) {
            $query = "insert into st_epics set epic_id = NULL, " . $q_string;
          }
          if ($formVars['update'] == 1) {
            $query = "update st_epics set " . $q_string . " where epic_id = " . $formVars['id'];
          }

          logaccess($db, $_SESSION['uid'], $package, "Saving Changes to: " . $formVars['epic_jira']);

          mysqli_query($db, $query) or die($query . ": " . mysqli_error($db));
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      logaccess($db, $_SESSION['uid'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Epic Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('epic-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"epic-listing-help\" style=\"display: none\">\n";

      $output .= "<div class=\"main-help ui-widget-content\">\n";
      $output .= "<ul>\n";
      $output .= "  <li><strong>Location Listing</strong>\n";
      $output .= "  <ul>\n";
      $output .= "    <li><strong>Editing</strong> - Click on a location to edit it.</li>\n";
      $output .= "  </ul></li>\n";
      $output .= "</ul>\n";

      $output .= "</div>\n";

      $output .= "</div>\n";

      $output .= "<table class=\"ui-styled-table\">\n";
      $output .= "<tr>\n";
      if (check_userlevel($db, $AL_Developer)) {
        $output .= "  <th class=\"ui-state-default\">Del</th>\n";
      }
      $output .= "  <th class=\"ui-state-default\">Jira</th>\n";
      $output .= "  <th class=\"ui-state-default\">Title</th>\n";
      $output .= "  <th class=\"ui-state-default\">Closed</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select epic_id,epic_jira,epic_title,epic_closed ";
      $q_string .= "from st_epics ";
      $q_string .= "where epic_user = " . $_SESSION['uid'] . " and epic_closed = 0 ";
      $q_string .= "order by epic_jira ";
      $q_st_epics = mysqli_query($db, $q_string) or die($q_string . ": " . mysqli_error($db));
      if (mysqli_num_rows($q_st_epics) > 0) {
        while ($a_st_epics = mysqli_fetch_array($q_st_epics)) {

          $linkstart = "<a href=\"#\" onclick=\"show_file('epics.fill.php?id="  . $a_st_epics['epic_id'] . "');jQuery('#dialogEpic').dialog('open');return false;\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onclick=\"delete_epic('epics.del.php?id=" . $a_st_epics['epic_id'] . "');\">";
          $linkend   = "</a>";

          $class = 'ui-widget-content';

          $closed = 'No';
          if ($a_st_epics['epic_closed']) {
            $class = 'ui-state-highlight';
            $closed = 'Yes';
          }

          $output .= "<tr>";
          if (check_userlevel($db, $AL_Developer)) {
            $output .= "  <td class=\"ui-widget-content delete\">" . $linkdel . "</td>";
          }
          $output .= "  <td class=\"" . $class . "\">" . $linkstart . $a_st_epics['epic_jira']  . $linkend . "</td>";
          $output .= "  <td class=\"" . $class . "\">" . $linkstart . $a_st_epics['epic_title'] . $linkend . "</td>";
          $output .= "  <td class=\"" . $class . "\">" . $linkstart . $closed . $linkend . "</td>";
          $output .= "</tr>";

        }
      } else {
        $output .= "<tr>";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"4\">No records found.</td>";
        $output .= "</tr>";
      }

      $output .= "</table>";

      mysqli_free_result($q_epics);

      print "document.epics.epic_jira.value = '';\n";
      print "document.epics.epic_title.value = '';\n";
      print "document.epics.epic_closed.checked = false;\n";

      print "document.getElementById('epic_mysql').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n\n";

    } else {
      logaccess($db, $_SESSION['uid'], $package, "Unauthorized access.");
    }
  }
?>
