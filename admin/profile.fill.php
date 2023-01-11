<?php
# Script: profile.fill.php
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
    $package = "profile.fill.php";
    $formVars['id'] = 0;
    if (isset($_SESSION['uid'])) {
      $formVars['id'] = clean($_SESSION['uid'], 10);
    }

    if (check_userlevel($db, $AL_User)) {
      logaccess($db, $_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from users");

      $q_string  = "select usr_id,usr_first,usr_last,usr_email,usr_phone,usr_theme,usr_reset,usr_manager,usr_title ";
      $q_string .= "from users ";
      $q_string .= "where usr_id = " . $formVars['id'];
      $q_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_users = mysqli_fetch_array($q_users);
      mysqli_free_result($q_users);

      $theme    = return_Index($a_users['usr_theme'],    "select theme_id from st_themes order by theme_title") - 1;
      $manager  = return_Index($a_users['usr_manager'],  "select usr_id from users where usr_disabled = 0 order by usr_last,usr_first");
      $title    = return_Index($a_users['usr_title'],    "select tit_id from st_titles order by tit_name");

      print "document.user.usr_first.value = '"      . mysqli_real_escape_string($db, $a_users['usr_first'])    . "';\n";
      print "document.user.usr_last.value = '"       . mysqli_real_escape_string($db, $a_users['usr_last'])     . "';\n";
      print "document.user.usr_email.value = '"      . mysqli_real_escape_string($db, $a_users['usr_email'])    . "';\n";
      print "document.user.usr_phone.value = '"      . mysqli_real_escape_string($db, $a_users['usr_phone'])    . "';\n";

      print "document.user.usr_theme['"    . $theme     . "'].selected = true;\n";
      print "document.user.usr_manager['"  . $manager   . "'].selected = true;\n";
      print "document.user.usr_title['"    . $title     . "'].selected = true;\n";

      if ($a_users['usr_reset']) {
        print "document.user.usr_reset.checked = true;\n";
      } else {
        print "document.user.usr_reset.checked = false;\n";
      }

      print "document.user.update.disabled = false;\n\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
