<?php
# Script: profile.mysql.php
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
    $package = "profile.mysql.php";
    $formVars['id']             = clean($_SESSION['uid'],        10);
    $formVars['update']         = clean($_GET['update'],         10);
    $formVars['usr_first']      = clean($_GET['usr_first'],     255);
    $formVars['usr_last']       = clean($_GET['usr_last'],      255);
    $formVars['usr_email']      = clean($_GET['usr_email'],     255);
    $formVars['usr_manager']    = clean($_GET['usr_manager'],    10);
    $formVars['usr_title']      = clean($_GET['usr_title'],      10);
    $formVars['usr_theme']      = clean($_GET['usr_theme'],      10);
    $formVars['usr_passwd']     = clean($_GET['usr_passwd'],     32);
    $formVars['usr_reenter']    = clean($_GET['usr_reenter'],    32);
    $formVars['usr_reset']      = clean($_GET['usr_reset'],      10);
    $formVars['usr_phone']      = clean($_GET['usr_phone'],      15);

    if ($formVars['id'] == '') {
      $formVars['id'] = 0;
    }
    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }
    if ($formVars['usr_reset'] == 'true') {
      $formVars['usr_reset'] = 1;
    } else {
      $formVars['usr_reset'] = 0;
    }

    if (check_userlevel($AL_User)) {
      if ($formVars['update'] == 1) {
        if (strlen($formVars['usr_last']) > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string = 
            "usr_first       = \"" . $formVars['usr_first']     . "\"," .
            "usr_last        = \"" . $formVars['usr_last']      . "\"," .
            "usr_email       = \"" . $formVars['usr_email']     . "\"," .
            "usr_manager     =   " . $formVars['usr_manager']   . "," .
            "usr_title       =   " . $formVars['usr_title']     . "," .
            "usr_theme       =   " . $formVars['usr_theme']     . "," .
            "usr_reset       =   " . $formVars['usr_reset']     . "," . 
            "usr_phone       = \"" . $formVars['usr_phone']     . "\"";

          if (strlen($formVars['usr_passwd']) > 0 && $formVars['usr_passwd'] === $formVars['usr_reenter']) {
            logaccess($_SESSION['username'], $package, "Resetting user " . $formVars['usr_last'] . " password.");
            $q_string .= ",usr_passwd = '" . MD5($formVars['usr_passwd']) . "' ";
          }

          if ($formVars['update'] == 1) {
            $query = "update users set " . $q_string . " where usr_id = " . $formVars['id'];
            $message = "Account settings updated.";
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['usr_last']);

          mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));

          print "alert('" . $message . "');\n";
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }
    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }

?>
