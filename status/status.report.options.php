<?php
# Script: status.report.options.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: Building a hardware list of a selected type

  header('Content-Type: text/javascript');

  include ('settings.php');
  $called="yes";
  include ($Loginpath . '/check.php');
  include ($Sitepath . '/function.php');

 if (isset($_SESSION['username'])) {
    $package = "status.report.options.php";
    $formVars['epic_id'] = 0;
    if (isset($_GET['epic_id'])) {
      $formVars['epic_id'] = clean($_GET['epic_id'], 10);
    }

    if (check_userlevel($AL_User)) {
      logaccess($_SESSION['uid'], $package, "Building the Epic User Stories list: type=" . $formVars['epic_id']);

      print "var selbox = document.taskmgr.user_jira;\n\n";
      print "selbox.options.length = 0;\n";

      $q_string  = "select user_id,user_jira,user_task ";
      $q_string .= "from userstories ";
      $q_string .= "where user_user = " . $_SESSION['uid'] . " and user_epic = " . $formVars['epic_id'] . " ";
      $q_string .= "order by user_jira ";
      $q_userstories = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_userstories) > 0) {
        while ($a_userstories = mysql_fetch_array($q_userstories) ) {
          print "selbox.options[selbox.options.length] = new Option(\"" . htmlspecialchars($a_userstories['user_jira']) . " - " . htmlspecialchars($a_userstories['user_task']) . "\"," . $a_userstories['user_id'] . ");\n";
        }
      } else {
        print "selbox.options[selbox.options.length] = new Option(\"No User Stories have been created for this Epic\",0);\n";
      }
    } else {
      logaccess($_SESSION['uid'], $package, "Access denied");
    }
  }

?>
