<?php
# Script: status.mysql.php
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
    $package = "status.mysql.php";
    $formVars['update']         = clean($_GET['update'],        10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel($db, $AL_User)) {

      $formVars['id']        = clean($_GET['id'], 10);
      $formVars['user']      = clean($_GET['user'], 10);
      $formVars['startweek'] = clean($_GET['startweek'], 4);
      $formVars['endweek']   = clean($_GET['endweek'], 4);
      $formVars['group']     = clean($_GET['group'], 4);
      $formVars['save']      = clean($_GET['save'], 10);
      $debug = "";
      $DEBUG = 1;

      if ($formVars['startweek'] == $formVars['endweek']) {
        $formVars['endweek'] = $formVars['startweek'] + 1;
      }

      $logfile = "status.mysql.php";

      logaccess($db, $_SESSION['username'], $logfile, "Accessing status.mysql.php " . $formVars['id'] . ": week=" . $formVars['startweek'] . " user=" . $formVars['user'] . " group=" . $formVars['group'] . " save=" . $formVars['save']);

#### Save incoming data if any.

      if ($formVars['save'] >= 0) {

        logaccess($db, $_SESSION['username'], $logfile, "Updating status record " . $formVars['id'] . ": save=" . $formVars['save']);

        $q_string  = "update status set ";
        $q_string .= "strp_save = " . $formVars['save'] . " ";
        $q_string .= "where strp_id = " . $formVars['id'];
        $insert = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));

      }
    }
  }

  $doweek[0] = "Sunday";
  $doweek[1] = "Monday";
  $doweek[2] = "Tuesday";
  $doweek[3] = "Wednesday";
  $doweek[4] = "Thursday";
  $doweek[5] = "Friday";
  $doweek[6] = "Saturday";

#### Now retrieve the data from the db in order to create the page.

#######
# Retrieve information for the user
#######

  $q_string  = "select usr_group ";
  $q_string .= "from users ";
  $q_string .= "where usr_id = " . $formVars['user'];
  $q_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  $a_users = mysqli_fetch_array($q_users);

  $usergroup = $a_users['usr_group'];

#######
# Retrieve information for the group
#######

  $q_string  = "select grp_day ";
  $q_string .= "from groups ";
  $q_string .= "where grp_id = $usergroup";
  $q_groups = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  $a_groups = mysqli_fetch_array($q_groups);

  $startday = $a_groups['grp_day'];

#######
# Retrieve all the weeks into the weekval array
#######

  $q_string  = "select wk_id,wk_date ";
  $q_string .= "from st_weeks";
  $q_st_weeks = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));

  while ( $a_st_weeks = mysqli_fetch_array($q_st_weeks) ) {
    $weekval[$a_st_weeks['wk_id']] = $a_st_weeks['wk_date'];
  }

#######
# Retrieve all the classifications into the classval array
#######

  $class = 0;
  $first = 0;
  $q_string  = "select cls_id,cls_name,cls_project ";
  $q_string .= "from st_class";
  $q_st_class = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));

  while ( $a_st_class = mysqli_fetch_array($q_st_class) ) {
    $classval[$a_st_class['cls_id']] = $a_st_class['cls_name'];
    $classprj[$a_st_class['cls_id']] = $a_st_class['cls_project'];
  }

#######
# Retrieve all the projects into the projval array
#######

  $project = 0;
  $q_string  = "select prj_id,prj_desc ";
  $q_string .= "from project"; 
  $q_project = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));

  while ( $a_project = mysqli_fetch_array($q_project) ) {
    $projval[$a_project['prj_id']] = $a_project['prj_desc'];
  }

#######
# Retrieve all the progress into the progval array
#######
  
  $progress = 0; 
  $q_string  = "select pro_id,pro_name ";
  $q_string .= "from st_progress";
  $q_st_progress = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));

  while ( $a_st_progress = mysqli_fetch_array($q_st_progress) ) {
    $progval[$a_st_progress['pro_id']] = $a_st_progress['pro_name'];
  }

#######
# Now process the status reports
####### 
    
###
# Logic:
#  if group > 0
#   if user level = manager, get all the users that usr_manager = usr_id
#   if user level = supervisor, get all the users where usr_group = $formVars['group']
###

  if ($formVars['group'] != 0) {
    if (check_userlevel($db, $AL_Supervisor)) {
      $q_string = "select usr_id from users where usr_supervisor = " . $formVars['user'];
    }
    if (check_userlevel($db, $AL_Manager)) {
      $q_string = "select usr_id from users where usr_manager = " . $formVars['user'];
    }
    if (check_userlevel($db, $AL_Director)) {
      $q_string = "select usr_id from users where usr_director = " . $formVars['user'];
    }
    if (check_userlevel($db, $AL_VicePresident)) {
      $q_string = "select usr_id from users where usr_vicepresident = " . $formVars['user'];
    }

# restrict to group if looking at something other than the Management group.
    if ($formVars['group'] != 3 && $formVars['group'] != -1) {
      $q_string .= " and usr_group = " . $formVars['group'];
    }

# now build the user string this will have all the users that fit the above criteria
    $prtor = "";
    $u_string = "";

    $q_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
    while ($a_users = mysqli_fetch_array($q_users)) {
      $u_string .= $prtor . "strp_name = " . $a_users['usr_id'];
      if ($prtor == "") {
        $prtor = " or ";
      }
    }
# if no users were found, empty group for instance, present just the user's data
    if ($u_string == "") {
      $u_string = "strp_name = " . $formVars['user'];
    }
    $managerview = " and strp_save = 1 ";
  } else {
    $u_string = "strp_name = " . $formVars['user'];
    $managerview = "";
  }

