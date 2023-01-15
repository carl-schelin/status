<?php
# Script: status.report.options.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
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

    if (check_userlevel($db, $AL_User)) {
      logaccess($db, $_SESSION['uid'], $package, "Building the Epic User Stories list: type=" . $formVars['epic_id']);

      print "var selbox = document.taskmgr.user_jira;\n\n";
      print "selbox.options.length = 0;\n";

      $q_string  = "select user_id,user_jira,user_task ";
      $q_string .= "from st_userstories ";
      $q_string .= "where user_user = " . $_SESSION['uid'] . " and user_epic = " . $formVars['epic_id'] . " ";
      $q_string .= "order by user_jira ";
      $q_st_userstories = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_st_userstories) > 0) {
        while ($a_st_userstories = mysqli_fetch_array($q_st_userstories) ) {
          print "selbox.options[selbox.options.length] = new Option(\"" . htmlspecialchars($a_st_userstories['user_jira']) . " - " . htmlspecialchars($a_st_userstories['user_task']) . "\"," . $a_st_userstories['user_id'] . ");\n";
        }
      } else {
        print "selbox.options[selbox.options.length] = new Option(\"No User Stories have been created for this Epic\",0);\n";
      }
    } else {
      logaccess($db, $_SESSION['uid'], $package, "Access denied");
    }
  }

?>
