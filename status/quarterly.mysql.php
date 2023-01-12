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

      $formVars['id']        = clean($_GET['id'], 10);
      $formVars['user']      = clean($_GET['user'], 10);
      $formVars['startweek'] = clean($_GET['startweek'], 4);
      $formVars['endweek']   = clean($_GET['endweek'], 4);
      $formVars['group']     = clean($_GET['group'], 4);
      $formVars['save']      = clean($_GET['save'], 10);
      $formVars['move']      = clean($_GET['move'], 10);
      $formVars['goedit']    = clean($_GET['goedit'], 10);
      $debug = "";
      $DEBUG = 0;

      logaccess($db, $_SESSION['username'], "quarterly.mysql.php", "Accessing quarterly.mysql.php " . $formVars['id'] . ": startweek=" . $formVars['startweek'] . " endweek=" . $formVars['endweek'] . " user=" . $formVars['user'] . " group=" . $formVars['group'] . " save=" . $formVars['save']);

#### Save incoming data if any.

      if ($formVars['save'] >= 0) {

        logaccess($db, $_SESSION['username'], "quarterly.mysql.php", "Updating status record " . $formVars['id'] . ": save=" . $formVars['save']);
        $q_string  = "update status set ";
        $q_string .= "strp_quarter = " . $formVars['save'] . " ";
        $q_string .= "where strp_id = " . $formVars['id'];
        $insert = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));

      }

      if ($formVars['move'] > 0) {

        logaccess($db, $_SESSION['username'], "quarterly.mysql.php", "Updating status record " . $formVars['id'] . ": move=" . $formVars['move']);
        $q_string  = "update status set ";
        $q_string .= "strp_type = " . $formVars['move'] . " ";
        $q_string .= "where strp_id = " . $formVars['id'];
        $insert = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));

      }
    }
  }

#### Now retrieve the data from the db in order to create the page.

