<?php
# Script: type.fill.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# Description: 

  header('Content-Type: text/javascript');

  include('settings.php');
  $called = 'yes';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');

  if (isset($_SESSION['username'])) {
    $package = "type.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel($db, $AL_Admin)) {
      logaccess($db, $_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from st_type");

      $q_string  = "select typ_name,typ_desc ";
      $q_string .= "from st_type ";
      $q_string .= "where typ_id = " . $formVars['id'];
      $q_st_type = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_st_type = mysqli_fetch_array($q_st_type);
      mysqli_free_result($q_st_type);

      print "document.formUpdate.typ_name.value = \"" . $a_st_type['typ_name']   . "\";\n";
      print "document.formUpdate.typ_desc.value = \"" . $a_st_type['typ_desc']   . "\";\n";

      print "document.formUpdate.id.value = " . $formVars['id'] . ";\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
