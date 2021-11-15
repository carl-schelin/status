<?php

  header('Content-Type: text/javascript');

  function clean($input, $maxlength)
  {
    $input = substr($input, 0, $maxlength);
    $input = escapeshellcmd($input);
    return ($input);
  }

  $formVars['loop']     = trim(clean($_GET['loop'], 10));
  $formVars['build']    = trim(clean($_GET['build'], 10));

  if ($formVars['build'] == 1) {
    $output  = "<div style=\"overflow: hidden;\" id=\"mansion" . $formVars['loop'] . "\" class=\"hide_me\">";
    $output .= "  <div class=textcontent>";
    $output .= "    <table width=100%>";
    $output .= "    <tr>";
    $output .= "      <th colspan=2>Are you alone?</th>";
    $output .= "    </tr>";
    $output .= "    <tr>";
    $output .= "      <td colspan=2><hr></td>";
    $output .= "    </tr>";
    $output .= "    <tr>";
    $output .= "      <th><a href=\"#\" onclick=\"javascript:attach_file(\'index.puzzle.php?loop=1\'); slide(\'puzzles\',100); slide(\'game0\',100); return false;\" class=\"trigger\">Yes</a></th>";
    $output .= "      <th><a href=\"#\" onclick=\"javascript:attach_file(\'index.mansion.php?loop=" . ($formVars['loop'] + 1) . "&build=2\'); slide(\'splitup" . $formVars['loop'] . "\',100); return false;\" class=\"trigger\">No</a></th>";
    $output .= "    </tr>";
    $output .= "    </table>";
    $output .= "  </div>";
    $output .= "";
    $output .= "</div>";
    $output .= "";
    $output .= "<span id=\"splituploop" . ($formVars['loop'] + 1) . "\"></span>";
    $target = "mansionloop" . $formVars['loop'];
  }

  if ($formVars['build'] == 2) {
    $output = "<div style=\"overflow: hidden;\" id=\"splitup" . $formVars['loop'] . "\" class=\"hide_me\">";
    $output .= "";
    $output .= "  <div class=textcontent>";
    $output .= "    <table width=100%>";
    $output .= "    <tr>";
    $output .= "      <th><a href=\"#\" onClick=\"javascript:attach_file(\'index.mansion.php?loop=" . ($formVars['loop'] + 1) . "&build=1\'); slide(\'mansion" . $formVars['loop'] . "\',100); return false;\" class=\"trigger\">Time to split up!</a></th>";
    $output .= "    </tr>";
    $output .= "    </table>";
    $output .= "  </div>";
    $output .= "";
    $output .= "</div>";
    $output .= "";
    $output .= "<span id=\"mansionloop" . ($formVars['loop'] + 1) . "\"></span>";
    $target = "splituploop" . $formVars['loop'];
  }

?>

document.getElementById('<?php print $target; ?>').innerHTML = '<?php echo $output; ?>';

