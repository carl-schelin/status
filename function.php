<?php

include('settings.php');

date_default_timezone_set('UTC');

# clean and escape the input data

function clean($input, $maxlength) {
  $input = trim($input);
  $input = substr($input, 0, $maxlength);
  return ($input);
}

# log who did what

function logaccess($p_db, $p_user, $p_source, $p_detail) {
  include('settings.php');
  $package = 'function.php';

  $query = "insert into log set " .
    "log_id        = NULL, " .
    "log_user      = \"" . $p_user   . "\", " .
    "log_source    = \"" . $p_source . "\", " .
    "log_detail    = \"" . $p_detail . "\"";

  $insert = mysqli_query($p_db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysqli_error($p_db)));

}

# default access is $AL_Admin
function check_userlevel( $p_db, $p_level = 2 ) {
  $package = 'function.php';

  if (isset($_SESSION['username'])) {
    include('settings.php');
    $q_string  = "select usr_level ";
    $q_string .= "from users ";
    $q_string .= "where usr_name = \"" . $_SESSION['username'] . "\"";
    $q_user_level = mysqli_query($p_db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($p_db)));
    $a_user_level = mysqli_fetch_array($q_user_level);

    if ($a_user_level['usr_level'] <= $p_level) {
      return(1);
    } else {
      return(0);
    }
  } else {
    return(0);
  }
}

function return_Index($p_check, $p_string) {
  $package = 'function.php';
  $r_index = 0;
  $count = 1;
  $q_table = mysqli_query($db, $p_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $p_string . "&mysql=" . mysqli_error($db)));
  while ($a_table = mysqli_fetch_row($q_table)) {
    if ($p_check == $a_table[0]) {
      $r_index = $count;
    }
    $count++;
  }
  return $r_index;
}

function wait_Process($p_string) {
# includeing in order to use path information
  include('settings.php');

  $randgif = rand(0,1);

  $output  = "<center>";
  switch ($randgif) {
    case 0: $output .= "<img src=\"" . $Siteroot . "/imgs/3MA_processingbar.gif\">";
            $output .= "<br class=\"iu-widget-content\">" . $p_string;
            break;
    case 1: $output .= "<img src=\"" . $Siteroot . "/imgs/progress_bar.gif\">";
            $output .= "<br class=\"iu-widget-content\">" . $p_string;
            break;
    case 2: $output .= "<img src=\"" . $Siteroot . "/imgs/chasingspheres.gif\">";
            $output .= $p_string;
            $output .= "<img src=\"" . $Siteroot . "/imgs/chasingspheres.gif\">";
            break;
    case 3: $output .= "<img src=\"" . $Siteroot . "/imgs/gears.gif\">";
            $output .= $p_string;
            $output .= "<img src=\"" . $Siteroot . "/imgs/gears.gif\">";
            break;
    case 4: $output .= "<img src=\"" . $Siteroot . "/imgs/recycling.gif\">";
            $output .= $p_string;
            $output .= "<img src=\"" . $Siteroot . "/imgs/recycling.gif\">";
            break;
  }
  $output .= "</center>";

  return $output;
}

function convert_datetime($date_string) {

  list($week_mon, $week_day, $week_year) = explode("/", $date_string);

  $timestamp = mktime("12", "00", "00", $week_mon + 1, $week_day, $week_year);

  return $timestamp;

}

function myUrlEncode($string) {

    $entities     = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D');
    $replacements = array(  '!',   '*',   "'",   "(",   ")",   ";",   ":",   "@",   "&",   "=",   "+",   "$",   ",",   "/",   "?",   "%",   "#",   "[",   "]");
    return str_replace($entities, $replacements, urlencode($string));
}

function myUrlDecode($string) {

    $entities     = array(  '!',   '*',   "'",   "(",   ")",   ";",   ":",   "@",   "&",   "=",   "+",   "$",   ",",   "/",   "?",   "%",   "#",   "[",   "]");
    $replacements = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D');
    return str_replace($entities, $replacements, urldecode($string));
}

