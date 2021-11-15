<?php
# Script: del.status.mysql.php
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
    $package = "del.status.mysql.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel($AL_User)) {
      logaccess($_SESSION['username'], $package, "Deleting " . $formVars['id'] . " from status");

      $q_string  = "delete ";
      $q_string .= "from status ";
      $q_string .= "where strp_id = " . $formVars['id'];
      $insert = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

      print "alert('Status Item deleted.');\n";

      print "clear_fields();\n";
    } else {
      logaccess($_SESSION['username'], $package, "Access denied");
    }
  }
?>
