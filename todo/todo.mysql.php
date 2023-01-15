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
    $formVars['update']         = clean($_GET['update'],        10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel($db, $AL_User)) {
      $formVars['id']               = clean($_GET['id'],               10);
      $formVars['week']             = clean($_GET['week'],             10);
      $formVars['user']             = clean($_GET['user'],             10);
      $formVars['class']            = clean($_GET['class'],            10);
      $formVars['project']          = clean($_GET['project'],          10);
      $formVars['day']              = clean($_GET['day'],              10);
      $formVars['time']             = clean($_GET['time'],             10);
      $formVars['task']             = clean($_GET['task'],            255);
      $formVars['duedate']          = clean($_GET['duedate'],          10);
      $formVars['completed']        = clean($_GET['completed'],        10);
      $formVars['todo_priority']    = clean($_GET['todo_priority'],    10);
      $formVars['todo_status']      = clean($_GET['todo_status'],      10);
      $formVars['save']             = clean($_GET['save'],             10);
      $formVars['showall']          = clean($_GET['showall'],          10);
      $formVars['showyour']         = clean($_GET['showyour'],         10);
      $formVars['assign']           = clean($_GET['assign'],           10);
      $formVars['group']            = clean($_GET['group'],            10);

      logaccess($db, $_SESSION['username'], "todo.mysql.php", "Accessing script. id:" . $formVars['id'] . ": user=" . $formVars['user'] . " assign=" . $formVars['assign']);

      if (!isset($_GET['week'])) {
        $formVars['week'] = 112;
      }
      if (!isset($_GET['user'])) {
        $formVars['user'] = 1;
      }
      if (!isset($_GET['day'])) {
        $formVars['day'] = 0;
      }
      if (!isset($_GET['day'])) {
        $formVars['day'] = 0;
      }
      if ($formVars['showall'] == "") {
        $formVars['showall'] = "false";
      }
      if ($formVars['showyour'] == "") {
        $formVars['showyour'] = "false";
      }
      if ($formVars['save'] == "true") {
        $formVars['save'] = 1;
      } else {
        $formVars['save'] = 0;
      }
      if ($formVars['todo_priority'] == '') {
        $formVars['todo_priority'] = 0;
      }

      $weekday[0] = "U";
      $weekday[1] = "M";
      $weekday[2] = "T";
      $weekday[3] = "W";
      $weekday[4] = "H";
      $weekday[5] = "F";
      $weekday[6] = "S";

      $query_user = $formVars['user'];

      if ($formVars['assign'] == "") {
        $formVars['assign'] = $formVars['user'];
      }

// If task is empty, no data was passed. Otherwise, save the passed values.
      if (strlen($formVars['task']) > 1) {

        $formVars['showall'] = "false";
        if ($formVars['completed'] == 'true') {
          $formVars['type'] = 3;
          $formVars['progress'] = 1;
          $formVars['quarter'] = 0;

          $formVars['status'] = 1;
          $formVars['done'] = $formVars['week'];
        } else {
          $formVars['type'] = 3;
          $formVars['progress'] = 3;
          $formVars['quarter'] = 0;

          $formVars['status'] = 0;
          $formVars['done'] = 0;
        }

### Send an e-mail if the user is assigning the task to someone else.
        if ($formVars['user'] != $formVars['assign']) {
          $q_string  = "select usr_email ";
          $q_string .= "from st_users ";
          $q_string .= "where usr_id = " . $formVars['assign'];
          $q_st_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
          $a_st_users = mysqli_fetch_array($q_st_users);
          $usermail = $a_st_users['usr_email'];

          logaccess($db, $_SESSION['username'], "todo.mysql.php", "Todo Debug1 " . $formVars['id'] . ": user=" . $formVars['user'] . " assign=" . $formVars['assign']);

          $q_string  = "select usr_first,usr_last ";
          $q_string .= "from st_users ";
          $q_string .= "where usr_id = " . $formVars['user'];
          $q_st_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
          $a_st_users = mysqli_fetch_array($q_st_users);
          $assignedby = $a_st_users['usr_first'] . " " . $a_st_users['usr_last'];

          logaccess($db, $_SESSION['username'], "todo.mysql.php", "Todo Debug2 " . $formVars['id'] . ": user=" . $formVars['user'] . " assign=" . $formVars['assign']);

          $q_string  = "select wk_date ";
          $q_string .= "from st_weeks ";
          $q_string .= "where wk_id = " . $formVars['week'];
          $q_st_weeks = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
          $a_st_weeks = mysqli_fetch_array($q_st_weeks);
          $dueweek = $a_st_weeks['wk_date'];

          logaccess($db, $_SESSION['username'], "todo.mysql.php", "Todo Debug3 " . $formVars['id'] . ": user=" . $formVars['user'] . " assign=" . $formVars['assign']);
          $body  = "<html>";
          $body .= "<body>";
          $body .= "<p>You have been assigned the following task by " . $assignedby . " which is due " . $dueweek . ".</p>";
          $body .= "<p>" . $formVars['task'] . "</p>";
          $body .= "<p><a href=\"todo.php?week=0&user=" . $formVars['assign'] . "\">Click to view your Todo List.</a></p>";
          $body .= "</body>";
          $body .= "</html>";

          $body = wordwrap($body, 70);

          logaccess($db, $_SESSION['username'], "todo.mysql.php", "Todo Debug4 " . $formVars['id'] . ": user=" . $formVars['user'] . " assign=" . $formVars['assign']);
          $headers = 'MIME-Version: 1.0' . "\r\n";
          $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

          logaccess($db, $_SESSION['username'], "todo.mysql.php", "Todo Debug5 " . $formVars['id'] . ": user=" . $formVars['user'] . " assign=" . $formVars['assign']);
          mail($usermail, "Item Added to your Todo List", $body, $headers);

          $query_user = $formVars['assign'];

          logaccess($db, $_SESSION['username'], "todo.mysql.php", "Todo Debug6 " . $formVars['id'] . ": user=" . $formVars['user'] . " assign=" . $formVars['assign']);
        }

        $query_status = "insert into st_status set " .
          "strp_id        = NULL, " .
          "strp_week      = "   . $formVars['week']     . ", " .
          "strp_name      = "   . $formVars['user']     . ", " .
          "strp_class     = "   . $formVars['class']    . ", " .
          "strp_type      = "   . $formVars['type']     . ", " .
          "strp_progress  = "   . $formVars['progress'] . ", " .
          "strp_project   = "   . $formVars['project']  . ", " .
          "strp_day       = "   . $formVars['day']      . ", " .
          "strp_time      = "   . $formVars['time']     . ", " .
          "strp_task      = \"" . $formVars['task']     . "\", " .
          "strp_save      = "   . $formVars['save']     . ", " .
          "strp_quarter   = "   . $formVars['quarter'];

        $query_todo =
          "todo_name      = \"" . $formVars['task']      . "\", " .
          "todo_class     = "   . $formVars['class']     . ", " .
          "todo_project   = "   . $formVars['project']   . ", " .
          "todo_group     = "   . $_SESSION['group']     . ", " .
          "todo_save      = "   . $formVars['save']      . ", " .
          "todo_due       = "   . $formVars['duedate']   . ", " .
          "todo_day       = "   . $formVars['day']       . ", " .
          "todo_time      = "   . $formVars['time']      . ", " .
          "todo_completed = "   . $formVars['done']      . ", " .
          "todo_user      = "   . $query_user            . ", " .
          "todo_priority  = "   . $formVars['todo_priority']  . ", " .
          "todo_status    = "   . $formVars['todo_status'];

      if ($formVars['update'] == 0) {
        $q_string = "insert into todo set todo_id = NULL," . $query_todo;

        $insert = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
        logaccess($db, $_SESSION['username'], "todo.mysql.php", "Adding todo record " . $formVars['id'] . ": week=" . $formVars['week'] . " user=" . $formVars['user'] . " assign=" . $formVars['assign']);
      }
      if ($formVars['update'] == 1) {
        $q_string = "update todo set " . $query_todo . " where todo_id = " . $formVars['id'];

        logaccess($db, $_SESSION['username'], "todo.mysql.php", "Updating todo record " . $formVars['id'] . ": week=" . $formVars['week'] . " user=" . $formVars['user'] . " assign=" . $formVars['assign']);
        $insert = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      }
      if ($formVars['update'] == 2) {
        $q_string = $query_status;

        logaccess($db, $_SESSION['username'], "todo.mysql.php", "Adding status record: week=" . $formVars['week'] . " user=" . $formVars['user'] . " assign=" . $formVars['assign']);
        $insert = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      }
    }
  }

