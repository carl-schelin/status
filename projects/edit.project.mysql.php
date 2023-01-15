<?php
# Script: edit.project.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# Description: Retrieve data and update the database with the new info. Prepare and display the table

  header('Content-Type: text/javascript');

  include('settings.php');
  $called = 'yes';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');

  if (isset($_SESSION['username'])) {
    $package = "edit.project.mysql.php";
    $formVars['update']         = clean($_GET['update'],        10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel($db, $AL_User)) {
      $formVars['id']       = clean($_GET['id'], 10);
      $formVars['name']     = clean($_GET['name'], 255);
      $formVars['code']     = clean($_GET['code'], 10);
      $formVars['snow']     = clean($_GET['snow'], 30);
      $formVars['task']     = clean($_GET['task'], 30);
      $formVars['desc']     = clean($_GET['desc'], 100);
      $formVars['personal'] = clean($_GET['personal'], 10);
      $formVars['close']    = clean($_GET['close'], 10);

      if ($formVars['close'] == "true") {
        $formVars['close'] = 1;
      } else {
        $formVars['close'] = 0;
      }

# we need to read the existing usr_projects variable, break it apart into unique strings, 
# compare the output to the project variable. If true, put it in the string. Otherwise don't

      $q_string  = "select usr_projects ";
      $q_string .= "from st_users ";
      $q_string .= "where usr_id = " . $_SESSION['uid'];
      $q_st_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_st_users = mysqli_fetch_array($q_st_users);

      $new_projects = '';
      $matches[0] = '';
      $q_string  = "select prj_id ";
      $q_string .= "from st_project ";
      $q_string .= "where prj_group = " . $_SESSION['group'] . " ";
      $q_string .= "order by prj_id";
      $q_st_project = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      while ($a_st_project = mysqli_fetch_array($q_st_project)) {

# regex check
        $projectid = "/:" . $a_st_project['prj_id'] . ":/i";
        preg_match($projectid, $a_st_users['usr_projects'], $matches);

# if the retrieved $a_st_project['prj_id'] exists in $a_st_users['usr_projects']
#  check the $formVars['project'] and save it in $new_projects if it's true

        if ($matches[0] == ":" . $a_st_project['prj_id'] . ":") {
          if ($formVars['id'] == $a_st_project['prj_id']) {
            logaccess($db, $_SESSION['username'], "edit.project.mysql.php", "record " . $formVars['personal']);
            if ($formVars['personal'] == "true") {
              $new_projects .= ":" . $a_st_project['prj_id'] . ":";
            }
          } else {
            $new_projects .= ":" . $a_st_project['prj_id'] . ":";
          }
        } else {

# if $a_st_project['prj_id'] is not in $a_st_users['usr_projects']
# if we've reached the same project id that was passed
# if the variable is set to true, then add it to the listing
          if ($formVars['id'] == $a_st_project['prj_id']) {
            if ($formVars['personal'] == "true") {
              $new_projects .= ":" . $a_st_project['prj_id'] . ":";
            }
          }
        }
      }

      $q_string  = "update st_users set ";
      $q_string .= "usr_projects = \"" . $new_projects . "\" ";
      $q_string .= "where usr_id = " . $_SESSION['uid'];
      $insert = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));

      logaccess($db, $_SESSION['username'], "edit.project.mysql.php", "Editing record " . $formVars['id'] . " in project");

      $query = "update st_project set " . 
        "prj_name      = \"" . $formVars['name']  . "\", " . 
        "prj_code      =   " . $formVars['code']  . ", " . 
        "prj_snow      = \"" . $formVars['snow']  . "\", " . 
        "prj_task      = \"" . $formVars['task']  . "\", " . 
        "prj_desc      = \"" . $formVars['desc']  . "\", " . 
        "prj_close     =   " . $formVars['close'] . " " . 
        "where prj_id  =   " . $formVars['id'];

      $insert = mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysqli_error($db)));
    }
  }

?>

if (navigator.appName == "Microsoft Internet Explorer") {
  document.getElementById('name_<?php print $formVars['id']; ?>').className = "ui-state-highlight";
  document.getElementById('proj_<?php print $formVars['id']; ?>').className = "ui-state-highlight";
  document.getElementById('snow_<?php print $formVars['id']; ?>').className = "ui-state-highlight";
  document.getElementById('task_<?php print $formVars['id']; ?>').className = "ui-state-highlight";
  document.getElementById('desc_<?php print $formVars['id']; ?>').className = "ui-state-highlight";
  document.getElementById('pers_<?php print $formVars['id']; ?>').className = "ui-state-highlight delete";
  document.getElementById('clos_<?php print $formVars['id']; ?>').className = "ui-state-highlight delete";
  document.getElementById('save_<?php print $formVars['id']; ?>').className = "ui-state-highlight delete";
} else {
  document.getElementById('name_<?php print $formVars['id']; ?>').setAttribute("class","ui-state-highlight");
  document.getElementById('proj_<?php print $formVars['id']; ?>').setAttribute("class","ui-state-highlight");
  document.getElementById('snow_<?php print $formVars['id']; ?>').setAttribute("class","ui-state-highlight");
  document.getElementById('task_<?php print $formVars['id']; ?>').setAttribute("class","ui-state-highlight");
  document.getElementById('desc_<?php print $formVars['id']; ?>').setAttribute("class","ui-state-highlight");
  document.getElementById('pers_<?php print $formVars['id']; ?>').setAttribute("class","ui-state-highlight delete");
  document.getElementById('clos_<?php print $formVars['id']; ?>').setAttribute("class","ui-state-highlight delete");
  document.getElementById('save_<?php print $formVars['id']; ?>').setAttribute("class","ui-state-highlight delete");
}

