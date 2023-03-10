<?php
# Script: del.todo.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# Description: 

  header('Content-Type: text/javascript');

  include('settings.php');
  $called = 'yes';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');

  if (isset($_SESSION['username'])) {
    $package = "del.todo.mysql.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel($db, $AL_User)) {
      logaccess($db, $_SESSION['username'], $package, "Deleting " . $formVars['id'] . " from st_todo");

      $q_string  = "delete ";
      $q_string .= "from st_todo ";
      $q_string .= "where todo_id = " . $formVars['id'];
      $insert = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));

      print "alert('Todo deleted.');\n";

      print "clear_fields();\n";
    } else {
      logaccess($db, $_SESSION['username'], $package, "Access denied");
    }
  }
?>

if (navigator.appName == "Microsoft Internet Explorer") {

  document.getElementById('task_<?php print $formVars['id']; ?>').className = "deleted";
  document.getElementById('proj_<?php print $formVars['id']; ?>').className = "deleted";
  document.getElementById('due_<?php print $formVars['id']; ?>').className = "deleted";
  document.getElementById('done_<?php print $formVars['id']; ?>').className = "deleted";
  document.getElementById('del_<?php print $formVars['id']; ?>').className = "deleted";
} else {
  document.getElementById('task_<?php print $formVars['id']; ?>').setAttribute("class","deleted");
  document.getElementById('proj_<?php print $formVars['id']; ?>').setAttribute("class","deleted");
  document.getElementById('due_<?php print $formVars['id']; ?>').setAttribute("class","deleted");
  document.getElementById('done_<?php print $formVars['id']; ?>').setAttribute("class","deleted");
  document.getElementById('del_<?php print $formVars['id']; ?>').setAttribute("class","deleted");
}

