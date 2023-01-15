<?php
# Script: timegraph.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# Description:

  include('settings.php');
  $called = 'no';
  include($Sitepath . '/function.php');
  include($Loginpath . '/check.php');

# connect to the database
  $db = db_connect($DBserver, $DBname, $DBuser, $DBpassword);

  check_login($db, $AL_User);

  $package = "timegraph.php";

  logaccess($db, $_SESSION['username'], $package, "Accessing script");

  global $deg;

  function get_polar($xrel, $yrel, $ang, $radius) {
    $i = $ang;
    $ang = ($ang * pi())/ 180;
   
    $ix = abs($radius*cos($ang));
    $iy = abs($radius*sin($ang));
   
    if ($i>=0 && $i<=90) {
        $ix = $xrel + $ix;
        $iy = $yrel - $iy;
    }
    if ($i>90 && $i<=180) {
        $ix = $xrel - $ix;
        $iy = $yrel - $iy;
    }
    if ($i>180 && $i<=270) {
        $ix = $xrel - $ix;
        $iy = $yrel + $iy;
    }
// Fixed as the value for 360 is returned as 360.01
    if ($i>270 && $i<=361) {
        $ix = $xrel + $ix;
        $iy = $yrel + $iy;
    }

    $ix = floor($ix);
    $iy = floor($iy);
    //echo ($ix . " $iy<br>");
    $returnvals = array (
                        'x1' => $xrel,
                        'y1' => $yrel,
                        'x2' => $ix,
                        'y2' => $iy
                        );
    return $returnvals;
  }

  function get_degtotal($degindex)
  {
    global $deg;
    if ($degindex == 0 ) {
        return ( $deg[$degindex] );
    }
    else {       
        return ( $deg[$degindex] + get_degtotal($degindex-1) );
    }   
  }

  $formVars['startweek'] = clean($_GET['startweek'], 10);
  $formVars['endweek']   = clean($_GET['endweek'], 10);
  $formVars['user']      = clean($_GET['user'], 10);
  $formVars['group']     = clean($_GET['group'], 10);

  $data[0] = 0;
  $data[1] = 0;
  $data[2] = 0;
  $data[3] = 0;
  $string[0] = "Projects";
  $string[1] = "Support & Maintenance";
  $string[2] = "Administration";
  $string[3] = "On-Call/After hours";

  $q_user = "";
  $setor = " and";
  $count = 0;



  if ($formVars['group'] != 0) {
    if (check_userlevel($db, $AL_Supervisor)) {
      $q_string = "select usr_id from st_users where usr_supervisor = " . $formVars['user'];
    }
    if (check_userlevel($db, $AL_Manager)) {
      $q_string = "select usr_id from st_users where usr_manager = " . $formVars['user'];
    }
    if (check_userlevel($db, $AL_Director)) {
      $q_string = "select usr_id from st_users where usr_director = " . $formVars['user'];
    }
    if (check_userlevel($db, $AL_VicePresident)) {
      $q_string = "select usr_id from st_users where usr_vicepresident = " . $formVars['user'];
    }
  
# restrict to group if looking at something other than the Management group.
    if ($formVars['group'] != 3 && $formVars['group'] != -1) {
      $q_string .= " and usr_group = " . $formVars['group'];
    }
    
# now build the user string this will have all the users that fit the above criteria
    $prtor = "";
    $u_string = "";
  
    $q_st_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
    while ($a_st_users = mysqli_fetch_array($q_st_users)) {
      $u_string .= $prtor . "strp_name = " . $a_st_users['usr_id'];
      if ($prtor == "") {
        $prtor = " or ";
      }
    }
# if no users were found, empty group for instance, present just the user's data
    if ($u_string == "") {
      $u_string = "strp_name = " . $formVars['user'];
    }
  } else {
    $u_string = "strp_name = " . $formVars['user'];
  }

  $q_string  = "select strp_project,strp_time ";
  $q_string .= "from st_status ";
  $q_string .= "where strp_week >= " . $formVars['startweek'] . " and strp_week <= " . $formVars['endweek'] . " and (" . $u_string . ")";
  $q_st_status = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ($a_st_status = mysqli_fetch_array($q_st_status)) {

    $q_string  = "select prj_code,prj_task ";
    $q_string .= "from st_project ";
    $q_string .= "where prj_id = " . $a_st_status['strp_project'];
    $q_st_project = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
    $a_st_project = mysqli_fetch_array($q_st_project);

    if ($a_st_project['prj_code'] == 7884) {

      if ($a_st_project['prj_task'] == "1.1 Tickets") {
        $data[3] += $a_st_status['strp_time'];
      }

      if ($a_st_project['prj_task'] == "1.2 Maintenance") {
        $data[3] += $a_st_status['strp_time'];
      }

      if ($a_st_project['prj_task'] == "1.3 On-Call") {
        $data[1] += $a_st_status['strp_time'];
      }

      if ($a_st_project['prj_task'] == "1.4 Consulting") {
        $data[3] += $a_st_status['strp_time'];
      }

      if ($a_st_project['prj_task'] == "2.1 Admin") {
        $data[2] += $a_st_status['strp_time'];
      }

      if ($a_st_project['prj_task'] == "2.2 PTO") {
        $data[2] += $a_st_status['strp_time'];
      }

      if ($a_st_project['prj_task'] == "2.3 Training") {
        $data[2] += $a_st_status['strp_time'];
      }
    } else {
// Incidents (code 2839) are added to the On Call bucket
      if ($a_st_project['prj_code'] == 2839) {
        $data[1] += $a_st_status['strp_time'];
      } else {
        $data[0] += $a_st_status['strp_time'];
      }
    }
  }

  $im      = imagecreate (467, 267) or die('Cannot Initialize new GD image stream');
  $w       = imagecolorallocate ($im, 255, 255, 255);
  $black   = imagecolorallocate ($im, 0, 0, 0);
  $red     = imagecolorallocate ($im, 255, 0, 0);
  $green   = imagecolorallocate ($im, 0, 180, 0);

  $datasum = array_sum($data);

