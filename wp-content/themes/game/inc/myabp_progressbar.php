<?php
/**
 * MyArcadePlugin Pro Theme Progress bar helps theme developers to create MyArcadePlugin Pro compatible theme backends.
 * @package MyArcadePlugin Pro Theme Progressbar
 * @author Onedin Ibrocevic - http://myarcadeblogthemes.com
 * @version 1.1
 */
?>
<script type="text/javascript">
  var zaehler = 1;
  
  function loadgame(ziel) {
    var loadtext=document.getElementById('progressbarloadtext').style;
    var percentlimit = <?php echo get_option('braygames_progressbartextloadlimit'); ?>;
    var speedindex = <?php echo get_option('braygames_progressbarspeedindex'); ?>;
    var percentlimitstatus = "<?php echo get_option('braygames_progressbartextloadstatus'); ?>";
    speedindex = speedindex*2;
    if ( zaehler < ziel) { 
      zaehler = zaehler + 1; 
      document.getElementById("progressbarloadbg").style.width = zaehler + "px"; 
      var prozent = Math.round( zaehler / ziel * 100); 
      document.getElementById("progresstext").innerHTML = prozent+" %"; 
      window.setTimeout("loadgame('" + ziel + "')", speedindex ); 
      if ( (prozent >= percentlimit) & (percentlimitstatus == "enable" ) ) {
        loadtext.display='block';
      }	
    } 
    else { 
      zaehler = 1;
      window.hide(); 
    }
  }

  function hide() {
    var showprogressbar=document.getElementById('showprogressbar').style;
    var loadtext=document.getElementById('progressbarloadtext').style;
    var game = document.getElementById('my_game').style;

    showprogressbar.display='none'; 
    loadtext.display='none';
    game.width = '100%';
    game.height = '100%';

    zaehler = 400;
  }
  
  jQuery(document).ready( function() {
    setTimeout('loadgame(400)', <?php echo get_option('braygames_progressbardelay'); ?>); 
  });
</script>

<?php ?>