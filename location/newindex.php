<?php include('header.php'); ?>
<script type="text/javascript" src="slide.js"></script>

<script type="text/javascript">

function attach_file( p_script_url ) {
  // create new script element, set its relative URL, and load it
  script = document.createElement('script');
  script.src = p_script_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

</script>

<div class=textcontent>
  <h1><a href="#" onclick="slide('location',100); return false;" class="trigger">Where Are You?</a></h1>
</div>

<div style="overflow: hidden;" id="location" class="hide_me">

  <div class=textcontent>
    <table width=100%>
    <tr>
      <th><a href="#" onclick="slide('lab',100); return false;" class="trigger">Some Lab</a></th>
      <th><a href="#" onclick="slide('space',100); slide('space2',100); return false;" class="trigger">Space</a></th>
      <th><a href="#" onclick="slide('rural',100); return false;" class="trigger">Rural Town</a></th>
      <th><a href="#" onClick="javascript:attach_file('index.mansion.php?loop=0&build=1'); slide('mansion0',100); return false;" class="trigger">Mansion</a></th>
      <th><a href="#" onclick="slide('barn',100); return false;" class="trigger">Last Thing I Remember is a Crash...</a></th>
      <th><a href="#" onclick="slide('mall',100); slide('mallnext',100); return false;" class="trigger">Shopping Mall</a></th>
    </tr>
    </table>
  </div>

</div>

<div style="overflow: hidden;" id="barn" class="hide_me">

  <div class=textcontent>
    <table width=100%>
    <tr>
      <th colspan=2>Are you in a barn?</th></th>
    </tr>
    <tr>
      <td colspan=2><hr></td>
    </tr>
    <tr>
      <th><a href="#" onclick="slide('barnyes',100); return false;" class="trigger">Yes</a></th>
      <th><a href="#" onclick="slide('shed',100); slide('barnyes',100); return false;" class="trigger">No, it's a shed</a></th>
    </tr>
    </table>
  </div>

</div>

<div style="overflow: hidden;" id="shed" class="hide_me">

  <div class=textcontent>
    <table width=100%>
    <tr>
      <th colspan=2>Close enough, wise ass</th></th>
    </tr>
    </table>
  </div>

</div>

<div style="overflow: hidden;" id="barnyes" class="hide_me">

  <div class=textcontent>
    <table width=100%>
    <tr>
      <th colspan=2>Is it 1943?</th></th>
    </tr>
    <tr>
      <td colspan=2><hr></td>
    </tr>
    <tr>
      <th><a href="#" onclick="slide('1943yes',100); slide('youaredead',100); return false;" class="trigger">Yes</a></th>
      <th><a href="#" onclick="slide('mcfly',100);return false;" class="trigger">No, it's 1955</a></th>
    </tr>
    </table>
  </div>

</div>

<div style="overflow: hidden;" id="1943yes" class="hide_me">

  <div class=textcontent>
    <h3>OMG</h3>
    <h4>Nazi Zombies!</h4>
  </div>

</div>

<div style="overflow: hidden;" id="mcfly" class="hide_me">

  <div class=textcontent>
    <table width=100%>
    <tr>
      <th>You are Marty McFly</th></th>
    </tr>
    </table>
  </div>

</div>


<div style="overflow: hidden;" id="mall" class="hide_me">

  <div class=textcontent>
    <h3>OMG</h3>
    <h4>Zombie Consumers!</h4>
  </div>

</div>

<div style="overflow: hidden;" id="mallnext" class="hide_me">

  <div class=textcontent>

    <table width=100%>
    <tr>
      <th colspan=4>Who do you team up with?</th>
    </tr>
    <tr>
      <td colspan=4><hr></td>
    </tr>
    <tr>
      <th><a href="#" onclick="slide('couple',100); slide('youaredead',100); return false;" class="trigger">The endearing old couple</a></th>
      <th><a href="#" onclick="slide('coed',100); return false;" class="trigger">The promiscuous co-ed</a></th>
      <th><a href="#" onclick="slide('janitor',100); return false;" class="trigger">The janitor with the walkie-talkie</a></th>
      <th><a href="#" onclick="slide('cop',100); return false;" class="trigger">Disgraced ex-cop searching for his daughter</a></th>
    </tr>
    </table>
  </div>

</div>

<div style="overflow: hidden;" id="couple" class="hide_me">

  <div class=textcontent>
    <table width=100%>
    <tr>
      <th>That was stupid</th>
    </tr>
    </table>
  </div>

</div>

<div style="overflow: hidden;" id="coed" class="hide_me">

  <div class=textcontent>
    <table width=100%>
    <tr>
      <th><a href="#" onclick="slide('coed2',100); return false;" class="trigger">Barricade selves in food court, defile Orange Julius stand</a></th>
    </tr>
    </table>
  </div>

</div>

<div style="overflow: hidden;" id="coed2" class="hide_me">

  <div class=textcontent>
    <table width=100%>
    <tr>
      <th><a href="#" onclick="slide('coed3',100); slide('youaredead',100); return false;" class="trigger">Zombies break your barrier of chairs and KFC buckets.</a></th>
    </tr>
    </table>
  </div>

</div>

<div style="overflow: hidden;" id="coed3" class="hide_me">

  <div class=textcontent>
    <h2>Totally worth it</h2>
  </div>

</div>


<div style="overflow: hidden;" id="janitor" class="hide_me">

  <div class=textcontent>
    <table width=100%>
    <tr>
      <th><a href="#" onclick="slide('janitor2',100); return false;" class="trigger">Listen to his life story</a></th>
    </tr>
    </table>
  </div>

</div>

<div style="overflow: hidden;" id="janitor2" class="hide_me">

  <div class=textcontent>
    <table width=100%>
    <tr>
      <th><a href="#" onclick="slide('janitor3',100); return false;" class="trigger">Take maintenence tunnels to roof where rescue awaits</a></th>
    </tr>
    </table>
  </div>

</div>

<div style="overflow: hidden;" id="janitor3" class="hide_me">

  <div class=textcontent>
    <table width=100%>
    <tr>
      <th colspan=2>Last stand against zombie horde</th>
    </tr>
    <tr>
      <td colspan=2><hr></td>
    </tr>
    <tr>
      <th><a href="#" onclick="slide('youaredead',100); return false;" class="trigger">Help Janitor</a></th>
      <th><a href="#" onclick="slide('janitor4',100); return false;" class="trigger">Leave janitor</a></th>
    </tr>
    </table>
  </div>

</div>

<div style="overflow: hidden;" id="janitor4" class="hide_me">

  <div class=textcontent>
    <table width=100%>
    <tr>
      <th><a href="#" onclick="slide('helicopter',100); return false;" class="trigger">Shed single tear</a></th>
    </tr>
    </table>
  </div>

</div>

<div style="overflow: hidden;" id="cop" class="hide_me">

  <div class=textcontent>
    <table width=100%>
    <tr>
      <th><a href="#" onclick="slide('cop2',100); return false;" class="trigger">Provide comic relief while cop does all the killing</a></th>
    </tr>
    </table>
  </div>

</div>

<div style="overflow: hidden;" id="cop2" class="hide_me">

  <div class=textcontent>
    <table width=100%>
    <tr>
      <th><a href="#" onclick="slide('cop3',100); return false;" class="trigger">Cop helps you believe in yourself as you make it to the roof</a></th>
    </tr>
    </table>
  </div>

</div>

<div style="overflow: hidden;" id="cop3" class="hide_me">

  <div class=textcontent>
    <table width=100%>
    <tr>
      <th colspan=2>Last stand against zombie horde</th>
    </tr>
    <tr>
      <td colspan=2><hr></td>
    </tr>
    <tr>
      <th><a href="#" onclick="slide('youaredead',100); return false;" class="trigger">Cop abandons you</a></th>
      <th><a href="#" onclick="slide('youaredead',100); return false;" class="trigger">Cop abandons you, aim for chopper's gas tank in retaliation</a></th>
    </tr>
    </table>
  </div>

</div>

<div style="overflow: hidden;" id="lab" class="hide_me">

  <div class=textcontent>
    <table width=100%>
    <tr>
      <th colspan=2>What do you see?</th>
    </tr>
    <tr>
      <td colspan=2><hr></td>
    </tr>
    <tr>
      <th><a href="#" onclick="slide('cutscene',100); return false;" class="trigger">An unskippable cutscene starring the recurring villain</a></th>
      <th><a href="#" onclick="slide('incubation',100); return false;" class="trigger">Fleshy, asymmetrical monsters floating in massive incubation tubes</a></th>
    </tr>
    </table>
  </div>

</div>

<div style="overflow: hidden;" id="cutscene" class="hide_me">

  <div class=textcontent>
    <table width=100%>
    <tr>
      <th colspan=2>Cut to preachy monologue about the nature of man</th>
    </tr>
    <tr>
      <td colspan=2><hr></td>
    </tr>
    <tr>
      <th><a href="#" onclick="slide('youaredead',100); return false;" class="trigger">This is boring, I'm going to make a sandwich</a></th>
      <th><a href="#" onclick="slide('selfdestruct',100); slide('helicopter',100); return false;" class="trigger">Activate self-destruct while villain is distracted</a></th>
    </tr>
    </table>
  </div>

</div>

<div style="overflow: hidden;" id="incubation" class="hide_me">

  <div class=textcontent>
    <table width=100%>
    <tr>
      <th colspan=2>Have they escaped?</th>
    </tr>
    <tr>
      <td colspan=2><hr></td>
    </tr>
    <tr>
      <th><a href="#" onclick="slide('selfdestruct',100); slide('helicopter',100); return false;" class="trigger">Yes</a></th>
      <th><a href="#" onclick="slide('fine',100); slide('uhoh',100); return false;" class="trigger">No</a></th>
    </tr>
    </table>
  </div>

</div>

<div style="overflow: hidden;" id="fine" class="hide_me">

  <div class=textcontent>
    <table width=100%>
    <tr>
      <th>Everything is probably fine</th>
    </tr>
    </table>
  </div>

</div>

<div style="overflow: hidden;" id="uhoh" class="hide_me">

  <div class=textcontent>
    <table width=100%>
    <tr>
      <th>Uh-oh</th>
    </tr>
    </table>
  </div>

</div>

<div style="overflow: hidden;" id="selfdestruct" class="hide_me">

  <div class=textcontent>
    <h3>OMG</h3>
    <h4>Zombie Boss Rocket Launcher Fight!</h4>
  </div>

</div>

<div style="overflow: hidden;" id="space" class="hide_me">

  <div class=textcontent>
    <table width=100%>
    <tr>
      <th>Complain about being in space</th>
    </tr>
    </table>
  </div>

</div>

<div style="overflow: hidden;" id="space2" class="hide_me">

  <div class=textcontent>
    <table width=100%>
    <tr>
      <th colspan=3>Why are you there?</th>
    </tr>
    <tr>
      <td colspan=3><hr></td>
    </tr>
    <tr>
      <th><a href="#" onclick="slide('girlfriend',100); slide('girlfriend2',100); return false;" class="trigger">Searching for lost girlfriend</a></th>
      <th><a href="#" onclick="slide('beacon',100); return false;" class="trigger">Answering distress beacon</a></th>
      <th><a href="#" onclick="slide('thawed',100); slide('youaredead',100); return false;" class="trigger">I don't know, I just thawed</a></th>
    </tr>
    </table>
  </div>

</div>

<div style="overflow: hidden;" id="girlfriend" class="hide_me">

  <div class=textcontent>
    <h3>OMG</h3>
    <h4>Zombie Girlfriend!</h4>
  </div>

</div>

<div style="overflow: hidden;" id="girlfriend2" class="hide_me">

  <div class=textcontent>
    <table width=100%>
    <tr>
      <th colspan=2>How attached to her are you?</th>
    </tr>
    <tr>
      <td colspan=2><hr></td>
    </tr>
    <tr>
      <th><a href="#" onclick="slide('girlfriend3',100); slide('youaredead'); return false;" class="trigger">Love of my life</a></th>
      <th><a href="#" onclick="slide('meh',100); return false;" class="trigger">Meh...</a></th>
    </tr>
    </table>
  </div>

</div>

<div style="overflow: hidden;" id="girlfriend3" class="hide_me">

  <div class=textcontent>
    <h3>OMG</h3>
    <h4>Zombie Honeymoon!</h4>
  </div>

</div>

<div style="overflow: hidden;" id="meh" class="hide_me">

  <div class=textcontent>
    <table width=100%>
    <tr>
      <th><a href="#" onclick="slide('collision'); return false;" class="trigger">"It's not you, it's me," reach for breakup shotgun</a></th>
    </tr>
    </table>
  </div>

</div>

<div style="overflow: hidden;" id="beacon" class="hide_me">

  <div class=textcontent>
    <table width=100%>
    <tr>
      <th><a href="#" onclick="slide('trap',100); return false;" class="trigger">It's a trap, obviously</a></th>
    </tr>
    </table>
  </div>

</div>

<div style="overflow: hidden;" id="trap" class="hide_me">

  <div class=textcontent>
    <h3>OMG</h3>
    <h4>Space Zombies!</h4>
    <table width=100%>
    <tr>
      <th><a href="#" onclick="slide('collision',100); return false;" class="trigger">Dismember undead crew</a></th>
      <th><a href="#" onclick="slide('youaredead',100); return false;" class="trigger">Space weapons are hard</a></th>
    </tr>
    </table>
  </div>

</div>

<div style="overflow: hidden;" id="collision" class="hide_me">

  <div class=textcontent>
    <table width=100%>
    <tr>
      <th><a href="#" onclick="slide('tentacle',100); slide('escape',100); return false;" class="trigger">Set collision course for sun</a></th>
    </tr>
    </table>
  </div>

</div>

<div style="overflow: hidden;" id="tentacle" class="hide_me">

  <div class=textcontent>
    <h3>OMG</h3>
    <h4>Tentacle Boss Zombie!</h4>
  </div>

</div>

<div style="overflow: hidden;" id="escape" class="hide_me">

  <div class=textcontent>
    <table width=100%>
    <tr>
      <th><a href="#" onclick="slide('gorural',100); history.go(0);">Escape via pod and crash land</a></th>
    </tr>
    </table>
  </div>

</div>

<div style="overflow: hidden;" id="thawed" class="hide_me">

  <div class=textcontent>
    <h3>OMG</h3>
    <h4>I'm Naked And That's A Zombie!</h4>
  </div>

</div>

<div style="overflow: hidden;" id="rural" class="hide_me">

  <div class=textcontent>
    <table width=100%>
    <tr>
      <th colspan=2>Are you with...</th>
    </tr>
    <tr>
      <td colspan=2><hr></td>
    </tr>
    <tr>
      <th><a href="#" onclick="slide('military',100); return false;" class="trigger">The military</a></th>
      <th><a href="#" onclick="slide('group',100); return false;" class="trigger">A biker, veteran, student, and systems analyst</a></th>
    </tr>
    </table>
  </div>

</div>

<div style="overflow: hidden;" id="military" class="hide_me">

  <div class=textcontent>
    <table width=100%>
    <tr>
      <th colspan=2>What are they armed with?</th>
    </tr>
    <tr>
      <td colspan=2><hr></td>
    </tr>
    <tr>
      <th><a href="#" onclick="slide('dreamcast',100); slide('youaredead',100); return false;" class="trigger">Dreamcast keyboards</a></th>
      <th><a href="#" onclick="slide('guns',100); return false;" class="trigger">Guns</a></th>
    </tr>
    </table>
  </div>

</div>

<div style="overflow: hidden;" id="dreamcast" class="hide_me">

  <div class=textcontent>
    <table width=100%>
    <tr>
      <th>Words per minute < 13</th>
    </tr>
    </table>
  </div>

</div>

<div style="overflow: hidden;" id="guns" class="hide_me">

  <div class=textcontent>
    <table width=100%>
    <tr>
      <th><a href="#" onclick="slide('armor',100); return false;" class="trigger">...but no armor</a></th>
    </tr>
    </table>
  </div>

</div>

<div style="overflow: hidden;" id="armor" class="hide_me">

  <div class=textcontent>
    <table width=100%>
    <tr>
      <th colspan=2>Listen to them whine about inevitable flesh wound</th>
    </tr>
    <tr>
      <td colspan=2><hr></td>
    </tr>
    <tr>
      <th><a href="#" onclick="slide('group',100); return false;" class="trigger">Shoot them in the face</a></th>
      <th><a href="#" onclick="slide('wait',100); slide('fodder',100); return false;" class="trigger">Wait it out</a></th>
    </tr>
    </table>
  </div>

</div>

<div style="overflow: hidden;" id="wait" class="hide_me">

  <div class=textcontent>
    <h3>OMG</h3>
    <h4>Ex-comrade Zombie!</h4>
  </div>

</div>

<div style="overflow: hidden;" id="fodder" class="hide_me">

  <div class=textcontent>
    <table width=100%>
    <tr>
      <th><a href="#" onclick="slide('lovebite',100); slide('youaredead',100); return false;" class="trigger">Sgt. Fodder gives you love bite</a></th>
    </tr>
    </table>
  </div>

</div>

<div style="overflow: hidden;" id="lovebite" class="hide_me">

  <div class=textcontent>
    <table width=100%>
    <tr>
      <th>Start feeling ill, chronicle symptoms in diary</th>
    </tr>
    </table>
  </div>

</div>

<div style="overflow: hidden;" id="group" class="hide_me">

  <div class=textcontent>
    <table width=100%>
    <tr>
      <th colspan=2>Seek shelter at...</th>
    </tr>
    <tr>
      <td colspan=2><hr></td>
    </tr>
    <tr>
      <th><a href="#" onclick="slide('parents',100); return false;" class="trigger">Parents' house</a></th>
      <th><a href="#" onclick="slide('motel',100); return false;" class="trigger">Motel</a></th>
    </tr>
    </table>
  </div>

</div>

<div style="overflow: hidden;" id="parents" class="hide_me">

  <div class=textcontent>
    <table width=100%>
    <tr>
      <th><a href="#" onclick="slide('parents2',100); return false;" class="trigger">Is that a zombie on the lawn?</a></th>
    </tr>
    </table>
  </div>

</div>

<div style="overflow: hidden;" id="parents2" class="hide_me">

  <div class=textcontent>
    <table width=100%>
    <tr>
      <th><a href="#" onclick="slide('parents3',100); slide('parents4',100); slide('youaredead',100); return false;" class="trigger">We don't want zombies on the lawn</a></th>
    </tr>
    </table>
  </div>

</div>

<div style="overflow: hidden;" id="parents3" class="hide_me">

  <div class=textcontent>
    <h3>OMG</h3>
    <h4>Plants vs. Zombies!</h4>
  </div>

</div>

<div style="overflow: hidden;" id="parents4" class="hide_me">

  <div class=textcontent>
    <table width=100%>
    <tr>
      <th>Not enough sun!</th>
    </tr>
    </table>
  </div>

</div>

<div style="overflow: hidden;" id="motel" class="hide_me">

  <div class=textcontent>
    <table width=100%>
    <tr>
      <th><a href="#" onclick="slide('motel2',100); return false;" class="trigger">Talk to manager with glass eye about End of Days</a></th>
    </tr>
    </table>
  </div>

</div>

<div style="overflow: hidden;" id="motel2" class="hide_me">

  <div class=textcontent>
    <table width=100%>
    <tr>
      <th><a href="#" onclick="slide('motel3',100); return false;" class="trigger">Cur preachy monologue about the nature of man</a></th>
    </tr>
    </table>
  </div>

</div>

<div style="overflow: hidden;" id="motel3" class="hide_me">

  <div class=textcontent>
    <table width=100%>
    <tr>
      <th><a href="#" onclick="slide('motel4',100); return false;" class="trigger">Impolitely seize glass eye and stash in inventory</a></th>
    </tr>
    </table>
  </div>

</div>

<div style="overflow: hidden;" id="motel4" class="hide_me">

  <div class=textcontent>
    <table width=100%>
    <tr>
      <th><a href="#" onclick="slide('gomansion',100); history.go(0);">Conveniently stumble upon large iron gate with eye-shaped hole</a></th>
    </tr>
    </table>
  </div>

</div>

<div style="overflow: hidden;" id="gomansion" class="hide_me">

  <div class=textcontent>
    <table width=100%>
    <tr>
      <th>Go to Mansion</th>
    </tr>
    </table>
  </div>

</div>

<span id="mansionloop0"></span>

<div style="overflow: hidden;" id="puzzles" class="hide_me">

  <div class=textcontent>
    <h3>OMG</h3>
    <h4>Puzzles!</h4>
  </div>

</div>

<span id="puzzleloop1"></span>

<div style="overflow: hidden;" id="runfight" class="hide_me">

  <div class=textcontent>
    <h3>OMG</h3>
    <h4>Zombies!</h4>
  </div>

</div>

<div style="overflow: hidden;" id="nowwhat" class="hide_me">

  <div class=textcontent>
    <table width=100%>
    <tr>
      <th><a href="#" onclick="slide('run',100); return false;" class="trigger">Run!</a></th>
      <th><a href="#" onclick="slide('fight',100); return false;" class="trigger">Fight!</a></th>
    </tr>
    </table>
  </div>

</div>

<div style="overflow: hidden;" id="run" class="hide_me">

  <div class=textcontent>
    <table width=100%>
    <tr>
      <th><a href="#" onclick="slide('youaredead',100); return false;" class="trigger">Struggle with tank controls</a></th>
    </tr>
    </table>
  </div>

</div>

<div style="overflow: hidden;" id="fight" class="hide_me">

  <div class=textcontent>
    <table width=100%>
    <tr>
      <th><a href="#" onclick="slide('youaredead',100); return false;" class="trigger">Crap, I exchanged my ammo for an ink ribbon</a></th>
      <th><a href="#" onclick="slide('fight2',100); return false;" class="trigger">Battle through zombie hoard</a></th>
    </tr>
    </table>
  </div>

</div>

<div style="overflow: hidden;" id="fight2" class="hide_me">

  <div class=textcontent>
    <table width=100%>
    <tr>
      <th><a href="#" onclick="slide('fight3',100); return false;" class="trigger">What is this, some kind of elevator?</a></th>
    </tr>
    </table>
  </div>

</div>

<div style="overflow: hidden;" id="fight3" class="hide_me">

  <div class=textcontent>
    <table width=100%>
    <tr>
      <th><a href="#" onclick="slide('fight4',100); return false;" class="trigger">What is this, some kind of tunnel?</a></th>
    </tr>
    </table>
  </div>

</div>

<div style="overflow: hidden;" id="fight4" class="hide_me">

  <div class=textcontent>
    <table width=100%>
    <tr>
      <th><a href="#" onclick="slide('fight5',100); return false;" class="trigger">What is this, some kind of ladder?</a></th>
    </tr>
    </table>
  </div>

</div>

<div style="overflow: hidden;" id="fight5" class="hide_me">

  <div class=textcontent>
    <table width=100%>
    <tr>
      <th><a href="#" onclick="slide('golab',100); history.go(0);">What is this, some kind of hand trolly? Are you serious?</a></th>
    </tr>
    </table>
  </div>

</div>

<div style="overflow: hidden;" id="golab" class="hide_me">

  <div class=textcontent>
    <table width=100%>
    <tr>
      <th>Go to Some Lab</th>
    </tr>
    </table>
  </div>

</div>

<div style="overflow: hidden;" id="helicopter" class="hide_me">

  <div class=textcontent>
    <table width=100%>
    <tr>
      <th><a href="#" onclick="slide('sequel',100); return false;" class="trigger">Escape on helicopter</a></th>
    </tr>
    </table>
  </div>

</div>

<div style="overflow: hidden;" id="sequel" class="hide_me">

  <div class=textcontent>
    <table width=100%>
    <tr>
      <th><a href="#" onclick="slide('gorural',100); history.go(0);">Sequel</a></th>
    </tr>
    </table>
  </div>

</div>

<div style="overflow: hidden;" id="gorural" class="hide_me">

  <div class=textcontent>
    <table width=100%>
    <tr>
      <th>Go to Rural Town</th>
    </tr>
    </table>
  </div>

</div>

<div style="overflow: hidden;" id="youaredead" class="hide_me">

  <div class=textcontent>
    <h1>You Are Dead!</h1>
  </div>

</div>

<?php include('footer.php'); ?>
