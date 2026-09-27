<?php
# Script: tickets.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# Description: Retrieve data and update the database with the new info. Prepare and display the table

  header('Content-Type: text/javascript');

  include('settings.php');
  $called = 'yes';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');

  if (isset($_SESSION['username'])) {
    $package = "tickets.mysql.php";
    $formVars['update'] = clean($_GET['update'], 10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel($db, $AL_Admin)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['id']              = clean($_GET['id'],                10);
        $formVars['tik_number']      = clean($_GET['tik_number'],        60);
        $formVars['tik_task']        = clean($_GET['tik_task'],         255);
        $formVars['tik_priority']    = clean($_GET['tik_priority'],      10);
        $formVars['tik_user']        = $_SESSION['uid'];
        $formVars['tik_closed']      = clean($_GET['tik_closed'],        10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['tik_closed'] == 'true') {
          $formVars['tik_closed'] = 1;
        } else {
          $formVars['tik_closed'] = 0;
        }
    
        if (strlen($formVars['tik_number']) > 0) {
          logaccess($db, $_SESSION['uid'], $package, "Building the query.");

          $q_string =
            "tik_number      = \"" . $formVars['tik_number']     . "\"," .
            "tik_task        = \"" . $formVars['tik_task']       . "\"," .
            "tik_priority    =   " . $formVars['tik_priority']   . "," .
            "tik_user        =   " . $formVars['tik_user']       . "," .
            "tik_closed      =   " . $formVars['tik_closed'];

          if ($formVars['update'] == 0) {
            $query = "insert into st_tickets set tik_id = NULL, " . $q_string;
          }
          if ($formVars['update'] == 1) {
            $query = "update st_tickets set " . $q_string . " where tik_id = " . $formVars['id'];
          }

          logaccess($db, $_SESSION['uid'], $package, "Saving Changes to: " . $formVars['tik_number']);

          mysqli_query($db, $query) or die($query . ": " . mysqli_error($db));
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      logaccess($db, $_SESSION['uid'], $package, "Creating the table for viewing.");

      $priority[0] = "Lowest";
      $priority[1] = "Low";
      $priority[2] = "Low (migrated)";
      $priority[3] = "Medium";
      $priority[4] = "High";
      $priority[5] = "Highest";

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Ticket Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('ticket-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"ticket-listing-help\" style=\"display: none\">\n";

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
        $output .= "  <th class=\"ui-state-default\" width=\"160\">Delete Ticket</th>\n";
      }
      $output .= "  <th class=\"ui-state-default\">Number</th>\n";
      $output .= "  <th class=\"ui-state-default\">Title</th>\n";
      $output .= "  <th class=\"ui-state-default\">Priority</th>\n";
      $output .= "  <th class=\"ui-state-default\">In Use</th>\n";
      $output .= "</tr>\n";

      $class = 'ui-widget-content';

      $q_string  = "select tik_id,tik_number,tik_task,tik_priority ";
      $q_string .= "from st_tickets ";
      $q_string .= "where tik_user = " . $_SESSION['uid'] . " and tik_closed = 0 ";
      $q_string .= "order by tik_number ";
      $q_st_tickets = mysqli_query($db, $q_string) or die($q_string . ": " . mysqli_error($db));
      if (mysqli_num_rows($q_st_tickets) > 0) {
        while ($a_st_tickets = mysqli_fetch_array($q_st_tickets)) {

          $linkstart  = "<a href=\"#\" onclick=\"show_file('tickets.fill.php?id="  . $a_st_tickets['tik_id'] . "');jQuery('#dialogTicket').dialog('open');return false;\">";
          $linkdel    = "<input type=\"button\" value=\"Remove\" onclick=\"delete_ticket('tickets.del.php?id=" . $a_st_tickets['tik_id'] . "');\">";
          $linkmember = "<a href=\"tickets.member.php?id=" . $a_st_tickets['tik_id'] . "\">";
          $linkend    = "</a>";

          $class = 'ui-widget-content';

          $closed = 'No';
          if ($a_st_tickets['tik_closed']) {
            $class = 'ui-status-highlight';
            $closed = 'Yes';
          }

          $total = 0;
          $q_string  = "select strp_id ";
          $q_string .= "from st_status ";
          $q_string .= "where strp_ticket = " . $a_st_tickets['tik_id'] . " ";
          $q_st_status = mysqli_query($db, $q_string) or die($q_string . ": " . mysqli_error($db));
          $total = mysqli_num_rows($q_st_status);

          $output .= "<tr>";
          if (check_userlevel($db, $AL_Developer)) {
            $output .= "  <td class=\"ui-widget-content delete\">" . $linkdel . "</td>";
          }
          $output .= "  <td class=\"" . $class . " delete\">" . $linkstart  . $a_st_tickets['tik_number']               . $linkend . "</td>";
          $output .= "  <td class=\"" . $class . "\">"        . $linkstart  . $a_st_tickets['tik_task']                 . $linkend . "</td>";
          $output .= "  <td class=\"" . $class . " delete\">"               . $priority[$a_st_tickets['tik_priority']]             . "</td>";
          if ($total > 0) {
            $output .= "  <td class=\"" . $class . " delete\">" . $linkmember . $total                                    . $linkend . "</td>";
          } else {
            $output .= "  <td class=\"" . $class . " delete\">"               . $total                                               . "</td>";
          }
          $output .= "</tr>";

        }
      } else {
        $output .= "<tr>";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"5\">No records found.</td>";
        $output .= "</tr>";
      }

      $output .= "</table>";

      mysqli_free_result($q_st_tickets);

      print "document.tickets.tik_number.value = '';\n";
      print "document.tickets.tik_task.value = '';\n";
      print "document.tickets.tik_closed.checked = false;\n";

      print "document.getElementById('ticket_mysql').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n\n";

    } else {
      logaccess($db, $_SESSION['uid'], $package, "Unauthorized access.");
    }
  }
?>
