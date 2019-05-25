<?php
$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
require_once( $parse_uri[0] . 'wp-load.php' );
$tubeserver = $_GET['tubeserver'];
if (!stristr($tubeserver, 'get.google.com')){
  echo 'Invalid info.';
  exit;
}
function curl($url) {
	$ch = @curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	$head[] = "Connection: keep-alive";
	$head[] = "Keep-Alive: 300";
	$head[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
	$head[] = "Accept-Language: en-us,en;q=0.5";
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.124 Safari/537.36');
	curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_TIMEOUT, 60);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
	$page = curl_exec($ch);
	curl_close($ch);
	return $page;
}
function Picasa($link) {
	$links = str_replace('#038;', '', $link);
	$url = urldecode($links);
	if (stristr($url, '#')) list($url, $id) = explode('#', $url);
	$data = curl($url);
	if($id) $gach = explode($id, $data);
	$gach = explode('{"url":"', ($id)?$gach[7]:$data);
	$v360p = urldecode(reset(explode('"', $gach[2])));
	$v720p = urldecode(reset(explode('"', $gach[3])));
	$v1080p = urldecode(reset(explode('"', $gach[4])));
	if(strpos($v1080p, 'redirector') || strpos($v1080p, '=m32')){
		$js = "<source src='".$v1080p."' type='video/mp4' data-res='1080p'/><source src='".$v720p."' type='video/mp4' data-res='720p'/><source src='".$v360p."' type='video/mp4' data-res='360p'/>";
	} elseif(strpos($v720p, 'redirector') || strpos($v720p, '=m22')){
		$js .= "<source src='".$v720p."' type='video/mp4' data-res='720p'/><source src='".$v360p."' type='video/mp4' data-res='360p'/>";
	} else {
		$js .= "<source src='".$v360p."' type='video/mp4' data-res='360p'/>";
	}
	return $js;
}

$mp4 = $resultados[0];
$thumbnail = $resultados[1];


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
echo Picasa($tubeserver);
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