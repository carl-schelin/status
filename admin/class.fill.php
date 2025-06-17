<?php
# Script: class.fill.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# Description: 

  header('Content-Type: text/javascript');

  include('settings.php');
  $called = 'yes';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');

  if (isset($_SESSION['username'])) {
    $package = "class.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel($db, $AL_Admin)) {
      logaccess($db, $_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from st_class");

      $q_string  = "select cls_name,cls_template,cls_project,cls_title,cls_help ";
      $q_string .= "from st_class ";
      $q_string .= "where cls_id = " . $formVars['id'];
      $q_st_class = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_st_class = mysqli_fetch_array($q_st_class);
      mysqli_free_result($q_st_class);

      print "document.formUpdate.cls_name.value = \""     . $a_st_class['cls_name']       . "\";\n";
      print "document.formUpdate.cls_template.value = \"" . $a_st_class['cls_template']   . "\";\n";
      print "document.formUpdate.cls_project.value = \""  . $a_st_class['cls_project']    . "\";\n";
      print "document.formUpdate.cls_title.value = \""    . $a_st_class['cls_title']      . "\";\n";
      print "document.formUpdate.cls_help.value = \""     . $a_st_class['cls_help']       . "\";\n";

      print "document.formUpdate.id.value = " . $formVars['id'] . ";\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
