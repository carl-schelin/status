<?php
# Script: status.report.fill.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# Description:

  header('Content-Type: text/javascript');

  include('settings.php');
  $called = 'yes';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');

  if (isset($_SESSION['username'])) {
    $package = "status.report.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel($db, $AL_User)) {
      logaccess($db, $_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from st_users");

// id of the record being pulled from the database.
      $formVars['user'] = clean($_GET['user'], 10);

// Now get the correct number of classes.
      $q_string  = "select usr_template,usr_projects ";
      $q_string .= "from st_users ";
      $q_string .= "where usr_id = " . $formVars['user'];
      $q_st_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_st_users = mysqli_fetch_array($q_st_users);

      $q_string  = "select cls_id ";
      $q_string .= "from st_class ";
      $q_string .= "where cls_template = " . $a_st_users['usr_template'];
      $q_st_class = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));;
      $a_st_class = mysqli_fetch_array($q_st_class);

      $class = $a_st_class['cls_id'];

// Retrieve the task array
      $q_string  = "select strp_id,strp_ticket,strp_jira,strp_project,strp_class,strp_progress,strp_type,strp_day,strp_task,strp_save,strp_quarter,strp_time ";
      $q_string .= "from st_status ";
      $q_string .= "where strp_id = " . $formVars['id'];
      $q_st_status = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_st_status = mysqli_fetch_array($q_st_status);

      mysqli_free_result($q_st_status);

      $ticket = return_Index($db, $a_st_status['strp_ticket'], "select tik_id from st_tickets where tik_user = " . $formVars['user'] . " and tik_closed = 0");

      if ($ticket > 0) {
        $ticket--;
      }

      $epic = return_Index($db, $a_st_status['strp_jira'], "select epic_id from st_epics where epic_user = " . $formVars['user'] . " and epic_closed = 0");

#      if ($epic > 0) {
#        $epic--;
#      }

// Retrieve the projects in the same order as the main page to identify which needs to be set as true
      $project = 0;
      $count = 1;

      $q_string  = "select prj_id ";
      $q_string .= "from st_project ";
      $q_string .= "where prj_group = " . $_SESSION['group'] . " and prj_close = 0 ";
      $q_string .= "order by prj_desc";
      $q_st_project = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      while ( $a_st_project = mysqli_fetch_array($q_st_project) ) {
        if (strlen($a_st_users['usr_projects']) == 0) {
          if ($a_st_project['prj_id'] == $a_st_status['strp_project']) {
            $project = $count;
          } else {
            $count++;
          }
        } else {
          $projectid = "/:" . $a_st_project['prj_id'] . ":/i";
          if (preg_match($projectid, $a_st_users['usr_projects'])) {
            if ($a_st_project['prj_id'] == $a_st_status['strp_project']) {
              $project = $count;
            } else {
              $count++;
            }
          }
        }
      }
    }
  }


  print "document.taskmgr.project['"        . $project                               . "'].selected = true;\n";
  print "document.taskmgr.report['"         . $a_st_status['strp_class'] - $class    . "'].checked = true;\n";
  print "document.taskmgr.progress['"       . $a_st_status['strp_progress']          . "'].selected = true;\n";
  print "document.taskmgr.strp_ticket['"    . $ticket                                . "'].selected = true;\n";
  print "document.taskmgr.epic_jira['"      . $epic                                  . "'].selected = true;\n";
  print "document.taskmgr.tcktype['"        . $a_st_status['strp_type']              . "'].selected = true;\n";
  print "document.taskmgr.day['"            . $a_st_status['strp_day']               . "'].checked = true;\n";

  print "document.taskmgr.task.value = \"" . mysqli_real_escape_string($db, $a_st_status['strp_task']) . "\";\n";

  if ($a_st_status['strp_save']) {
    print "document.taskmgr.save.checked = true;\n";
  } else {
    print "document.taskmgr.save.checked = false;\n";
  }

  if ($a_st_status['strp_quarter']) {
    print "document.taskmgr.quarter.checked = true;\n";
  } else {
    print "document.taskmgr.quarter.checked = false;\n";
  }

  print "document.taskmgr.time.value = "   . $a_st_status['strp_time'] . ";\n";
  print "document.taskmgr.id.value = "     . $a_st_status['strp_id']   . ";\n";

  print "document.taskmgr.update.disabled = false;\n";
  print "document.taskmgr.copy.disabled = false;\n";

?>