// Project totals - Should be in blue.
  $degper[0]    = number_format(($data[0] / $datasum * 100), 0, ".", "");
  $deg[0]       = number_format(($data[0] / $datasum * 360), 2, ".", "");
  $randcolor[0] = imagecolorallocate($im, 60, 116, 182);  // blue

// On-Call totals - Should be in purple.
  $degper[1]    = number_format(($data[1] / $datasum * 100), 0, ".", "");
  $deg[1]       = number_format(($data[1] / $datasum * 360), 2, ".", "");
  $randcolor[1] = imagecolorallocate($im, 103, 48, 250);  // purple

// Administration totals - should be in green(ish).
  $degper[2]    = number_format(($data[2] / $datasum * 100), 0, ".", "");
  $deg[2]       = number_format(($data[2] / $datasum * 360), 2, ".", "");
  $randcolor[2] = imagecolorallocate($im, 178, 210, 86);   // avacado

// Maintenance totals - should be in red.
  $degper[3]    = number_format(($data[3] / $datasum * 100), 0, ".", "");
  $deg[3]       = number_format(($data[3] / $datasum * 360), 2, ".", "");
  $randcolor[3] = imagecolorallocate($im, 192, 80, 77);   // red

  $datadeg = array();
  $datapol = array();
  $degbetween = array();
  $databetweenpol = array();
  $degpercent = array();

// 
  for ($i=0; $i < count($deg) ; $i++) {
    $datadeg[$i] = get_degtotal($i);
    $datapol[$i] = get_polar(154, 133, $datadeg[$i], 112);
  }

  for ($i=0; $i < count($datadeg) ; $i++) {
    /*this is a trick where you take 2deg angle before
    and get the smaller radius so that you can have a pt to
    `imagefill` the chartboundary
    */
    $degbetween[$i] = ($datadeg[$i]-2);
    $databetweenpol[$i] = get_polar(154, 133, $degbetween[$i], 50);
  }

  for ($i=0; $i < count($datadeg); $i++) {
    $degloc = $datadeg[$i] - ($deg[$i] / 2);
    $degpercent[$i] = get_polar(154, 133, $degloc, 95);
  }

  for ($i=0; $i<count($deg); $i++) {
    if ($deg[$i] > 0) {
      imageline ($im, 154, 133, $datapol[$i]['x2'], $datapol[$i]['y2'], $black);
    }
  }

  imagearc($im, 154, 133, 224, 224, 0, 360, $black);


  for ($i=0; $i<count($deg); $i++) {
    if ($deg[$i] > 0) {
      imagefill ($im, $databetweenpol[$i]['x2'], $databetweenpol[$i]['y2'], $randcolor[$i]);
    }
  }

  for ($i = 0; $i < count($deg); $i++) {
    if ($deg[$i] > 0) {
      imagestring($im, 2, $degpercent[$i]['x2'] - 10, $degpercent[$i]['y2'] - 5, $degper[$i] . "%", $black);
    }
  }


//
// Print the legend on the right
//

  imagerectangle($im, 330, 61, 337, 68, $black);
       imagefill($im, 332, 63, $randcolor[0]);
  imagestring($im, 2, 342, 58, $string[0], $black);

  imagerectangle($im, 330, 101, 337, 108, $black);
       imagefill($im, 332, 103, $randcolor[3]);
  imagestring($im, 2, 342, 98, $string[1], $black);

  imagerectangle($im, 330, 141, 337, 148, $black);
       imagefill($im, 332, 143, $randcolor[2]);
  imagestring($im, 2, 342, 138, $string[2], $black);

  imagerectangle($im, 330, 181, 337, 188, $black);
       imagefill($im, 332, 183, $randcolor[1]);
  imagestring($im, 2, 342, 178, $string[3], $black);

// All done, print the image
  header ("Content-type: image/png");
  imagepng($im);
?>