// See if the data was copied to a new week or not.
  if ($formVars['docopy'] == 1) {
    $formVars['week'] = $formVars['week'] - 1;
  }

  $output = "<table class=\"ui-widget-content\">";
  $c_project = "";

  if ($formVars['showall'] == 1) {
    $showall = "";
  } else {
    $showall = "todo_completed = 0 and ";
  }

  if ($formVars['showyour'] == 1) {
    $showyour = "";
  } else {
    $showyour = " or todo_user = 0";
  }

  $q_string  = "select usr_template ";
  $q_string .= "from st_users ";
  $q_string .= "where usr_id = " . $formVars['user'];
  $q_st_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  $a_st_users = mysqli_fetch_array($q_st_users);

  // Retrieve the class headers. Start with 5 as 1-4 are obsolete (but still used)
  $q_string  = "select cls_id,cls_name,cls_project,cls_help ";
  $q_string .= "from st_class ";
  $q_string .= "where cls_template = " . $a_st_users['usr_template'];
  $q_st_class = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ($a_st_class = mysqli_fetch_array($q_st_class)) {
    if ($a_st_class['cls_id'] == $formVars['class']) {
      $checked = " checked";
    } else {
      $checked = "";
    }
    $output .= "<tr>";
    $output .= "<td class=\"delete ui-widget-content\" title=\"Classification. Project names are only displayed in the Projects entry." . $a_st_class['cls_id'] . "\" width=3%>";
    $output .= "<input type=\"radio\"" . $checked . " value=\"" . $a_st_class['cls_id'] . "\" name=\"report\" ";
    $output .= "onclick=\"touch_class();\">";
    $output .= "</td>";

    $output .= "<td class=\"ui-widget-content\" title=\"" . $a_st_class['cls_help'] . "\" id=\"clsready" . $a_st_class['cls_id'] . "\" colspan=2>";
    $output .= "<b>" . $a_st_class['cls_name'] . "</b>";
    $output .= "</td>";
    $output .= "</tr>";

// Retrieve the todo array
    $q_string  = "select todo_id,todo_name,todo_project,todo_save,todo_entered,todo_due,todo_day,";
    $q_string .= "todo_time,todo_completed,todo_user,todo_priority,todo_status ";
    $q_string .= "from todo ";
    $q_string .= "where " . $showall . "(todo_user = " . $formVars['user'] . $showyour . ") ";
    $q_string .= "and todo_group = " . $_SESSION['group'] . " ";
    $q_string .= "and todo_class = " . $a_st_class['cls_id'] . " ";
    $q_string .= "order by todo_project,todo_due,todo_day,todo_status,todo_priority,todo_name ";
    $q_todo = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
    while ($a_todo = mysqli_fetch_assoc($q_todo)) {

// Retreive the project information for this entry
      $q_string  = "select prj_desc ";
      $q_string .= "from st_project ";
      $q_string .= "where prj_id = " . $a_todo['todo_project'];
      $q_st_project = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_st_project = mysqli_fetch_assoc($q_st_project);
      if ($a_st_project['prj_desc'] != $c_project && $a_st_class['cls_project'] == 1) {
        $output .= "<tr>";
        $output .= "<td class=\"delete ui-widget-content\">*</td>";
        $output .= "<td class=\"ui-widget-content\" colspan=2><b>" . $a_st_project['prj_desc'] . "</b></td>";
        $output .= "</tr>";
        $c_project = $a_st_project['prj_desc'];
      }
      mysqli_free_result($q_st_project);

      if ($a_todo['todo_completed'] > 0) {
        $tdclass = "ui-state-highlight";
      } else {
        if ($a_todo['todo_due'] <= $formVars['week']) {
          $tdclass = "ui-state-error";
        } else {
          $tdclass = "ui-widget-content";
        }
      }
      if ($a_todo['todo_user'] == 0) {
        $tdclass = "ui-state-highlight";
        $title = "\"This task is Unassigned and available for work. Modify the todo with your name to claim ownership of the task.\"";
      }

      if ($a_todo['todo_user'] == 0) {
        $grptitle = "group ";
      } else {
        $grptitle = "";
      }
      $title = "\"Edit this " . $grptitle . "task\"";

// Set up the daily check.
      $daily_output = "<tr>";

# delete column
      $daily_output .= "<td class=\"" . $tdclass . " delete\" title=\"Delete this task\">";
      $daily_output .= "<a href=\"#\" onClick=\"javascript:delete_line('del.todo.mysql.php?id=";
      $daily_output .= $a_todo['todo_id'] . "');\">x</a></td>";

# data column
      $daily_output .= "<td class=\"" . $tdclass . "\" title=" . $title . ">";
      $daily_output .= "<a href=\"#\" onclick=\"show_file('todo.fill.php?id=";
      $daily_output .= $a_todo['todo_id'] . "&user=" . $formVars['user'] . "');" . "\">";
      if ($a_todo['todo_status']) {
        $daily_output .= "Desired - ";
      } else {
        $daily_output .= "Required - ";
      }
      $daily_output .= $a_todo['todo_priority'] . " - " . mysqli_real_escape_string($db, $a_todo['todo_name']) . "</a>" . "</td>";

      $q_string  = "select wk_date ";
      $q_string .= "from st_weeks ";
      $q_string .= "where wk_id = " . $a_todo['todo_due'];
      $q_st_weeks = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_st_weeks = mysqli_fetch_assoc($q_st_weeks);

      if ($a_todo['todo_save'] == 1) {
        $tdclass = "ui-widget-content";
      } else {
        $tdclass= "ui-state-highlight";
      }

      $daily_output .= "<td title=\"Week and Day due plus Estimated hours to complete\" class=\"" . $tdclass . "\">";
      $daily_output .= $a_st_weeks['wk_date'] . "&nbsp;" . $weekday[$a_todo['todo_day']] . "&nbsp;";
      $daily_output .= number_format((($a_todo['todo_time'] * 15) / 60), 2, '.', ',') . "</td>";

      $daily_output .= "</tr>";

      $output .= $daily_output;
    }
    mysqli_free_result($q_todo);
  }
}

mysqli_free_result($q_st_class);

$output .= "</table>";

?>

document.getElementById('from_mysql').innerHTML = '<?php print mysqli_real_escape_string($db, $output); ?>';

document.taskmgr.id.value = 0;

document.taskmgr.showall.checked = <?php print $formVars['showall']; ?>;
document.taskmgr.showyour.checked = <?php print $formVars['showyour']; ?>;
document.taskmgr.save.checked = true;
document.taskmgr.completed.checked = false;
document.taskmgr.update.disabled = true;
document.taskmgr.status.disabled = true;

