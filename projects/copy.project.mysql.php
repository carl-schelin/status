<?php
# Script: copy.project.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
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

    if (check_userlevel($db, $AL_User)) {

      $formVars['group'] = clean($_GET['group'], 10);

      logaccess($db, $_SESSION['username'], "copy.project.mysql.php", "Copying record " . $formVars['id'] . " to " . $formVars['group'] . " in st_project");

// Retrieve the selected project
      $q_string  = "select prj_name,prj_code,prj_snow,prj_task,prj_desc ";
      $q_string .= "from st_project ";
      $q_string .= "where prj_id = " . $formVars['id'];
      $q_st_project = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_st_project = mysqli_fetch_array($q_st_project);

// Once the project is captured, save it as a new element.

      $q_string = "insert into st_project set " . 
        "prj_id    = NULL, " . 
        "prj_name  = \"" . $a_st_project['prj_name'] . "\", " . 
        "prj_code  = "   . $a_st_project['prj_code'] . ", "  . 
        "prj_snow  = \"" . $a_st_project['prj_snow'] . "\", " . 
        "prj_task  = \"" . $a_st_project['prj_task'] . "\", " . 
        "prj_desc  = \"" . $a_st_project['prj_desc'] . "\", " . 
        "prj_group = "   . $formVars['group'];

      $insert = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
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