### Begin creating the output page.
  $holdweek = '';
  $output = "<table class=\"ui-widget-content\">";

  $q_string  = "select strp_id,strp_week,strp_name,strp_class,strp_project,strp_progress,strp_task,strp_day,strp_save ";
  $q_string .= "from status ";
  $q_string .= "where (($u_string)$managerview) ";
  $q_string .= "and strp_week >= " . $formVars['startweek'] . " ";
  $q_string .= "order by strp_week,strp_class,strp_project,strp_day";
  $q_status = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));

  while ( $a_status = mysqli_fetch_array($q_status) ) {

    $q_string  = "select usr_last ";
    $q_string .= "from users ";
    $q_string .= "where usr_id = " . $a_status['strp_name'];
    $q_username = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
    $a_username = mysqli_fetch_array($q_username);

    if ($holdweek != $a_status['strp_week']) {
      $holdweek = $a_status['strp_week'];
      if ($a_status['strp_week'] == $formVars['startweek']) {
        $output .= "<tr><th class=\"ui-state-default\">Starting on " . $doweek[$startday] . " of Week " . $weekval[$formVars['startweek']] . "</th></tr>";
      }
      if ($a_status['strp_week'] == $formVars['endweek'] && $startday != 0) {
        $output .= "<tr><th class=\"ui-state-default\">Ending on " . $doweek[$startday -1] . " of Week " . $weekval[$formVars['endweek']] . "</th></tr>";
      }
    }

    if (($a_status['strp_week'] == $formVars['startweek'] && $a_status['strp_day'] >= $startday) || ($a_status['strp_week'] == $formVars['endweek'] && $a_status['strp_day'] < $startday)) {

# build the class line
      if ($class != $a_status['strp_class']) {
        $class = $a_status['strp_class'];
        if ($class > 0) {
          $output .= "<tr><td class=\"ui-widget-content\"><b>" . $classval[$a_status['strp_class']] . "</b></td></tr>";
        }
      }

# build the project line beginning with an asterisk
      $pre = "";
      if ($class > 0) {
        if ($classprj[$class] == 1) {
          $pre = "&nbsp;&nbsp;&nbsp;&nbsp;- ";
          if ($project != $a_status['strp_project']) {
            $project = $a_status['strp_project'];
            if ($project > 0) {
              $output .= "<tr><td class=\"ui-widget-content\"><b>&nbsp;&nbsp;* " . $projval[$a_status['strp_project']] . "</b></td></tr>";
            }
          }
        } else {
          $pre = "&nbsp;&nbsp;- ";
        }
      }

# build the detail line
# build title line for the mouse-over
      if ($a_status['strp_save']) {
        $ready = " class=\"ui-state-highlight\"";
        $title = " title=\"Click to remove from Status e-mail\"";
        $save = 0;
      } else {
        $ready = " class=\"ui-widget-content\"";
        $title = " title=\"Click to add to Status e-mail\"";
        $save = 1;
      }

# 
      $output .= "<tr><td" . $ready . $title . ">" . $pre;
      if ($a_status['strp_progress'] > 0) {
        $output .= $progval[$a_status['strp_progress']] . ": ";
      }
      $output .= "<a href=\"#\" onclick=\"show_file('status.mysql.php";
        $output .= "?id=" . $a_status['strp_id'];
        $output .= "&startweek=" . $formVars['startweek'];
        $output .= "&endweek=" . $formVars['endweek'];
        $output .= "&user=" . $formVars['user'];
        $output .= "&group=" . $formVars['group'];
        $output .= "&save=" . $save;
      $output .= "');\">" . $a_status['strp_task'] . "</a>";

# add the user name if a manager is looking
      if ($formVars['group'] != 0) {
        $output .= " (" . $a_username['usr_last'] . ")";
      }

# close the detail line
      $output .= "</td></tr>";

    }
  }

$output .= "</table>";

if (strlen($debug) > 0) {
  $output = $debug;
}

?>

document.getElementById('from_mysql').innerHTML = '<?php print mysqli_real_escape_string($db, $output); ?>';

