<?php
# Script: add.bandf.mysql.php
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
    $package = "add.bandf.mysql.php";
    $formVars['update']         = clean($_GET['update'],        10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel($AL_User)) {
      $headers  = "From: Status Management DB <root@incomsu1.scc911.com>\r\n";
      $headers .= "MIME-Version: 1.0\r\n";
      $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

      $formVars['id']        = clean($_GET['id'], 10);
      $formVars['update']    = clean($_GET['update'], 10);
      $formVars['bf_name']   = clean($_GET['username'], 10);
      $formVars['bf_week']   = clean($_GET['week'], 10);
      $formVars['bf_borf']   = clean($_GET['borf'], 10);
      $formVars['bf_text']   = clean($_GET['bftext'], 1024);
      $formVars['bf_dev']    = clean($_GET['developer'], 10);
      $formVars['bf_status'] = clean($_GET['completed'], 10);

      if ($formVars['bf_name'] == 0) {
        $formVars['bf_name'] = $_SESSION['uid'];
      }

      if ($formVars['bf_status'] == 'true') {
        $formVars['bf_status'] = 1;

# so if the task is completed, build the e-mail to notify the requestor
        $body  = "<p>Your request has been completed.</p>";

        $body .= "<p><i>" . $formVars['bf_text'] . "</i></p>";

        $q_string  = "select usr_email ";
        $q_string .= "from users ";
        $q_string .= "where usr_id = " . $formVars['bf_name'];
        $q_users = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        $a_users = mysql_fetch_array($q_users);

        mail($a_users['usr_email'], "Status Management: Request Completed", $body, $headers);

        $q_string  = "select usr_email ";
        $q_string .= "from users ";
        $q_string .= "where usr_level = 1 and usr_id != 1";
        $q_users = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        while ($a_users = mysql_fetch_array($q_users)) {
          mail($a_users['usr_email'], "Status Management: Request Completed", $body, $headers);
        }
      } else {
        $formVars['bf_status'] = 0;

# so if the task is completed, build the e-mail to notify the requestor
        if ($formVars['update'] == 0 || $formVars['update'] == 1) {
          $q_string  = "select usr_first,usr_last ";
          $q_string .= "from users ";
          $q_string .= "where usr_id = " . $formVars['bf_name'];
          $q_users = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          $a_users = mysql_fetch_array($q_users);

          $body  = "<p>New request from " . $a_users['usr_first'] . " " . $a_users['usr_last'] . ".</p>";

          $body .= "<p><i>" . $formVars['bf_text'] . "</i></p>";

          $q_string = "select usr_email ";
          $q_string .= "from users ";
          $q_string .= "where usr_level = 1 and usr_id != 1";
          $q_users = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          while ($a_users = mysql_fetch_array($q_users)) {
            mail($a_users['usr_email'], "Status Management: New Request", $body, $headers);
          }
        }
      }

      if ($formVars['update'] == 0 || $formVars['update'] == 1) {

        logaccess($_SESSION['username'], $scriptname, "Adding bandf: " . $formVars['name']);

        $q_string = 
          "bf_borf   =  " . $formVars['bf_borf']   . "," .
          "bf_week   =  " . $formVars['bf_week']   . "," .
          "bf_text   = '" . $formVars['bf_text']   . "'," . 
          "bf_dev    =  " . $formVars['bf_dev']    . "," .
          "bf_status =  " . $formVars['bf_status'];

        if ($formVars['update'] == 0) {
# add the user name if it's a new item, otherwise don't change it.
          $q_string = "bf_name   =  " . $formVars['bf_name']   . "," . $q_string;
          $q_string = "insert into bandf set bf_id = NULL," . $q_string;
          $insert = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        }
        if ($formVars['update'] == 1) {
          $q_string = "update bandf set " . $q_string . " where bf_id = " . $formVars['id'];
          $insert = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        }
      }
    }
  }

  $output  = "<div id=\"main\">";
  $output .= "<table class=\"ui-widget-content\">";
  $output .= "<tr>";
  $output .=   "<th class=\"ui-state-default\" colspan=5>Bug Reports</th>";
  $output .= "</tr>";
  $output .= "<tr>";
  $output .=   "<th class=\"ui-state-default\">Del</th>";
  $output .=   "<th class=\"ui-state-default\">Reporter</th>";
  $output .=   "<th class=\"ui-state-default\">Date</th>";
  $output .=   "<th class=\"ui-state-default\">Description</th>";
  $output .=   "<th class=\"ui-state-default\">Worked</th>";
  $output .= "</tr>";

  $q_string  = "select bf_id,bf_name,bf_borf,bf_week,bf_text,bf_dev ";
  $q_string .= "from bandf ";
  $q_string .= "where bf_status = 0 and bf_borf = 1 ";
  $q_string .= "order by bf_week desc";
  $q_bandf = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_bandf = mysql_fetch_array($q_bandf)) {

    $q_string  = "select usr_name ";
    $q_string .= "from users ";
    $q_string .= "where usr_id = " . $a_bandf['bf_name'];
    $q_users = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
    $a_name = mysql_fetch_array($q_users);

    $q_string  = "select usr_name ";
    $q_string .= "from users ";
    $q_string .= "where usr_id = " . $a_bandf['bf_dev'];
    $q_users = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
    $a_dev = mysql_fetch_array($q_users);

    $dev = $a_dev['usr_name'];
    if ($a_bandf['bf_dev'] == 0) {
      $dev = "Unassigned";
    }

    $q_string  = "select wk_date ";
    $q_string .= "from weeks ";
    $q_string .= "where wk_id = " . $a_bandf['bf_week'];
    $q_weeks = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
    $a_weeks = mysql_fetch_array($q_weeks);

    if (check_userlevel($AL_Admin)) {
      $linkdel = "<a href='#' onClick=\"javascript:delete_item('add.bandf.del.php?id=" . $a_bandf['bf_id'] . "');\">";
      $linkstart = "<a href='#' onClick=\"javascript:show_file('add.bandf.fill.php?id=" . $a_bandf['bf_id'] . "');\">";
      $linkend = "</a>";
    } else {
      $linkdel = "";
      $linkstart = "";
      $linkend = "";
    }

    $output .= "<tr>";
    $output .= "  <td class=\"ui-widget-content delete\">" . $linkdel . "x" . $linkend . "</td>";
    $output .= "  <td class=\"ui-widget-content\">" . $linkstart . $a_name['usr_name'] . $linkend . "</td>";
    $output .= "  <td class=\"ui-widget-content\">" . $linkstart . $a_weeks['wk_date'] . $linkend . "</td>";
    $output .= "  <td class=\"ui-widget-content\">" . $linkstart . mysql_real_escape_string($a_bandf['bf_text']) . $linkend . "</td>";
    $output .= "  <td class=\"ui-widget-content\">" . $linkstart . $dev . $linkend . "</td>";
    $output .= "</tr>";

  }

  $output .= "</table>";
  $output .= "</div>";
  $output .= "<div id=\"main\">";
  $output .= "<table class=\"ui-widget-content\">";
  $output .= "<tr>";
  $output .=   "<th class=\"ui-state-default\" colspan=5>Feature Requests</th>";
  $output .= "</tr>";
  $output .= "<tr>";
  $output .=   "<th class=\"ui-state-default\">Del</th>";
  $output .=   "<th class=\"ui-state-default\">Reporter</th>";
  $output .=   "<th class=\"ui-state-default\">Date</th>";
  $output .=   "<th class=\"ui-state-default\">Description</th>";
  $output .=   "<th class=\"ui-state-default\">Worked</th>";
  $output .= "</tr>";
  $output .= "</div>";

  $q_string  = "select bf_id,bf_name,bf_borf,bf_week,bf_text,bf_dev ";
  $q_string .= "from bandf ";
  $q_string .= "where bf_status = 0 and bf_borf = 0 ";
  $q_string .= "order by bf_week desc";
  $q_bandf = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_bandf = mysql_fetch_array($q_bandf)) {

    $q_string  = "select usr_name ";
    $q_string .= "from users ";
    $q_string .= "where usr_id = " . $a_bandf['bf_name'];
    $q_users = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
    $a_name = mysql_fetch_array($q_users);

    $q_string  = "select usr_name ";
    $q_string .= "from users ";
    $q_string .= "where usr_id = " . $a_bandf['bf_dev'];
    $q_users = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
    $a_dev = mysql_fetch_array($q_users);

    $dev = $a_dev['usr_name'];
    if ($a_bandf['bf_dev'] == 0) {
      $dev = "Unassigned";
    }

    $q_string  = "select wk_date ";
    $q_string .= "from weeks ";
    $q_string .= "where wk_id = " . $a_bandf['bf_week'];
    $q_weeks = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
    $a_weeks = mysql_fetch_array($q_weeks);

    if (check_userlevel($AL_Admin)) {
      $linkdel = "<a href=\"#\" onClick=\"javascript:delete_item('add.bandf.del.php?id=" . $a_bandf['bf_id'] . "');\">";
      $linkstart = "<a href=\"#\" onClick=\"javascript:show_file('add.bandf.fill.php?id=" . $a_bandf['bf_id'] . "');\">";
      $linkend = "</a>";
    } else {
      $linkdel = "";
      $linkstart = "";
      $linkend = "";
    }

    $output .= "<tr>";
    $output .= "  <td class=\"ui-widget-content delete\">" . $linkdel . "x" . $linkend . "</td>";
    $output .= "  <td class=\"ui-widget-content\">" . $linkstart . $a_name['usr_name'] . $linkend . "</td>";
    $output .= "  <td class=\"ui-widget-content\">" . $linkstart . $a_weeks['wk_date'] . $linkend . "</td>";
    $output .= "  <td class=\"ui-widget-content\">" . $linkstart . mysql_real_escape_string($a_bandf['bf_text']) . $linkend . "</td>";
    $output .= "  <td class=\"ui-widget-content\">" . $linkstart . $dev . $linkend . "</td>";
    $output .= "</tr>";

  }

  mysql_free_result($q_bandf);

?>

document.getElementById('from_mysql').innerHTML = '<?php print mysql_real_escape_string($output); ?>';

document.bandf.week['0'].checked = true;
document.bandf.username.value =  0;
document.bandf.bftext.value = '';
document.bandf.borf['0'].checked = true;
document.bandf.developer['0'].selected = true;
document.bandf.completed.checked = false;
document.bandf.id.value = 0;

document.bandf.update.disabled = true;

