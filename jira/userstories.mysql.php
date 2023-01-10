<?php
# Script: userstories.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: Retrieve data and update the database with the new info. Prepare and display the table

  header('Content-Type: text/javascript');

  include('settings.php');
  $called = 'yes';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');

  if (isset($_SESSION['username'])) {
    $package = "userstories.mysql.php";
    $formVars['update'] = clean($_GET['update'], 10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel($db, $AL_Admin)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['id']               = clean($_GET['id'],                10);
        $formVars['user_epic']        = clean($_GET['user_epic'],         10);
        $formVars['user_jira']        = clean($_GET['user_jira'],         60);
        $formVars['user_task']        = clean($_GET['user_task'],        255);
        $formVars['user_user']        = $_SESSION['uid'];
        $formVars['user_closed']      = clean($_GET['user_closed'],       10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['user_closed'] == 'true') {
          $formVars['user_closed'] = 1;
        } else {
          $formVars['user_closed'] = 0;
        }
    
        if (strlen($formVars['user_jira']) > 0) {
          logaccess($db, $_SESSION['uid'], $package, "Building the query.");

          $q_string =
            "user_epic        =   " . $formVars['user_epic']     . "," .
            "user_jira        = \"" . $formVars['user_jira']     . "\"," .
            "user_task        = \"" . $formVars['user_task']     . "\"," .
            "user_user        =   " . $formVars['user_user']     . "," .
            "user_closed      =   " . $formVars['user_closed'];

          if ($formVars['update'] == 0) {
            $query = "insert into userstories set user_id = NULL, " . $q_string;
          }
          if ($formVars['update'] == 1) {
            $query = "update userstories set " . $q_string . " where user_id = " . $formVars['id'];
          }

          logaccess($db, $_SESSION['uid'], $package, "Saving Changes to: " . $formVars['user_jira']);

          mysqli_query($db, $query) or die($query . ": " . mysqli_error($db));
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      logaccess($db, $_SESSION['uid'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">User Story Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('story-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"story-listing-help\" style=\"display: none\">\n";

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

# because some user stories have no epic "owner"

      $class = 'ui-widget-content';

      $output .= "<tr>";
      $output .= "  <td class=\"" . $class . " button\">" . "Epic: " . "</td>";
      $output .= "  <td class=\"" . $class . "\" colspan=\"3\">" . "User Stories not assigned to an Epic" . "</td>";
      $output .= "</tr>";

      $q_string  = "select user_id,user_jira,user_task,user_closed ";
      $q_string .= "from userstories ";
      $q_string .= "where user_user = " . $_SESSION['uid'] . " and user_epic = 0 ";
      $q_string .= "order by user_jira ";
      $q_userstories = mysqli_query($db, $q_string) or die($q_string . ": " . mysqli_error($db));
      if (mysqli_num_rows($q_userstories) > 0) {
        while ($a_userstories = mysqli_fetch_array($q_userstories)) {

          $linkstart = "<a href=\"#\" onclick=\"show_file('userstories.fill.php?id="  . $a_userstories['user_id'] . "');jQuery('#dialogStory').dialog('open');return false;\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onclick=\"delete_story('userstories.del.php?id=" . $a_userstories['user_id'] . "');\">";
          $linkend   = "</a>";

          $class = 'ui-widget-content';

          $closed = 'No';
          if ($a_userstories['user_closed']) {
            $closed = 'Yes';
          }

          $output .= "<tr>";
          if (check_userlevel($db, $AL_Developer)) {
            $output .= "  <td class=\"ui-widget-content delete\">" . $linkdel . "</td>";
          }
          $output .= "  <td class=\"" . $class . "\">&nbsp;*&nbsp;" . $linkstart . $a_userstories['user_jira']  . $linkend . "</td>";
          $output .= "  <td class=\"" . $class . "\">&nbsp;*&nbsp;" . $linkstart . $a_userstories['user_task'] . $linkend . "</td>";
          $output .= "  <td class=\"" . $class . "\">" . $linkstart . $closed . $linkend . "</td>";
          $output .= "</tr>";

        }
      }

      $q_string  = "select epic_id,epic_jira,epic_title ";
      $q_string .= "from st_epics ";
      $q_string .= "where epic_user = " . $_SESSION['uid'] . " and epic_closed = 0 ";
      $q_string .= "order by epic_jira ";
      $q_st_epics = mysqli_query($db, $q_string) or die($q_string . ": " . mysqli_error($db));
      if (mysqli_num_rows($q_st_epics) > 0) {
        while ($a_st_epics = mysqli_fetch_array($q_st_epics)) {

          $class = 'ui-widget-content';

          $output .= "<tr>";
          $output .= "  <td class=\"" . $class . " button\">" . "Epic: " . "</td>";
          $output .= "  <td class=\"" . $class . "\" colspan=\"3\">" . $a_st_epics['epic_jira'] . " - " . $a_st_epics['epic_title'] . "</td>";
          $output .= "</tr>";

          $q_string  = "select user_id,user_jira,user_task,user_closed ";
          $q_string .= "from userstories ";
          $q_string .= "where user_user = " . $_SESSION['uid'] . " and user_epic = " . $a_st_epics['epic_id'] . " and user_closed = 0 ";
          $q_string .= "order by user_jira ";
          $q_userstories = mysqli_query($db, $q_string) or die($q_string . ": " . mysqli_error($db));
          if (mysqli_num_rows($q_userstories) > 0) {
            while ($a_userstories = mysqli_fetch_array($q_userstories)) {

              $linkstart = "<a href=\"#\" onclick=\"show_file('userstories.fill.php?id="  . $a_userstories['user_id'] . "');jQuery('#dialogStory').dialog('open');return false;\">";
              $linkdel   = "<input type=\"button\" value=\"Remove\" onclick=\"delete_story('userstories.del.php?id=" . $a_userstories['user_id'] . "');\">";
              $linkend   = "</a>";

              $class = 'ui-widget-content';

              $closed = 'No';
              if ($a_userstories['user_closed']) {
                $class = 'ui-status-highlight';
                $closed = 'Yes';
              }

              $output .= "<tr>";
              if (check_userlevel($db, $AL_Developer)) {
                $output .= "  <td class=\"ui-widget-content delete\">" . $linkdel . "</td>";
              }
              $output .= "  <td class=\"" . $class . "\">&nbsp;*&nbsp;" . $linkstart . $a_userstories['user_jira']  . $linkend . "</td>";
              $output .= "  <td class=\"" . $class . "\">&nbsp;*&nbsp;" . $linkstart . $a_userstories['user_task'] . $linkend . "</td>";
              $output .= "  <td class=\"" . $class . "\">" . $linkstart . $closed . $linkend . "</td>";
              $output .= "</tr>";

            }
          }
        }
      } else {
        $output .= "<tr>";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"4\">No records found.</td>";
        $output .= "</tr>";
      }

      $output .= "</table>";

      mysqli_free_result($q_userstories);

      print "document.userstories.user_jira.value = '';\n";
      print "document.userstories.user_task.value = '';\n";
      print "document.userstories.user_closed.checked = false;\n";

      print "document.getElementById('story_mysql').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n\n";

    } else {
      logaccess($db, $_SESSION['uid'], $package, "Unauthorized access.");
    }
  }
?>
