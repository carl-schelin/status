<?php
# Script: add.bandf.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
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

    if (check_userlevel($db, $AL_User)) {
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
        $q_string .= "from st_users ";
        $q_string .= "where usr_id = " . $formVars['bf_name'];
        $q_st_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
        $a_st_users = mysqli_fetch_array($q_st_users);

        mail($a_st_users['usr_email'], "Status Management: Request Completed", $body, $headers);

        $q_string  = "select usr_email ";
        $q_string .= "from st_users ";
        $q_string .= "where usr_level = 1 and usr_id != 1";
        $q_st_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
        while ($a_st_users = mysqli_fetch_array($q_st_users)) {
          mail($a_st_users['usr_email'], "Status Management: Request Completed", $body, $headers);
        }
      } else {
        $formVars['bf_status'] = 0;

# so if the task is completed, build the e-mail to notify the requestor
        if ($formVars['update'] == 0 || $formVars['update'] == 1) {
          $q_string  = "select usr_first,usr_last ";
          $q_string .= "from st_users ";
          $q_string .= "where usr_id = " . $formVars['bf_name'];
          $q_st_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
          $a_st_users = mysqli_fetch_array($q_st_users);

          $body  = "<p>New request from " . $a_st_users['usr_first'] . " " . $a_st_users['usr_last'] . ".</p>";

          $body .= "<p><i>" . $formVars['bf_text'] . "</i></p>";

          $q_string = "select usr_email ";
          $q_string .= "from st_users ";
          $q_string .= "where usr_level = 1 and usr_id != 1";
          $q_st_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
          while ($a_st_users = mysqli_fetch_array($q_st_users)) {
            mail($a_st_users['usr_email'], "Status Management: New Request", $body, $headers);
          }
        }
      }

      if ($formVars['update'] == 0 || $formVars['update'] == 1) {

        logaccess($db, $_SESSION['username'], $scriptname, "Adding bandf: " . $formVars['name']);

        $q_string = 
          "bf_borf   =  " . $formVars['bf_borf']   . "," .
          "bf_week   =  " . $formVars['bf_week']   . "," .
          "bf_text   = '" . $formVars['bf_text']   . "'," . 
          "bf_dev    =  " . $formVars['bf_dev']    . "," .
          "bf_status =  " . $formVars['bf_status'];

        if ($formVars['update'] == 0) {
# add the user name if it's a new item, otherwise don't change it.
          $q_string = "bf_name   =  " . $formVars['bf_name']   . "," . $q_string;
          $q_string = "insert into st_bandf set bf_id = NULL," . $q_string;
          $insert = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
        }
        if ($formVars['update'] == 1) {
          $q_string = "update st_bandf set " . $q_string . " where bf_id = " . $formVars['id'];
          $insert = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
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
  $q_string .= "from st_bandf ";
  $q_string .= "where bf_status = 0 and bf_borf = 1 ";
  $q_string .= "order by bf_week desc";
  $q_st_bandf = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ($a_st_bandf = mysqli_fetch_array($q_st_bandf)) {

    $q_string  = "select usr_name ";
    $q_string .= "from st_users ";
    $q_string .= "where usr_id = " . $a_st_bandf['bf_name'];
    $q_st_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
    $a_name = mysqli_fetch_array($q_st_users);

    $q_string  = "select usr_name ";
    $q_string .= "from st_users ";
    $q_string .= "where usr_id = " . $a_st_bandf['bf_dev'];
    $q_st_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
    $a_dev = mysqli_fetch_array($q_st_users);

    $dev = $a_dev['usr_name'];
    if ($a_st_bandf['bf_dev'] == 0) {
      $dev = "Unassigned";
    }

    $q_string  = "select wk_date ";
    $q_string .= "from st_weeks ";
    $q_string .= "where wk_id = " . $a_st_bandf['bf_week'];
    $q_st_weeks = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
    $a_st_weeks = mysqli_fetch_array($q_st_weeks);

    if (check_userlevel($db, $AL_Admin)) {
      $linkdel = "<a href='#' onClick=\"javascript:delete_item('add.bandf.del.php?id=" . $a_st_bandf['bf_id'] . "');\">";
      $linkstart = "<a href='#' onClick=\"javascript:show_file('add.bandf.fill.php?id=" . $a_st_bandf['bf_id'] . "');\">";
      $linkend = "</a>";
    } else {
      $linkdel = "";
      $linkstart = "";
      $linkend = "";
    }

    $output .= "<tr>";
    $output .= "  <td class=\"ui-widget-content delete\">" . $linkdel . "x" . $linkend . "</td>";
    $output .= "  <td class=\"ui-widget-content\">" . $linkstart . $a_name['usr_name'] . $linkend . "</td>";
    $output .= "  <td class=\"ui-widget-content\">" . $linkstart . $a_st_weeks['wk_date'] . $linkend . "</td>";
    $output .= "  <td class=\"ui-widget-content\">" . $linkstart . mysqli_real_escape_string($db, $a_st_bandf['bf_text']) . $linkend . "</td>";
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
  $q_string .= "from st_bandf ";
  $q_string .= "where bf_status = 0 and bf_borf = 0 ";
  $q_string .= "order by bf_week desc";
  $q_st_bandf = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ($a_st_bandf = mysqli_fetch_array($q_st_bandf)) {

    $q_string  = "select usr_name ";
    $q_string .= "from st_users ";
    $q_string .= "where usr_id = " . $a_st_bandf['bf_name'];
    $q_st_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
    $a_name = mysqli_fetch_array($q_st_users);

    $q_string  = "select usr_name ";
    $q_string .= "from st_users ";
    $q_string .= "where usr_id = " . $a_st_bandf['bf_dev'];
    $q_st_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
    $a_dev = mysqli_fetch_array($q_st_users);

    $dev = $a_dev['usr_name'];
    if ($a_st_bandf['bf_dev'] == 0) {
      $dev = "Unassigned";
    }

    $q_string  = "select wk_date ";
    $q_string .= "from st_weeks ";
    $q_string .= "where wk_id = " . $a_st_bandf['bf_week'];
    $q_st_weeks = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
    $a_st_weeks = mysqli_fetch_array($q_st_weeks);

    if (check_userlevel($db, $AL_Admin)) {
      $linkdel = "<a href=\"#\" onClick=\"javascript:delete_item('add.bandf.del.php?id=" . $a_st_bandf['bf_id'] . "');\">";
      $linkstart = "<a href=\"#\" onClick=\"javascript:show_file('add.bandf.fill.php?id=" . $a_st_bandf['bf_id'] . "');\">";
      $linkend = "</a>";
    } else {
      $linkdel = "";
      $linkstart = "";
      $linkend = "";
    }

    $output .= "<tr>";
    $output .= "  <td class=\"ui-widget-content delete\">" . $linkdel . "x" . $linkend . "</td>";
    $output .= "  <td class=\"ui-widget-content\">" . $linkstart . $a_name['usr_name'] . $linkend . "</td>";
    $output .= "  <td class=\"ui-widget-content\">" . $linkstart . $a_st_weeks['wk_date'] . $linkend . "</td>";
    $output .= "  <td class=\"ui-widget-content\">" . $linkstart . mysqli_real_escape_string($db, $a_st_bandf['bf_text']) . $linkend . "</td>";
    $output .= "  <td class=\"ui-widget-content\">" . $linkstart . $dev . $linkend . "</td>";
    $output .= "</tr>";

  }

  mysqli_free_result($q_st_bandf);

?>

document.getElementById('from_mysql').innerHTML = '<?php print mysqli_real_escape_string($db, $output); ?>';

document.bandf.week['0'].checked = true;
document.bandf.username.value =  0;
document.bandf.bftext.value = '';
document.bandf.borf['0'].checked = true;
document.bandf.developer['0'].selected = true;
document.bandf.completed.checked = false;
document.bandf.id.value = 0;

document.bandf.update.disabled = true;