function displayHistory($ras_id, $ras_code, $ras_resource, $ras_group) {
  $package = 'function.php';
  $divout .= "<div id=\"down_" . $ras_id . "\" style=\"display:none\">\n";
  $divout .= "<table>\n";

  $divout .= "<tr>\n";
  $divout .= "  <th colspan=13>Historical View: " . $ras_code . "</th>\n";
  $divout .= "</tr>\n";
  $divout .= "<tr>\n";
  $divout .= "  <th>Project Name</th>\n";
  $divout .= "  <th>Jan</th>\n";
  $divout .= "  <th>Feb</th>\n";
  $divout .= "  <th>Mar</th>\n";
  $divout .= "  <th>Apr</th>\n";
  $divout .= "  <th>May</th>\n";
  $divout .= "  <th>Jun</th>\n";
  $divout .= "  <th>Jul</th>\n";
  $divout .= "  <th>Aug</th>\n";
  $divout .= "  <th>Sep</th>\n";
  $divout .= "  <th>Oct</th>\n";
  $divout .= "  <th>Nov</th>\n";
  $divout .= "  <th>Dec</th>\n";
  $divout .= "</tr>\n";


# Create the first line; the time worked on the project over the past year

  $divout .= "<tr>\n";
  $divout .= "  <td align=right><strong>Time Worked:</strong></th>\n";
# loop through the months and retrieve the information for the project
  $divmon = date('n');
  $divyear = date('Y');
  for ($i = 1; $i < 13; $i++) {
    $divouttotal = 0;
    $divyearmon = date('Ym', mktime(0, 0, 0, $i + 1, 0, $divyear));
    $q_string  = "select strp_time ";
    $q_string .= "from status ";
    $q_string .= "left join project on project.prj_id = status.strp_project ";
    $q_string .= "where prj_code = " . $ras_code . " and strp_yearmon = " . $divyearmon . " and strp_name = " . $ras_resource;
    $q_divstatus = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
    while ($a_divstatus = mysqli_fetch_array($q_divstatus)) {
      $divouttotal += $a_divstatus['strp_time'];
    }

    if ($divyear == date('Y')) {
      $divtdstatus = '';
    } else {
      $divtdstatus = " class=\"deleted\"";
    }

    $divout .= "  <td" . $divtdstatus . " title=\"Year: " . $divyear . "\">" . ($divouttotal / 4) . "</td>\n";
# do it after so the next month is set to the correct year
    if ($i == $divmon) {
      $divyear = date('Y') - 1;
    }
  }
  $divout .= "</tr>\n";

  $q_string  = "select ras_id,ras_name,ras_code,ras_link,ras_status,ras_date,ras_manager,ras_resource,ras_group,";
  $q_string .= "ras_jan,ras_feb,ras_mar,ras_apr,ras_may,ras_jun,ras_jul,ras_aug,ras_sep,";
  $q_string .= "ras_oct,ras_nov,ras_dec,ras_closed ";
  $q_string .= "from st_ras ";
  $q_string .= "left join users on ras_resource = users.usr_id ";
  $q_string .= "where ras_name not like \"%PTO%\" and ras_group = " . $ras_group . " and ras_resource = " . $ras_resource . " and ras_code = " . $ras_code . " ";
  $q_string .= "order by ras_id desc";
  $q_showras = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ($a_showras = mysqli_fetch_array($q_showras)) {
    $viewras[1] = $a_showras['ras_jan'];
    $viewras[2] = $a_showras['ras_feb'];
    $viewras[3] = $a_showras['ras_mar'];
    $viewras[4] = $a_showras['ras_apr'];
    $viewras[5] = $a_showras['ras_may'];
    $viewras[6] = $a_showras['ras_jun'];
    $viewras[7] = $a_showras['ras_jul'];
    $viewras[8] = $a_showras['ras_aug'];
    $viewras[9] = $a_showras['ras_sep'];
    $viewras[10] = $a_showras['ras_oct'];
    $viewras[11] = $a_showras['ras_nov'];
    $viewras[12] = $a_showras['ras_dec'];

    if ($a_showras['ras_closed'] == 1) {
      $rasstatus = " class=\"activedk\"";
      $showstatus = $a_showras['ras_status'];
    } else {
      $rasstatus = " class=\"green\"";
      $showstatus = "This is the current, active project RAS line";
    }

    $divout .= "<tr>\n";
    $divout .= "  <td" . $rasstatus . " title=\"" . $showstatus . "\">" . $a_showras['ras_name'] . "</td>\n";

    for ($i = 1; $i < 13; $i++) {
      if ($a_showras['ras_closed'] == 1) {
        if ($viewras[$i] > 0) {
          $rasstatus = " class=\"green\"";
        } else {
          $rasstatus = " class=\"activedk\"";
        }
        $showstatus = $a_showras['ras_status'];
      } else {
        $rasstatus = " class=\"green\"";
        $showstatus = "This is the current, active project RAS line";
      }
      $divout .= "  <td" . $rasstatus . ">" . $viewras[$i] . "</td>\n";
    }
    $divout .= "</tr>\n";
  }
  $divout .= "</table>\n";
  $divout .= "</div>\n\n";

  return $divout;
}

# connect to the server
function db_connect($p_server, $p_database, $p_user, $p_pass){

  $r_db = mysqli_connect($p_server, $p_user, $p_pass, $p_database);

  $db_select = mysqli_select_db($r_db, $p_database);

  return $r_db;
}

?>
