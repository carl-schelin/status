<?php
# Script: groups.fill.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# Description: 

  header('Content-Type: text/javascript');

  include('settings.php');
  $called = 'yes';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');

  if (isset($_SESSION['username'])) {
    $package = "groups.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel($db, $AL_Admin)) {
      logaccess($db, $_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from st_groups");

      $q_string  = "select grp_disabled,grp_name,grp_email,grp_manager,grp_report ";
      $q_string .= "from st_groups ";
      $q_string .= "where grp_id = " . $formVars['id'];
      $q_st_groups = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_st_groups = mysqli_fetch_array($q_st_groups);
      mysqli_free_result($q_st_groups);

      $manager      = return_Index($db, $a_st_groups['grp_manager'],      "select usr_id from st_users where usr_disabled = 0 order by usr_last,usr_first");

      print "document.groups.grp_name.value = '"      . mysqli_real_escape_string($db, $a_st_groups['grp_name'])      . "';\n";
      print "document.groups.grp_email.value = '"     . mysqli_real_escape_string($db, $a_st_groups['grp_email'])     . "';\n";
      print "document.groups.grp_report.value = '"    . mysqli_real_escape_string($db, $a_st_groups['grp_report'])    . "';\n";

      print "document.groups.grp_manager['"       . $manager      . "'].selected = true;\n";

      print "document.groups.grp_disabled['" . $a_st_groups['grp_disabled'] . "'].selected = 'true';\n";

      print "document.groups.id.value = " . $formVars['id'] . ";\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
