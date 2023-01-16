<?php
# Script: userstories.fill.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# Description:

  header('Content-Type: text/javascript');

  include('settings.php');
  $called = 'yes';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');

  if (isset($_SESSION['username'])) {
    $package = "userstories.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel($db, $AL_Admin)) {
      logaccess($db, $_SESSION['uid'], $package, "Requesting record " . $formVars['id'] . " from st_userstories");

      $q_string  = "select user_epic,user_jira,user_task,user_closed ";
      $q_string .= "from st_userstories ";
      $q_string .= "where user_id = " . $formVars['id'];
      $q_st_userstories = mysqli_query($db, $q_string) or die (mysqli_error($db));
      $a_st_userstories = mysqli_fetch_array($q_st_userstories);
      mysqli_free_result($q_st_userstories);

      $epic = return_Index($db, $a_st_userstories['user_epic'], "select epic_id from epics where epic_user = " . $_SESSION['uid'] . " order by epic_jira");

      print "document.userstories.user_jira.value = '"      . mysqli_real_escape_string($db, $a_st_userstories['user_jira'])      . "';\n";
      print "document.userstories.user_task.value = '"      . mysqli_real_escape_string($db, $a_st_userstories['user_task'])      . "';\n";

      print "document.userstories.user_epic['" . $epic . "'].selected = true;\n";

      if ($a_st_userstories['user_closed']) {
        print "document.userstories.user_closed.checked = true;\n";
      } else {
        print "document.userstories.user_closed.checked = false;\n";
      }

      print "document.userstories.id.value = " . $formVars['id'] . ";\n";

    } else {
      logaccess($db, $_SESSION['uid'], $package, "Unauthorized access.");
    }
  }
?>
