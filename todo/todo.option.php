<?php
# Script: todo.mysql.php
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
    $package = "todo.mysql.php";
    $formVars['user'] = clean($_GET['user'], 10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel($db, $AL_User)) {

      logaccess($db, $_SESSION['username'], "todo.option.php", "Building a project list: user=" . $formVars['user']);

      $q_string  = "select usr_group ";
      $q_string .= "from users ";
      $q_string .= "where usr_id = " . $formVars['user'];
      $q_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_users = mysqli_fetch_array($q_users);

// Only change the list if the person selected is in a different group. Prevents resetting the project id when you change people.
      if ($a_users['usr_group'] != $_SESSION['group']) {

        print "var selbox = document.taskmgr.project;\n\n";
        print "selbox.options.length = 0;\n";
        print "selbox.options[selbox.options.length] = new Option(\"N/A\",0);\n";

// retrieve project list
        $q_string  = "select prj_id,prj_desc ";
        $q_string .= "from project ";
        $q_string .= "where prj_group = " . $a_users['usr_group'] . " and prj_close = 0 ";
        $q_string .= "order by prj_desc";
        $q_project = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));

// create the javascript bit for populating the project dropdown box.
        while ( $a_project = mysqli_fetch_array($q_project) ) {
          print "selbox.options[selbox.options.length] = new Option(\"" . $a_project['prj_desc'] . "\"," . $a_project['prj_id'] . ");\n";
        }
      }
    }
  }

?>
