<?php
# Script: status.report.mysql.php
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
    $package = "status.report.mysql.php";
    $formVars['update']         = clean($_GET['update'],        10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel($AL_User)) {
      $formVars['id']       = clean($_GET['id'], 10);
      $formVars['week']     = clean($_GET['startweek'], 10);
      $formVars['user']     = clean($_GET['user'], 10);
      $formVars['user_jira']  = clean($_GET['user_jira'], 10);
      $formVars['class']    = clean($_GET['class'], 10);
      $formVars['type']     = clean($_GET['type'], 10);
      $formVars['progress'] = clean($_GET['progress'], 10);
      $formVars['project']  = clean($_GET['project'], 10);
      $formVars['day']      = clean($_GET['day'], 10);
      $formVars['time']     = clean($_GET['time'], 10);
      $formVars['task']     = clean($_GET['task'], 255);
      $formVars['save']     = clean($_GET['save'], 10);
      $formVars['quarter']  = clean($_GET['quarter'], 10);
      $formVars['update']   = clean($_GET['update'], 10);
      $formVars['daily']    = clean($_GET['daily'], 10);
      $formVars['docopy']   = clean($_GET['docopy'], 10);

      $weekday[0] = "U";
      $weekday[1] = "M";
      $weekday[2] = "T";
      $weekday[3] = "W";
      $weekday[4] = "H";
      $weekday[5] = "F";
      $weekday[6] = "S";
      $weektot[0] = 0;
      $weektot[1] = 0;
      $weektot[2] = 0;
      $weektot[3] = 0;
      $weektot[4] = 0;
      $weektot[5] = 0;
      $weektot[6] = 0;

      if (!isset($_GET['startweek'])) {
        $formVars['week'] = 112;
      }
      if (!isset($_GET['user'])) {
        $formVars['user'] = 1;
      }
      if (!isset($_GET['day'])) {
        $formVars['day'] = 0;
      }
      if (!isset($_GET['daily'])) {
        $formVars['daily'] = 10;
      }

# figure out the yearmon variable in order to track things for a specific month.

      $q_string  = "select wk_id,wk_date ";
      $q_string .= "from weeks where wk_id = " . $formVars['week'];
      $q_weeks = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_weeks = mysql_fetch_array($q_weeks);

      $ym_date = explode("-", $a_weeks['wk_date']);
      $ym_convert = (5 - $formVars['day']) * 86400;
      $finaldate = date('Ym', mktime(0, 0, 0, $ym_date[1], $ym_date[2], $ym_date[0]) - $ym_convert);

// If task is empty, no data was passed. Otherwise, save the passed values.
      if (strlen($formVars['task']) > 1) {

        $q_string = 
          "strp_week      = "   . $formVars['week']     . "," . 
          "strp_name      = "   . $formVars['user']     . "," . 
          "strp_jira      = "   . $formVars['user_jira']     . "," . 
          "strp_class     = "   . $formVars['class']    . "," . 
          "strp_type      = "   . $formVars['type']     . "," . 
          "strp_progress  = "   . $formVars['progress'] . "," . 
          "strp_project   = "   . $formVars['project']  . "," . 
          "strp_day       = "   . $formVars['day']      . "," . 
          "strp_time      = "   . $formVars['time']     . "," . 
          "strp_task      = \"" . $formVars['task']     . "\"," .
          "strp_save      = "   . $formVars['save']     . "," .
          "strp_quarter   = "   . $formVars['quarter']  . "," .
          "strp_yearmon   = "   . $finaldate;

        if ($formVars['update'] == 1) {
          $query = "update status set " .$q_string . " where strp_id = " . $formVars['id'];
          logaccess($_SESSION['username'], "status.report.mysql.php", "Updating status record " . $formVars['id'] . ": week=" . $formVars['week'] . " user=" . $formVars['user']);
        } else {
          $query = "insert into status set strp_id = NULL," . $q_string;
          logaccess($_SESSION['username'], "status.report.mysql.php", "Adding status record: week=" . $formVars['week'] . " user=" . $formVars['user']);

        }

        $insert = mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));
      }
    }

  }

