<?php
# Script: tickets.fill.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# Description:

  header('Content-Type: text/javascript');

  include('settings.php');
  $called = 'yes';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');

  if (isset($_SESSION['username'])) {
    $package = "tickets.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel($db, $AL_Admin)) {
      logaccess($db, $_SESSION['uid'], $package, "Requesting record " . $formVars['id'] . " from st_tickets");

      $q_string  = "select tik_number,tik_task,tik_priority,tik_closed ";
      $q_string .= "from st_tickets ";
      $q_string .= "where tik_id = " . $formVars['id'];
      $q_st_tickets = mysqli_query($db, $q_string) or die (mysqli_error($db));
      $a_st_tickets = mysqli_fetch_array($q_st_tickets);
      mysqli_free_result($q_st_tickets);

      print "document.tickets.tik_number.value = '"    . mysqli_real_escape_string($db, $a_st_tickets['tik_number'])    . "';\n";
      print "document.tickets.tik_task.value = '"      . mysqli_real_escape_string($db, $a_st_tickets['tik_task'])      . "';\n";

      print "document.tickets.tik_priority['" . $a_st_tickets['tik_priority'] . "'].selected = true;\n";

      if ($a_st_tickets['tik_closed']) {
        print "document.tickets.tik_closed.checked = true;\n";
      } else {
        print "document.tickets.tik_closed.checked = false;\n";
      }

      print "document.tickets.id.value = " . $formVars['id'] . ";\n";

    } else {
      logaccess($db, $_SESSION['uid'], $package, "Unauthorized access.");
    }
  }
?>
