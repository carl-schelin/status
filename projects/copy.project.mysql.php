<?php
# Script: copy.project.mysql.php
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
    $package = "copy.project.mysql.php";
    $formVars['id'] = clean($_GET['id'],        10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel($AL_User)) {

      $formVars['group'] = clean($_GET['group'], 10);

      logaccess($_SESSION['username'], "copy.project.mysql.php", "Copying record " . $formVars['id'] . " to " . $formVars['group'] . " in project");

// Retrieve the selected project
      $q_string  = "select prj_name,prj_code,prj_snow,prj_task,prj_desc ";
      $q_string .= "from project ";
      $q_string .= "where prj_id = " . $formVars['id'];
      $q_project = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_project = mysql_fetch_array($q_project);

// Once the project is captured, save it as a new element.

      $q_string = "insert into project set " . 
        "prj_id    = NULL, " . 
        "prj_name  = \"" . $a_project['prj_name'] . "\", " . 
        "prj_code  = "   . $a_project['prj_code'] . ", "  . 
        "prj_snow  = \"" . $a_project['prj_snow'] . "\", " . 
        "prj_task  = \"" . $a_project['prj_task'] . "\", " . 
        "prj_desc  = \"" . $a_project['prj_desc'] . "\", " . 
        "prj_group = "   . $formVars['group'];

      $insert = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
    }
  }

?>

if (navigator.appName == "Microsoft Internet Explorer") {
  document.getElementById('name_<?php print $formVars['id']; ?>').className = "ui-state-highlight";
  document.getElementById('proj_<?php print $formVars['id']; ?>').className = "ui-state-highlight";
  document.getElementById('snow_<?php print $formVars['id']; ?>').className = "ui-state-highlight";
  document.getElementById('task_<?php print $formVars['id']; ?>').className = "ui-state-highlight";
  document.getElementById('desc_<?php print $formVars['id']; ?>').className = "ui-state-highlight";
  document.getElementById('copy_<?php print $formVars['id']; ?>').className = "ui-state-highlight delete";
} else {
  document.getElementById('name_<?php print $formVars['id']; ?>').setAttribute("class","ui-state-highlight");
  document.getElementById('proj_<?php print $formVars['id']; ?>').setAttribute("class","ui-state-highlight");
  document.getElementById('snow_<?php print $formVars['id']; ?>').setAttribute("class","ui-state-highlight");
  document.getElementById('task_<?php print $formVars['id']; ?>').setAttribute("class","ui-state-highlight");
  document.getElementById('desc_<?php print $formVars['id']; ?>').setAttribute("class","ui-state-highlight");
  document.getElementById('copy_<?php print $formVars['id']; ?>').setAttribute("class","ui-state-highlight delete");
}