// See if the data was copied to a new week or not.
  if ($formVars['docopy'] == 1) {
    $formVars['week'] = $formVars['week'] - 1;
  }

  $total = 0;
  $output = "<table class=\"ui-widget-content\">";
  $c_project = "";

  $q_string  = "select usr_template ";
  $q_string .= "from users ";
  $q_string .= "where usr_id = " . $formVars['user'];
  $q_users = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  $a_users = mysql_fetch_array($q_users);

// Retrieve the class headers. Start with 5 as 1-4 are obsolete (but still used)
  $q_string  = "select cls_id,cls_name,cls_project,cls_help ";
  $q_string .= "from class ";
  $q_string .= "where cls_template = " . $a_users['usr_template'];
  $q_class = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_class = mysql_fetch_array($q_class)) {
    if ($a_class['cls_id'] == $formVars['class']) {
      $checked = " checked";
    } else {
      $checked = "";
    }
    $output .= "<tr>";
    $output .= "  <td class=\"ui-widget-content delete\" title=\"Classification. Project names are only displayed in the Projects entry." . $a_class['cls_id'] . "\" width=3%>";
    $output .= "<input type=\"radio\"" . $checked . " value=\"" . $a_class['cls_id'] . "\" name=\"report\" ";
    $output .= "onclick=\"touch_class();\">";
    $output .= "</td>";

    $output .= "<td class=\"ui-widget-content\" title=\"" . $a_class['cls_help'] . "\" id=\"clsready" . $a_class['cls_id'] . "\" colspan=2>";
    $output .= "<b>" . $a_class['cls_name'] . "</b>";
    $output .= "</td>";
    $output .= "</tr>";

// Retrieve the task array
    $q_string  = "select strp_id,strp_jira,strp_task,strp_progress,strp_project,strp_day,strp_time,strp_save,strp_quarter ";
    $q_string .= "from status ";
    $q_string .= "where strp_name = " . $formVars['user'] . " and strp_class = " . $a_class['cls_id'] . " and strp_week = " . $formVars['week'] . " ";
    $q_string .= "order by strp_project,strp_day ";
    $q_task = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
    while ($a_task = mysql_fetch_assoc($q_task)) {

      if ($a_task['strp_save']) {
        $ready = "class=\"ui-state-highlight\"";
        $readydel = "class=\"ui-state-highlight delete\"";
      } else {
        $ready = "class=\"ui-widget-content\"";
        $readydel = "class=\"ui-widget-content delete\"";
      }

// Retreive the project information for this entry
      $q_string  = "select prj_desc ";
      $q_string .= "from project ";
      $q_string .= "where prj_id = " . $a_task['strp_project'];
      $q_project = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_project = mysql_fetch_assoc($q_project);
      if ($a_project['prj_desc'] != $c_project && $a_class['cls_project'] == 1) {
        $output .= "<tr>";
        $output .= "<td class=\"ui-widget-content delete\">*</td>";
        $output .= "<td class=\"ui-widget-content\" colspan=\"2\"><b>" . $a_project['prj_desc'] . "</b></td>";
        $output .= "</tr>";
        $c_project = $a_project['prj_desc'];
      }
      mysql_free_result($q_project);

// Set up the daily check.
      $daily_output = "<tr>";
      $daily_output .= "<td " . $readydel . " title=\"Delete this task\">";
      $daily_output .= "<a href=\"#\" onClick=\"javascript:delete_line('del.status.mysql.php?id=";
      $daily_output .= $a_task['strp_id'] . "');\">x</a></td><td " . $ready . " title=\"Edit this task\">";

// Retrieve the status of the task for this entry
      $q_string  = "select pro_name ";
      $q_string .= "from progress ";
      $q_string .= "where pro_id = " . $a_task['strp_progress'];
      $q_progress = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_progress = mysql_fetch_array($q_progress);

      $daily_output .= $a_progress[0] . ": ";
      mysql_free_result($q_progress);

// Retrieve the Jira tag
      $q_string  = "select epic_jira,user_jira ";
      $q_string .= "from userstories ";
      $q_string .= "left join epics on epics.epic_id = userstories.user_epic ";
      $q_string .= "where user_id = " . $a_task['strp_jira'];
      $q_userstories = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_userstories) > 0) {
        $a_userstories = mysql_fetch_array($q_userstories);

        $daily_output .= $a_userstories['epic_jira'] . "/" . $a_userstories['user_jira'] . ": ";
        mysql_free_result($q_userstories);
      }

      $daily_output .= "<a href=\"#\" onclick=\"show_file('status.report.fill.php?id=";
      $daily_output .= $a_task['strp_id'] . "&user=" . $formVars['user'] . "');" . "\">";
      $daily_output .= mysql_real_escape_string($a_task['strp_task']) . "</a></td>";

      $daily_output .= "<td " . $ready . " title=\"Day:Hours Worked. Green = Status email active.";
      $daily_output .= " * = Quarterly Accomplishment\">" . $weekday[$a_task['strp_day']] . ":";
      $daily_output .= number_format((($a_task['strp_time'] * 15) / 60), 2, '.', ',');
      if ($a_task['strp_quarter']) {
        $daily_output .= "<b>*</b>";
      }
      $daily_output .= "</td>";
      $daily_output .= "</tr>";

