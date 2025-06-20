<?php
# Script: groups.php
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

  $package = "groups.php";

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
<title>Manage Groups</title>

<?php include($Sitepath . "/head.php"); ?>

<script type="text/javascript">
<?php
  if (check_userlevel($db, $AL_Admin)) {
?>
function delete_line( p_script_url ) {
  var question;
  var answer;

  question  = "The preference is to change the group status from Enabled to Disabled\n";
  question += "which prevents the orphaning of group owned or identified information. Deleting\n";
  question += "the group should be done when removing duplicate records or if you know the \n";
  question += "group has no group managed information.\n\n";

  question += "Delete this Group anyway?";

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

  af_url += "&grp_name="          + encode_URI(af_form.grp_name.value);
  af_url += "&grp_manager="       + af_form.grp_manager.value;
  af_url += "&grp_email="         + encode_URI(af_form.grp_email.value);
  af_url += "&grp_disabled="      + af_form.grp_disabled.value;
  af_url += "&grp_report="        + encode_URI(af_form.grp_report.value);

  script = document.createElement('script');
  script.src = p_script_url + af_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function update_file( p_script_url, update ) {
  var uf_form = document.formUpdate;
  var uf_url;

  uf_url  = '?update='   + update;
  uf_url += '&id='       + uf_form.id.value;

  uf_url += "&grp_name="          + encode_URI(uf_form.grp_name.value);
  uf_url += "&grp_manager="       + uf_form.grp_manager.value;
  uf_url += "&grp_email="         + encode_URI(uf_form.grp_email.value);
  uf_url += "&grp_disabled="      + uf_form.grp_disabled.value;
  uf_url += "&grp_report="        + encode_URI(uf_form.grp_report.value);

  script = document.createElement('script');
  script.src = p_script_url + uf_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function clear_fields() {
  show_file('groups.mysql.php?update=-1');
}

$(document).ready( function() {
  $( '#clickCreate' ).click(function() {
    $( "#dialogCreate" ).dialog('open');
  });

  $( "#dialogCreate" ).dialog({
    autoOpen: false,
    modal: true,
    height: 250,
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
          show_file('groups.mysql.php?update=-1');
          $( this ).dialog( "close" );
        }
      },
      {
        text: "Add Group",
        click: function() {
          attach_file('groups.mysql.php', 0);
          $( this ).dialog( "close" );
        }
      }
    ]
  });

  $( "#dialogUpdate" ).dialog({
    autoOpen: false,
    modal: true,
    height: 250,
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
          show_file('groups.mysql.php?update=-1');
          $( this ).dialog( "close" );
        }
      },
      {
        text: "Update Group",
        click: function() {
          update_file('groups.mysql.php', 1);
          $( this ).dialog( "close" );
        }
      },
      {
        text: "Add Group",
        click: function() {
          update_file('groups.mysql.php', 0);
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
  <th class="ui-state-default">Group Management</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('group-help');">Help</a></th>
</tr>
</table>

<div id="group-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Group Form</strong>
  <ul>
    <li><strong>Group Name</strong> - The name as presented in any group selection drop down.</li>
    <li><strong>E-Mail</strong> - The e-mail address for this group. This is used by RSDP for example to send tasks to the group.</li>
    <li><strong>Status</strong> - Change the status of the group here. Disabled groups will not be shown in the group selection menus.</li>
  </ul></li>
</ul>

</div>

</div>


<table class="ui-styled-table">
<tr>
  <td class="ui-widget-content button"><input type="button" id="clickCreate" value="Add Group"></td>
</tr>
</table>


<span id="table_mysql"><?php print wait_Process('Waiting...')?></span>

</div>


<div id="dialogCreate" title="Add Group">

<form name="formCreate">

<?php include('groups.dialog.php'); ?>

</form>

</div>


<div id="dialogUpdate" title="Edit Group">

<form name="formUpdate">

<input type="hidden" name="id" value="0">

<?php include('groups.dialog.php'); ?>

</form>

</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
