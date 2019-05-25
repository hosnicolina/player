<?php
$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
require_once( $parse_uri[0] . 'wp-load.php' );
$tubeserver = base64_decode($_GET['tubeserver']);


$mp4 = $tubeserver;


?>
<link href="../video-js/video-js.min.css" rel="stylesheet">
<script src="../video-js/video.js"></script>
<!--plugins-->
<script src='../video-js/plugins/videojs.logobrand.js'></script>
<script src='../video-js/plugins/video-quality-selector.js'></script>
<link href="../video-js/plugins/videojs.logobrand.css" rel="stylesheet">
<!--/plugins-->

<style>
body{
  margin: 0;
  padding: 0;
}
#parent{
  position: relative;
}
#child {
  width: 320px;
  height: 300px;
  text-align: center;
  overflow: auto;
  margin: auto;
  top: 0; left: 0; bottom: 0; right: 0;
  position: fixed;
  z-index: 999;
}
.texto{
  margin: 0;
  padding: 0;
  font-size: 14px;
  font-weight: bold;
  color: #333;
  background: #fff;
  height: 10px;
}
</style>

<div id="parent">

<?php $ads = get_option( 'kenplayer_ads' ); if($ads != ''){ 
$seconds = get_option( 'kenplayer_seconds' ); if(!$seconds){ $seconds = 10; }
?>
<div id="child">
  <span class="texto">This banner will close after <?php echo $seconds; ?> seconds.</span>
  <a href='#' onclick='this.parentNode.parentNode.removeChild(this.parentNode)'><img src="fancy_close.png"/></a>
  <?php echo $ads; ?>
</div>
<?php } ?>

<?php
$uploads = wp_upload_dir();
?>
<video id="video" class="video-js vjs-default-skin vjs-big-play-centered"
  controls preload="auto" width="100%" height="100%"
  poster=""
  data-setup='{"example_option":true}'>
  
<?php
if (endsWith($tubeserver, '.flv')){
?>
 <source src="<?php echo $mp4; ?>" type='video/x-flv' />
<?php
} else {
?>
 <source src="<?php echo $mp4; ?>" type='video/mp4' />
<?php
}
?>
</video>

</div><!--/.parent-->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

<script>
video = document.querySelector('video');
            player = videojs(video);
            player.logobrand({
                image: "<?php echo get_option( 'kenplayer_logo' ); ?>",
                destination: "<?php echo get_site_url(); ?>"
            });
setTimeout(function() {
    $('#child').fadeOut('fast');
}, <?php echo ($seconds*1100); ?>); 
</script>
<!--Kenplayer direct v1.9-->