// Now that it's set, if the checkbox is set, show the correct day.
      if ($formVars['daily'] < 10) {
        if ($a_task['strp_day'] == $formVars['daily']) {
          $output .= $daily_output;
        }
      } else {
        $output .= $daily_output;
      }

      $weektot[$a_task['strp_day']] += $a_task['strp_time'];
      $total += $a_task['strp_time'];
    }
    mysql_free_result($q_task);
  }

  mysql_free_result($q_class);
  $output .= "<tr>";
  $output .= "<td colspan=3 class=\"ui-widget-content button\"><b>Total Hours: </b>" . number_format((($total * 15) / 60), 2, '.', ',') . "</td>";
  $output .= "</tr>";
  $output .= "</table>";

?>

document.getElementById('from_mysql').innerHTML = '<?php print mysql_real_escape_string($output); ?>';

if (navigator.appName == "Microsoft Internet Explorer") {
  document.getElementById('prjready').className = "ui-widget-content";
  document.getElementById('proready').className = "ui-widget-content";
  document.getElementById('typready').className = "ui-widget-content";
  document.getElementById('tskready').className = "ui-widget-content";
  document.getElementById('timready').className = "ui-widget-content";
  document.getElementById('dayready<?php print $formVars['day'] ?>').className = "ui-widget-content";
} else {
  document.getElementById('prjready').setAttribute("class","ui-widget-content");
  document.getElementById('proready').setAttribute("class","ui-widget-content");
  document.getElementById('typready').setAttribute("class","ui-widget-content");
  document.getElementById('tskready').setAttribute("class","ui-widget-content");
  document.getElementById('timready').setAttribute("class","ui-widget-content");
  document.getElementById('dayready<?php print $formVars['day'] ?>').setAttribute("class","ui-widget-content");
}

document.getElementById('sunday').innerHTML    = <?php print number_format((($weektot[0] * 15) / 60), 2, '.', ','); ?>;
document.getElementById('monday').innerHTML    = <?php print number_format((($weektot[1] * 15) / 60), 2, '.', ','); ?>;
document.getElementById('tuesday').innerHTML   = <?php print number_format((($weektot[2] * 15) / 60), 2, '.', ','); ?>;
document.getElementById('wednesday').innerHTML = <?php print number_format((($weektot[3] * 15) / 60), 2, '.', ','); ?>;
document.getElementById('thursday').innerHTML  = <?php print number_format((($weektot[4] * 15) / 60), 2, '.', ','); ?>;
document.getElementById('friday').innerHTML    = <?php print number_format((($weektot[5] * 15) / 60), 2, '.', ','); ?>;
document.getElementById('saturday').innerHTML  = <?php print number_format((($weektot[6] * 15) / 60), 2, '.', ','); ?>;

document.taskmgr.save.checked = true;
document.taskmgr.update.disabled = true;
document.taskmgr.copy.disabled = true;

