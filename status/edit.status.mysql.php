<?php
# Script: edit.status.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# Description: Retrieve data and update the database with the new info. Prepare and display the table

  header('Content-Type: text/javascript');

  include('settings.php');
  $called = 'yes';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');

  if (isset($_SESSION['username'])) {
    $package = "edit.status.mysql.php";
    $formVars['id'] = clean($_GET['id'], 10);

    if (check_userlevel($db, $AL_User)) {
      $formVars['week']     = clean($_GET['week'], 10);
      $formVars['class']    = clean($_GET['class'], 10);
      $formVars['task']     = clean($_GET['task'], 255);

      logaccess($db, $_SESSION['username'], $package, "Editing record " . $formVars['id'] . " in status");

      $query = "update status set " . 
        "strp_week      =   " . $formVars['week']     . ", " . 
        "strp_class     =   " . $formVars['class']    . ", " . 
        "strp_task      = \"" . $formVars['task']     . "\" " . 
        "where strp_id  =   " . $formVars['id'];

      $insert = mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysqli_error($db)));
    }
  }

?>

if (navigator.appName == "Microsoft Internet Explorer") {
  document.getElementById('week_<?php print $formVars['id']; ?>').className = "ui-state-highlight";
  document.getElementById('clas_<?php print $formVars['id']; ?>').className = "ui-state-highlight";
  document.getElementById('task_<?php print $formVars['id']; ?>').className = "ui-state-highlight";
  document.getElementById('save_<?php print $formVars['id']; ?>').className = "ui-state-highlight delete";
  document.getElementById('del_<?php print $formVars['id']; ?>').className = "ui-state-highlight delete";
} else {
  document.getElementById('week_<?php print $formVars['id']; ?>').setAttribute("class","ui-state-highlight");
  document.getElementById('clas_<?php print $formVars['id']; ?>').setAttribute("class","ui-state-highlight");
  document.getElementById('task_<?php print $formVars['id']; ?>').setAttribute("class","ui-state-highlight");
  document.getElementById('save_<?php print $formVars['id']; ?>').setAttribute("class","ui-state-highlight delete");
  document.getElementById('del_<?php print $formVars['id']; ?>').setAttribute("class","ui-state-highlight delete");
}

