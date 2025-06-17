<?php
# Script: class.php
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

  $package = "class.php";

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
<title>Manage Classifications</title>

<?php include($Sitepath . "/head.php"); ?>

<script type="text/javascript">
<?php
  if (check_userlevel($db, $AL_Admin)) {
?>
function delete_line( p_script_url ) {
  var answer = confirm("Delete this Classification?")

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

  af_url += "&cls_name="        + encode_URI(af_form.cls_name.value);
  af_url += "&cls_template="    + encode_URI(af_form.cls_template.value);
  af_url += "&cls_project="     + encode_URI(af_form.cls_project.value);
  af_url += "&cls_title="       + encode_URI(af_form.cls_title.value);
  af_url += "&cls_help="        + encode_URI(af_form.cls_help.value);

  script = document.createElement('script');
  script.src = p_script_url + af_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function update_file( p_script_url, update ) {
  var uf_form = document.formUpdate;
  var uf_url;

  uf_url  = '?update='   + update;
  uf_url += '&id='       + uf_form.id.value;

  uf_url += "&cls_name="        + encode_URI(uf_form.cls_name.value);
  uf_url += "&cls_template="    + encode_URI(uf_form.cls_template.value);
  uf_url += "&cls_project="     + encode_URI(uf_form.cls_project.value);
  uf_url += "&cls_title="       + encode_URI(uf_form.cls_title.value);
  uf_url += "&cls_help="        + encode_URI(uf_form.cls_help.value);

  script = document.createElement('script');
  script.src = p_script_url + uf_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function clear_fields() {
  show_file('class.mysql.php?update=-1');
}

$(document).ready( function() {
  $( '#clickCreate' ).click(function() {
    $( "#dialogCreate" ).dialog('open');
  });

  $( "#dialogCreate" ).dialog({
    autoOpen: false,
    modal: true,
    height: 275,
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
          show_file('class.mysql.php?update=-1');
          $( this ).dialog( "close" );
        }
      },
      {
        text: "Add Classification",
        click: function() {
          attach_file('class.mysql.php', 0);
          $( this ).dialog( "close" );
        }
      }
    ]
  });

  $( "#dialogUpdate" ).dialog({
    autoOpen: false,
    modal: true,
    height: 275,
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
          show_file('class.mysql.php?update=-1');
          $( this ).dialog( "close" );
        }
      },
      {
        text: "Update Classification",
        click: function() {
          update_file('class.mysql.php', 1);
          $( this ).dialog( "close" );
        }
      },
      {
        text: "Add Classification",
        click: function() {
          update_file('class.mysql.php', 0);
          $( this ).dialog( "close" );
        }
      }
    ]
  });
});

</script>

</head>
<body onload="clear_fields();" class="ui-widget-content">

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div id="main">

<table class="ui-styled-table">
<tr>
  <th class="ui-state-default">Classification Editor</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('class-help');">Help</a></th>
</tr>
</table>

<div id="class-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">


</div>

</div>

<table class="ui-styled-table">
<tr>
  <td class="ui-widget-content button"><input type="button" id="clickCreate" value="Add Classification"></td>
</tr>
</table>

<p></p>

<table class="ui-styled-table">
<tr>
  <th class="ui-state-default">Classification Listing</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('class-listing-help');">Help</a></th>
</tr>
</table>

<div id="class-listing-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">


</div>

</div>


<span id="table_mysql"><?php print wait_Process('Waiting...')?></span>

</div>


<div id="dialogCreate" title="Add Classifications">

<form name="formCreate">

<?php include('class.dialog.php'); ?>

</form>

</div>


<div id="dialogUpdate" title="Edit Classifications">

<form name="formUpdate">

<input type="hidden" name="id" value="0">

<?php include('class.dialog.php'); ?>

</form>

</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
