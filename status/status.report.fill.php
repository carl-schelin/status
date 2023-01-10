<?php
# Script: status.report.fill.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
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
      logaccess($db, $_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from todo");

// id of the record being pulled from the database.
      $formVars['user'] = clean($_GET['user'], 10);

// Now get the correct number of classes.
      $q_string  = "select usr_template,usr_projects ";
      $q_string .= "from users ";
      $q_string .= "where usr_id = " . $formVars['user'];
      $q_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_users = mysqli_fetch_array($q_users);

      $q_string  = "select cls_id ";
      $q_string .= "from class ";
      $q_string .= "where cls_template = " . $a_users['usr_template'];
      $q_class = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));;
      $a_class = mysqli_fetch_array($q_class);

      $class = $a_class['cls_id'];

// Retrieve the task array
      $q_string  = "select * ";
      $q_string .= "from status ";
      $q_string .= "where strp_id = " . $formVars['id'];
      $q_status = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_status = mysqli_fetch_array($q_status);

      mysqli_free_result($q_status);

// Retrieve the projects in the same order as the main page to identify which needs to be set as true
      $project = 0;
      $count = 1;

      $q_string  = "select prj_id ";
      $q_string .= "from project ";
      $q_string .= "where prj_group = " . $_SESSION['group'] . " and prj_close = 0 ";
      $q_string .= "order by prj_desc";
      $q_project = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      while ( $a_project = mysqli_fetch_array($q_project) ) {
        if (strlen($a_users['usr_projects']) == 0) {
          if ($a_project['prj_id'] == $a_status['strp_project']) {
            $project = $count;
          } else {
            $count++;
          }
        } else {
          $projectid = "/:" . $a_project['prj_id'] . ":/i";
          if (preg_match($projectid, $a_users['usr_projects'])) {
            if ($a_project['prj_id'] == $a_status['strp_project']) {
              $project = $count;
            } else {
              $count++;
            }
          }
        }
      }
    }
  }

?>

document.taskmgr.project['<?php print $project; ?>'].selected = true;
document.taskmgr.report['<?php print $a_status['strp_class'] - $class; ?>'].checked = true;
document.taskmgr.progress['<?php print $a_status['strp_progress']; ?>'].selected = true;
document.taskmgr.tcktype['<?php print $a_status['strp_type']; ?>'].selected = true;
document.taskmgr.day['<?php print $a_status['strp_day']; ?>'].checked = true;
document.taskmgr.task.value = "<?php print mysqli_real_escape_string($db, $a_status['strp_task']); ?>";
document.taskmgr.save.checked = <?php if ($a_status['strp_save']) { print "true"; } else { print "false"; }; ?>;
document.taskmgr.quarter.checked = <?php if ($a_status['strp_quarter']) { print "true"; } else { print "false"; }; ?>;
document.taskmgr.time.value = <?php print $a_status['strp_time']; ?>;
document.taskmgr.id.value = <?php print $a_status['strp_id']; ?>;
document.taskmgr.update.disabled = false;
document.taskmgr.copy.disabled = false;

