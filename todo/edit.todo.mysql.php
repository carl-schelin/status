<?php
# Script: edit.todo.mysql.php
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
    $package = "edit.todo.mysql.php";

    if (check_userlevel($AL_User)) {
      $formVars['id']        = clean($_GET['id'],       10);
      $formVars['task']      = clean($_GET['task'],    255);
      $formVars['class']     = clean($_GET['class'],    10);

      logaccess($_SESSION['username'], "edit.todo.mysql.php", "Editing record " . $formVars['id'] . " in todo");

      $q_string = "update todo set " . 
        "todo_name      = \"" . $formVars['task']      . "\"," . 
        "todo_class     =   " . $formVars['class']     . " " . 
        "where todo_id  =   " . $formVars['id'];

      $insert = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
    }
  }

?>
