<?php
# Script: levels.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# Description: 

  include('settings.php');
  $called = 'no';
  include($Sitepath . '/function.php');
  include($Loginpath . '/check.php');

# connect to the database
  $db = db_connect($DBserver, $DBname, $DBuser, $DBpassword);

  check_login($db, $AL_Admin);

  $package = "levels.php";

  logaccess($db, $_SESSION['username'], $package, "Accessing script");

# if help has not been seen yet,
  if (show_Help($db, $Sitepath . "/" . $package)) {
    $display = "display: block";
  } else {
    $display = "display: none";
  }

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Manage User Levels</title>

<?php include($Sitepath . "/head.php"); ?>

<script type="text/javascript">
<?php

  if (check_userlevel($db, $AL_Admin)) {
?>
function delete_level( p_script_url ) {
  var question;
  var answer;

  question  = "Making changes to the level titles can be done but deleting a level will seriously\n";
  question += "cause issues with user access to the system.\n\n";

  question += "Are you SURE you want to delete this level?";

  answer = confirm(question);

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
  }
}
<?php
  }
?>

function attach_file( p_script_url, update ) {
  var af_form = document.formCreate;
  var af_url;

  af_url  = '?update='   + update;

  af_url += "&lvl_name="         + encode_URI(af_form.lvl_name.value);
  af_url += "&lvl_level="        + encode_URI(af_form.lvl_level.value);
  af_url += "&lvl_disabled="     + encode_URI(af_form.lvl_disabled.value);

  script = document.createElement('script');
  script.src = p_script_url + af_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function update_file( p_script_url, update ) {
  var uf_form = document.formUpdate;
  var uf_url;

  uf_url  = '?update='   + update;
  uf_url += '&id='       + uf_form.id.value;

  uf_url += "&lvl_name="         + encode_URI(uf_form.lvl_name.value);
  uf_url += "&lvl_level="        + encode_URI(uf_form.lvl_level.value);
  uf_url += "&lvl_disabled="     + encode_URI(uf_form.lvl_disabled.value);

  script = document.createElement('script');
  script.src = p_script_url + uf_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}


function clear_fields() {
  show_file('levels.mysql.php?update=-1');
}

$(document).ready( function() {
  $( '#clickCreate' ).click(function() {
    $( "#dialogCreate" ).dialog('open');
  });

  $( "#dialogCreate" ).dialog({
    autoOpen: false,
    modal: true,
    height: 225,
    width: 600,
    show: 'slide',
    hide: 'slide',
    closeOnEscape: true,
    dialogClass: 'dialogWithDropShadow',
    close: function(event, ui) {
      $( "#dialogCreate" ).hide();
    },
    buttons: [
      {
        text: "Cancel",
        click: function() {
          show_file('levels.mysql.php?update=-1');
          $( this ).dialog( "close" );
        }
      },
      {
        text: "Add Level",
        click: function() {
          attach_file('levels.mysql.php', 0);
          $( this ).dialog( "close" );
        }
      }
    ]
  });

  $( "#dialogUpdate" ).dialog({
    autoOpen: false,
    modal: true,
    height: 225,
    width: 600,
    show: 'slide',
    hide: 'slide',
    closeOnEscape: true,
    dialogClass: 'dialogWithDropShadow',
    close: function(event, ui) {
      $( "#dialogUpdate" ).hide();
    },
    buttons: [
      {
        text: "Cancel",
        click: function() {
          show_file('levels.mysql.php?update=-1');
          $( this ).dialog( "close" );
        }
      },
      {
        text: "Update Level",
        click: function() {
          update_file('levels.mysql.php', 1);
          $( this ).dialog( "close" );
        }
      },
      {
        text: "Add Level",
        click: function() {
          update_file('levels.mysql.php', 0);
          $( this ).dialog( "close" );
        }
      }
    ]
  });
});

</script>

</head>
<body onLoad="clear_fields();" class="ui-widget-content">

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div id="main">

<table class="ui-styled-table">
<tr>
  <th class="ui-state-default">Level Management</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('level-help');">Help</a></th>
</tr>
</table>

<div id="level-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Level Form</strong>
  <ul>
    <li><strong>Level Name</strong> - The name assigned to an access level. This is displayed in a level selection drop down list.</li>
    <li><strong>Access Level</strong> - Defines the access level of the level. Various parts of the Inventory restrict access to view or edit pages based on this number. Levels for access permit lower numbered levels access. A level 3 access also permits level 2 and level 1 users but denies access to level 4 users.</li>
    <li><strong>Status</strong> - Disables this access level. Disabled levels will not be shown in the levels selection menus and will not have the access the level permits.</li>
  </ul></li>
</ul>

</div>

</div>

<table class="ui-styled-table">
<tr>
  <td class="ui-widget-content button"><input type="button" id="clickCreate" value="Add Level"></td>
</tr>
</table>

<span id="table_mysql"><?php print wait_Process('Waiting...')?></span>

</div>

</div>


<div id="dialogCreate" title="Add Levels">

<form name="formCreate">

<?php include('levels.dialog.php'); ?>

</form>

</div>


<div id="dialogUpdate" title="Edit Levels">

<form name="formUpdate">

<input type="hidden" name="id" value="0">

<?php include('levels.dialog.php'); ?>

</form>

</div>

<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
