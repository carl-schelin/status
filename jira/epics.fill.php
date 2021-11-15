<?php
# Script: epics.fill.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  header('Content-Type: text/javascript');

  include('settings.php');
  $called = 'yes';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');

  if (isset($_SESSION['username'])) {
    $package = "epics.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel(2)) {
      logaccess($_SESSION['uid'], $package, "Requesting record " . $formVars['id'] . " from epics");

      $q_string  = "select epic_jira,epic_title,epic_closed ";
      $q_string .= "from epics ";
      $q_string .= "where epic_id = " . $formVars['id'];
      $q_epics = mysql_query($q_string) or die (mysql_error());
      $a_epics = mysql_fetch_array($q_epics);
      mysql_free_result($q_epics);

      print "document.epics.epic_jira.value = '"       . mysql_real_escape_string($a_epics['epic_jira'])       . "';\n";
      print "document.epics.epic_title.value = '"      . mysql_real_escape_string($a_epics['epic_title'])      . "';\n";

      if ($a_epics['epic_closed']) {
        print "document.epics.epic_closed.checked = true;\n";
      } else {
        print "document.epics.epic_closed.checked = false;\n";
      }

      print "document.epics.id.value = " . $formVars['id'] . ";\n";

    } else {
      logaccess($_SESSION['uid'], $package, "Unauthorized access.");
    }
  }
?>