#######
# Retrieve information for the task types
#######

  $q_string  = "select typ_id,typ_name ";
  $q_string .= "from st_type";
  $q_st_type = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ($a_st_type = mysqli_fetch_array($q_st_type)) {

    $type[$a_st_type['typ_id']] = $a_st_type['typ_name'];
  }
  $type[0] = "N/A";

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
  $q_string .= "from st_project"; 
  $q_st_project = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));

  while ( $a_st_project = mysqli_fetch_array($q_st_project) ) {
    $projval[$a_st_project['prj_id']] = $a_st_project['prj_desc'];
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

  if ($formVars['group'] > 0) {
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
    if ($formVars['group'] != 3) {
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
    $managerview = " and strp_quarter = 1 ";
# if no users were found, empty group for instance, present just the user's data
    if ($u_string == "") {
      $u_string = "strp_name = " . $formVars['user'];
    }
  } else {
    $managerview = "";
    $u_string = "strp_name = " . $formVars['user'];
  }

### Begin creating the output page.
  $totalcount = 0;
  $projectcount = 0;
  $projectoutput = "";
  $quarterlytotal = 0;
  $quarterlycount = 0;
  $tableend = "";
  $output = "<table width=80%>";

  $q_string = "select strp_id,strp_week,strp_name,strp_class,strp_project,strp_progress,strp_task,strp_day,strp_type,strp_quarter ";
  $q_string .= "from status ";
  $q_string .= "where (($u_string)$managerview) and (strp_week >= " . $formVars['startweek'] . " and strp_week <= " . $formVars['endweek'] . ") ";
  $q_string .= "order by strp_project,strp_type,strp_week";
  $q_status = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));

  while ( $a_status = mysqli_fetch_array($q_status) ) {

    $q_string  = "select usr_last ";
    $q_string .= "from users ";
    $q_string .= "where usr_id = " . $a_status['strp_name'];
    $q_username = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
    $a_username = mysqli_fetch_array($q_username);

    if ($project != $a_status['strp_project']) {
      if ($project > 0) {
        $output .= $tablened . "</div><table class\"ui-widget-content\"><tr><th class=\"ui-state-default\" align=left><u>";
        $output .= "<a href=\"javascript:;\" onmousedown=\"toggleDiv('" . $projval[$project] . "');\">" . $projval[$project] . " (" . $projectcount . "/" . $quarterlycount . ")</a>";
        $output .= "</u></th></tr>";
        $output .= "</table><div id=\"" . $projval[$project] . "\" style=\"display:none\"><table class=\"ui-widget-content\">";
        $output .= $projectoutput;
        $projectoutput = "";
      }
      $projectcount = 0;
      $quarterlycount = 0;
      $statustype = -1;
      $project = $a_status['strp_project'];
    }

    if ($statustype != $a_status['strp_type']) {
      $projectoutput .= "<tr><td class=\"ui-widget-content\" colspan=2>&nbsp;+<i><b>" . $type[$a_status['strp_type']] . "</b></i></td></tr>";
      $statustype = $a_status['strp_type'];
    }

    if ($a_status['strp_quarter']) {
      $ready = " class=\"ui-state-highlight\"";
      $title = " title=\"Click to remove from Quarterly Accomplishments. ^ to move task up, v to move task down.\"";
      $save = 0;
    } else {
      $ready = " class=\"ui-widget-content\"";
      $title = " title=\"Click to add to Quarterly Accomplishments. ^ to move task up, v to move task down.\"";
      $save = 1;
    }
    $projectoutput .= "<tr><td" . $ready . $title . ">";

    if ($a_status['strp_type'] == 0) {
      $typeup = 3;
      $typedown = 1;
    }
    if ($a_status['strp_type'] == 1) {
      $typeup = 3;
      $typedown = 2;
    }
    if ($a_status['strp_type'] == 2) {
      $typeup = 1;
      $typedown = 3;
    }
    if ($a_status['strp_type'] == 3) {
      $typeup = 2;
      $typedown = 1;
    }

    $projectoutput .= "<a href=\"#\" onclick=\"show_file('quarterly.mysql.php?id=" . $a_status['strp_id'] . "&startweek=" . $formVars['startweek'];
    $projectoutput .= "&endweek=" . $formVars['endweek'] . "&user=" . $formVars['user'] . "&group=" . $formVars['group'];
    $projectoutput .= "&save=-1&move=" . $typeup . "');\">^</a>";

    $projectoutput .= "<a href=\"#\" onclick=\"show_file('quarterly.mysql.php?id=" . $a_status['strp_id'] . "&startweek=" . $formVars['startweek'];
    $projectoutput .= "&endweek=" . $formVars['endweek'] . "&user=" . $formVars['user'] . "&group=" . $formVars['group'];
    $projectoutput .= "&save=-1&move=" . $typedown . "');\">v</a>&nbsp;- ";

    if ($a_status['strp_progress'] > 0) {
      $projectoutput .= $progval[$a_status['strp_progress']] . ": ";
    }
    $projectoutput .= "<a href=\"#\" onclick=\"show_file('quarterly.mysql.php?id=" . $a_status['strp_id'];
    $projectoutput .= "&startweek=" . $formVars['startweek'] . "&endweek=" . $formVars['endweek'] . "&user=" . $formVars['user'] . "&group=" . $formVars['group'];
    $projectoutput .= "&save=" . $save . "');\">" . mysqli_real_escape_string($db, $a_status['strp_task']) . "</a>";
    if ($formVars['group'] > 0) {
      $projectoutput .= " (" . $a_username['usr_last'] . ")";
    }
    $projectoutput .= "</td><td" . $ready . " title=\"Click to jump to the status report for this week so you can make changes to the text if needed.\"><a href=\"" . $Statusroot . "/status.report.php?startweek=" . $a_status['strp_week'] . "&user=" . $formVars['user'] . "\">" . $weekval[$a_status['strp_week']] . "</td></tr>";

    $quarterlytotal += $a_status['strp_quarter'];
    $quarterlycount += $a_status['strp_quarter'];
    $totalcount++;
    $projectcount++;
    $tablened = "</table>";

  }

  if ($project != $a_status['strp_project']) {
    if ($project > 0) {
      $output .= $tablened . "</div><table class=\"ui-widget-content\"><tr><th class=\"ui-state-default\" align=left><u>";
      $output .= "<a href=\"javascript:;\" onmousedown=\"toggleDiv('" . $projval[$project] . "');\">" . $projval[$project] . " (" . $projectcount . "/" . $quarterlycount . ")</a>";
      $output .= "</u></th></tr>";
      $output .= "</table><div id=\"" . $projval[$project] . "\" style=\"display:none\"><table class=\"ui-widget-content\">";
      $output .= $projectoutput;
      $projectoutput = "";
    }
    $projectcount = 0;
    $quarterlycount = 0;
    $statustype = -1;
    $project = $a_status['strp_project'];
  }

  $output .= "</table>";

# Now create the main header and note the total items for the time period
  $header  = "<table class=\"ui-widget-content\">";
  $header .= "<tr>";
  $header .= "  <th class=\"ui-state-default\" title=\"The range of time being reviewed. () contains the total items listed in this time period.\">Week " . $weekval[$formVars['startweek']] . " to " . $weekval[$formVars['endweek']] . " (" . $totalcount . "/" . $quarterlytotal . ")</th>";
  $header .= "</tr>";
  $header .= "</table>" . $output; 

  if (strlen($debug) > 0) {
    $output = $debug;
  }

?>

document.getElementById('from_mysql').innerHTML = '<?php print mysqli_real_escape_string($db, $output); ?>';

