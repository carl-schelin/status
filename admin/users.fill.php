<?php
# Script: users.fill.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# Description: 

  header('Content-Type: text/javascript');

  include('settings.php');
  $called = 'yes';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');

  if (isset($_SESSION['username'])) {
    $package = "users.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel($db, $AL_Admin)) {
      logaccess($db, $_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from st_users");

      $q_string  = "select usr_id,usr_disabled,usr_first,usr_last,usr_name,usr_level,";
      $q_string .= "usr_phone,usr_email,usr_group,usr_theme,usr_reset,usr_manager,usr_title ";
      $q_string .= "from st_users ";
      $q_string .= "where usr_id = " . $formVars['id'];
      $q_st_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_st_users = mysqli_fetch_array($q_st_users);
      mysqli_free_result($q_st_users);

      $groups   = return_Index($db, $a_st_users['usr_group'],    "select grp_id from st_groups where grp_disabled = 0 order by grp_name");
      $disabled = $a_st_users['usr_disabled'];
      $levels   = return_Index($db, $a_st_users['usr_level'],    "select lvl_id from st_levels where lvl_disabled = 0 order by lvl_id");
      $theme    = return_Index($db, $a_st_users['usr_theme'],    "select theme_id from st_themes order by theme_title") - 1;
      $manager  = return_Index($db, $a_st_users['usr_manager'],  "select usr_id from st_users where usr_disabled = 0 order by usr_last,usr_first");
      $title    = return_Index($db, $a_st_users['usr_title'],    "select tit_id from st_titles order by tit_name");

      print "document.user.usr_name.value = '"       . mysqli_real_escape_string($db, $a_st_users['usr_name'])     . "';\n";
      print "document.user.usr_first.value = '"      . mysqli_real_escape_string($db, $a_st_users['usr_first'])    . "';\n";
      print "document.user.usr_last.value = '"       . mysqli_real_escape_string($db, $a_st_users['usr_last'])     . "';\n";
      print "document.user.usr_email.value = '"      . mysqli_real_escape_string($db, $a_st_users['usr_email'])    . "';\n";
      print "document.user.usr_phone.value = '"      . mysqli_real_escape_string($db, $a_st_users['usr_phone'])    . "';\n";

      print "document.user.usr_group['"    . $groups   . "'].selected = true;\n";
      print "document.user.usr_disabled['" . $disabled . "'].selected = true;\n";
      print "document.user.usr_level['"    . $levels   . "'].selected = true;\n";
      print "document.user.usr_theme['"    . $theme    . "'].selected = true;\n";
      print "document.user.usr_manager['"  . $manager  . "'].selected = true;\n";
      print "document.user.usr_title['"    . $title    . "'].selected = true;\n";

      if ($a_st_users['usr_reset']) {
        print "document.user.usr_reset.checked = true;\n";
      } else {
        print "document.user.usr_reset.checked = false;\n";
      }

      print "document.user.id.value = '" . $formVars['id'] . "'\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
