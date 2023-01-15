<?php
# Script: groups.mysql.php
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
    $package = "groups.mysql.php";
    $formVars['update'] = clean($_GET['update'], 10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel($db, $AL_Admin)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['id']               = clean($_GET['id'],                10);
        $formVars['grp_name']         = clean($_GET['grp_name'],         100);
        $formVars['grp_manager']      = clean($_GET['grp_manager'],       10);
        $formVars['grp_email']        = clean($_GET['grp_email'],        255);
        $formVars['grp_disabled']     = clean($_GET['grp_disabled'],     255);
        $formVars['grp_changedby']    = clean($_SESSION['uid'],           10);
        $formVars['grp_report']       = clean($_GET['grp_report'],        10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['grp_report'] == '') {
          $formVars['grp_report'] = 0;
        }

        if (strlen($formVars['grp_name']) > 0) {
          logaccess($db, $_SESSION['username'], $package, "Building the query.");

# get old group manager.
          $q_string  = "select grp_manager ";
          $q_string .= "from groups ";
          $q_string .= "where grp_id = " . $formVars['id'] . " ";
          $q_groups = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
          if (mysqli_num_rows($q_groups) > 0) {
            $a_groups = mysqli_fetch_array($q_groups);
# got it, now update everyone in the same group with the same old manager assuming the group already exists.
            $q_string  = "update ";
            $q_string .= "st_users ";
            $q_string .= "set usr_manager = " . $formVars['grp_manager'] . " ";
            $q_string .= "where usr_group = " . $formVars['id'] . " and usr_manager = " . $a_groups['grp_manager'] . " ";
            $result = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
          }

# all done. now update groups with the new information.
          $q_string =
            "grp_name          = \"" . $formVars['grp_name']          . "\"," . 
            "grp_manager       =   " . $formVars['grp_manager']       . "," . 
            "grp_email         = \"" . $formVars['grp_email']         . "\"," . 
            "grp_disabled      =   " . $formVars['grp_disabled']      . "," . 
            "grp_changedby     =   " . $formVars['grp_changedby']     . "," . 
            "grp_report        =   " . $formVars['grp_report'];

          if ($formVars['update'] == 0) {
            $query = "insert into groups set grp_id = NULL," . $q_string;
            $message = "Group added.";
          }
          if ($formVars['update'] == 1) {
            $query = "update groups set " . $q_string . " where grp_id = " . $formVars['id'];
            $message = "Group updated.";
          }

          logaccess($db, $_SESSION['username'], $package, "Saving Changes to: " . $formVars['grp_name']);

          mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysqli_error($db)));

          print "alert('" . $message . "');\n";
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }

      $group  = "<table class=\"ui-styled-table\">\n";
      $group .= "<tr>\n";
      $group .= "  <th class=\"ui-state-default\">Group Listing</th>\n";
      $group .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('group-listing-help');\">Help</a></th>\n";
      $group .= "</tr>\n";
      $group .= "</table>\n";

      $group .= "<div id=\"group-listing-help\" style=\"display: none\">\n";

      $header  = "<div class=\"main-help ui-widget-content\">\n";

      $header .= "<ul>\n";
      $header .= "  <li><strong>Group Listing</strong>\n";
      $header .= "  <ul>\n";
      $header .= "    <li><strong>Delete (x)</strong> - Click here to delete this group from the Inventory. It's better to disable the user.</li>\n";
      $header .= "    <li><strong>Editing</strong> - Click on a group to toggle the form and edit the group.</li>\n";
      $header .= "    <li><strong>Highlight</strong> - If a group is <span class=\"ui-state-error\">highlighted</span>, then the group has been disabled and will not be visible in any selection menus.</li>\n";
      $header .= "  </ul></li>\n";
      $header .= "</ul>\n";

      $header .= "</div>\n";

      $header .= "</div>\n";


      $title  = "<table class=\"ui-styled-table\">";
      $title .= "<tr>";
      if (check_userlevel($db, $AL_Admin)) {
        $title .= "  <th class=\"ui-state-default\">Del</th>";
      }
      $title .= "  <th class=\"ui-state-default\">Id</th>";
      $title .= "  <th class=\"ui-state-default\">Group</th>";
      $title .= "  <th class=\"ui-state-default\">Group EMail</th>";
      $title .= "  <th class=\"ui-state-default\">Group Manager</th>";
      $title .= "  <th class=\"ui-state-default\">Report</th>";
      $title .= "</tr>";

      $group     .= $header . $title;

      $q_string  = "select grp_id,grp_name,grp_email,usr_last,usr_first,grp_disabled,grp_report ";
      $q_string .= "from groups ";
      $q_string .= "left join st_users on st_users.usr_id = groups.grp_manager ";
      $q_string .= "order by grp_name";
      $q_groups = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_groups) > 0) {
        while ($a_groups = mysqli_fetch_array($q_groups)) {

          $linkstart = "<a href=\"#\" onclick=\"show_file('groups.fill.php?id="  . $a_groups['grp_id'] . "');jQuery('#dialogGroup').dialog('open');\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onclick=\"delete_line('groups.del.php?id=" . $a_groups['grp_id'] . "');\">";
          $linkend = "</a>";

          $class = "ui-widget-content";
          if ($a_groups['grp_disabled']) {
            $class = "ui-state-error";
          }

          $group .= "<tr>";
          if (check_userlevel($db, $AL_Admin)) {
            $group .= "  <td class=\"" . $class . " delete\">" . $linkdel   . "</td>";
          }
          $group .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_groups['grp_id']           . $linkend . "</td>";
          $group .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_groups['grp_name']         . $linkend . "</td>";
          $group .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_groups['grp_email']        . $linkend . "</td>";
          $group .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_groups['usr_first'] . " " . $a_groups['usr_last'] . $linkend . "</td>";
          $group .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_groups['grp_report']       . $linkend . "</td>";
          $group .= "</tr>";

        }
      } else {
        $group .= "<tr>";
        $group .= "  <td class=\"" . $class . "\" colspan=\"6\">No records found.</td>";
        $group .= "</tr>";
      }

      mysqli_free_result($q_groups);

      $group .= "</table>";

      print "document.getElementById('group_mysql').innerHTML = '"     . mysqli_real_escape_string($db, $group)     . "';\n\n";

      print "document.groups.grp_name.value = '';\n";
      print "document.groups.grp_email.value = '';\n";
      print "document.groups.grp_manager[0].selected = true;\n";
      print "document.groups.grp_disabled[0].selected = true;\n";
      print "document.groups.grp_report.value = '';\n";
    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
