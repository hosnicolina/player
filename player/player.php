<?php
$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
require_once( $parse_uri[0] . 'wp-load.php' );
$tubeserver = strip_tags($_GET['tubeserver']);
$video = strip_tags($_GET['id']);
if(!ctype_alnum($tubeserver)){
  echo 'Invalid info.';
  exit;
}

function curl($url, $referer, $type=null){
	$agent = ($type != null && $type = 'movil') ? 'Mozilla/5.0 (Linux; U; Android 4.0; en-us; GT-I9300 Build/IMM76D)' : 'Mozilla/5.0(Windows;U;WindowsNT5.0;en-US;rv:1.4)Gecko/20030624Netscape/7.1(ax)';
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_USERAGENT, $agent);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	curl_setopt($ch, CURLOPT_REFERER, $referer);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	$page = curl_exec($ch);
	curl_close($ch);
	return $page;
}
function getstring($string,$start,$end){
$str = explode($start,$string);
$str = explode($end,$str[1]);
return $str[0];
}

function get_match_all($data, $start, $end) {
	$data   = preg_replace('/\s+/',' ',$data);
	preg_match_all("#".$start."(.*?)".$end."#si", $data, $newPattern, PREG_SET_ORDER);	
	return $newPattern;
}
function obtenerVideo($tubeserver, $video){
  if($tubeserver == 'xvideos'){
    $userAgent  = array('http' => array('user_agent' => 'Mozilla/5.0 (Linux; U; Android 4.0; en-us; GT-I9300 Build/IMM76D)'));
    $str = @file_get_contents('http://www.xvideos.com/video'.$video.'/', false, stream_context_create($userAgent) );
	//$str = curl("http://www.xvideos.com/video".$video."/", "http://www.xvideos.com", 'movil');
	
    if(!$str){return false;}
    $VideoUrlLow=getstring($str,"html5player.setVideoUrlLow('","');");
    $VideoUrlHigh=getstring($str,"html5player.setVideoUrlHigh('","');");
    $VideoUrlHD=getstring($str,"html5player.setVideoHLS('","');");
    if($VideoUrlHigh!=""){$mp4=$VideoUrlHigh;} else {$mp4=$VideoUrlLow;}
	if($VideoUrlHD!=""){$mp4=$VideoUrlHD;}
	/* using toolshot 
	$data_get=fetchUrl("http://player1.toolshot.com/?url=http://www.xvideos.com/video".$video."/", "http://www.xvideos.com", 'movil');
	preg_match('#source[^>]+src="([^"]+)"#mis', $data_get, $match);
	$mp4=$match[1];
	/* end using toolshot */
    $thumbnail = getstring($str,'<meta property="og:image" content="','"');

	
  }elseif($tubeserver == 'foxtube'){
    $url = 'http://r.foxtube.com/'.$video.'/es/';
    $str = file_get_contents($url);
    preg_match("/var v_path = '(.*)';/", $str, $mp4);
    $mp4 = $mp4[1];
    $thumbnail = 'http://v.fxtimg.com/'.$video.'/preview.jpg';
  }elseif($tubeserver == 'pornmaki'){
    $url = 'http://pornmaki.com/embed/'.$video;
    $str = file_get_contents($url);
    preg_match("/label:\"800\", file:\"(.*)\"/", $str, $mp4);
    preg_match("/var image = \"(.*)\";/", $str, $thumbnail);
    $mp4 = $mp4[1];
    $thumbnail = $thumbnail[1];  
	}elseif($tubeserver == 'befuck'){
	$source = curl("http://www.befuck.com/player/embed.xml?video_id=".$video, 'http://www.befuck.com');
		preg_match('/video_url="(.*)" preview_url/', $source, $mp4);
		preg_match('/preview_url="(.*)" embed/', $source, $foto);
		$mp4 = $mp4[1];
		$thumbnail = $foto[1];
  }elseif($tubeserver == 'pornoid'){
	$source = curl("http://www.pornoid.com/player/embed.xml?video_id={$video}", 'http://www.pornoid.com');
		preg_match('/video_url="(.*)" preview_url/', $source, $mp4);
		preg_match('/preview_url="(.*)" embed/', $source, $foto);
		$mp4 = $mp4[1];
		$thumbnail = $foto[1];
  }elseif($tubeserver == 'youjizz'){
	$source = curl("http://www.youjizz.com/videos/a-".$video.".html", 'http://www.youjizz.com', 'movil');
		preg_match('/<a class="preview_thumb" href="(.*)">/', $source, $mp4);
		preg_match('/<img height="226" width="300" src="(.*)" alt/', $source, $foto);
		$mp4 = $mp4[1];
		$thumbnail = $foto[1];
	}elseif($tubeserver == 'youporn'){
	$url = 'http://www.youporn.com/watch/'.$video.'/';
	$userAgent  = array('http' => array('user_agent' => 'Mozilla/5.0 (Linux; U; Android 4.0; en-us; GT-I9300 Build/IMM76D)'));
    $str = file_get_contents($url, false, stream_context_create($userAgent) );
	if(!$str){return false;}
$mp4=getstring($str,'<video id="player-html5" class=\'videoPlayer\' src="','"');
	$thumbnail=getstring($str,'poster="','"');
  }elseif($tubeserver == 'redtube'){
    $userAgent  = array('http' => array('user_agent' => 'Mozilla/5.0 (Linux; U; Android 4.0; en-us; GT-I9300 Build/IMM76D)'));
    $str = file_get_contents('http://www.redtube.com/'. $video .'/', false, stream_context_create($userAgent) );
  if(!$str){return false;}
	preg_match('/"videoUrl":"(.*)"/', $str, $mp4);
	$mp4=getstring($mp4[1] ,'"videoUrl":"','"');
  //$mp4=getstring($str ,'"videoUrl":"','"');
  $thumbnail=getstring($str ,'<meta property="og:image" content="','"');
  $mp4=str_replace("\\", "", $mp4);

	}elseif($tubeserver == 'tube8'){
    $source = curl("http://www.tube8.com/a/a/".$video."/", 'http://www.tube8.com', 'movil');
		preg_match('/page_params.video_urls.sd = "(.*)";/', $source, $video);
		preg_match('/<img id="videoImage" src="(.*)" \/>/', $source, $foto);
		$mp4 = $video[1];
		$thumbnail = $foto[1];

	}elseif($tubeserver == 'pornhub'){
		$videos = array();
		$source = curl("http://www.pornhub.com/view_video.php?viewkey=$video", 'http://pornhub.com', 'movil');
		//preg_match('/<img  class="mainImage removeWhenPlaying"  src="(.*)" alt="">/', $source, $foto);
		//$thumbnail=$foto[1];
		//$source = curl("https://www.pornhub.com/embed/$video", 'http://pornhub.com', 'movil');
		$thumbnail=getstring($source,'"image_url":"','"');
		$videos=get_match_all($source, '"videoUrl":"https:', '"}');
		$mp4='https:'.$videos[0][1];

  }elseif($tubeserver == 'xhamster'){
    $url = 'http://es.xhamster.com/movies/'.$video.'/.html?prs=--';
    $source = curl("http://xhamster.com/xembed.php?video=$video", 'http://xhamster.com');
	preg_match('/<a target="_blank" class="noFlash" href="(.*)">/', $source, $link);
	$link = substr($link[1], 0, strpos($link[1], '?'));
	$source = curl($link, 'http://xhamster.com');
	preg_match('/<a href="(.*)" class="mp4Thumb" target="_blank">/', $source, $videolink);
	$thumb=getstring($source,'class="mp4Thumb" target="_blank"',"<div class='iconPlay'>");
	
    $mp4 = $videolink[1];
	$thumbnail=getstring($thumb,'"','"');
  }
  return array ($mp4, $thumbnail);
}
$resultados = obtenerVideo($tubeserver, $video);
if(!$resultados){?>
<meta name="robots" content="noindex">
<base target="_parent" />
<style>
body{
  margin: 0;
  padding: 0;
  font-family: arial;
  text-align: center;
}
.kt_imgrc{
	text-align: center;
}
.thumbs a {
    display: inline-block;
    font-size: 13px;
    margin: 0 0 10px 20px;
    vertical-align: middle;
    width: 30%;
}
a {
  color: #81afcd;
  text-decoration: none;
  target: parent;
}
.thumbs-aside a {
  margin: 0 0 10px 0;
}
.thumbs a img {
  width: 60%;
  height: auto;
}
.kt_imgrc:hover a .title {
  background: #006d04;
}
.title {
	width: 60%;
    min-height: 15px;
    text-overflow: ellipsis;
    transition: background 0.3s ease 0s, color 0.3s ease 0s;
    white-space: nowrap;
}
.title, .tools {
	width: 60%;
    background: #ebebeb none repeat scroll 0 0;
    color: #152530;
    display: block;
    overflow: hidden;
	margin: 0 auto;
    padding: 2px 2px 1px 4px;
}


</style>
<h2>This video had been deleted!</h2>
<h3>Don't worry, we have tons others here:</h3>
	<section class="thumbs thumbs-aside">
<?php
query_posts('showposts=6&orderby=rand');
if (have_posts()) : ?>
					
            <?php $i=0; while (have_posts()) : the_post(); if($current_post_id==get_the_ID()) continue; $i++; ?>
 
<?php
$thumb = get_post_meta( get_the_ID(), 'thumb', true ); 
if(!empty($thumb)) { 
$thumb=$thumb;
} elseif (kenplayer_get_thumbnail()!="") {$thumb=kenplayer_get_thumbnail();} else {$thumb="http://i.imgur.com/fjJMVKZ.jpg";}
?>
<a href="<?php the_permalink() ?>" class="kt_imgrc" title="<?php the_title_attribute(); ?>">
<span class="thumb-img">
	<img src="<?php echo $thumb; ?>" height="180" width="240" class="thumb"/> 

</span>
<span class="title"><?php echo $post->post_title; ?></span>

</a>

<?php endwhile; endif; wp_reset_query(); ?>


	</section>
<?php	
} else {
$mp4 = $resultados[0];
$thumbnail = $resultados[1];
$protocol = isset($_SERVER["HTTPS"]) ? 'https' : 'http';
if($protocol=='https'){
	$mp4=str_replace("http://","https://",$mp4);
	$thumbnail=str_replace("http://","https://",$thumbnail);
}


?>
<meta name="robots" content="noindex">
  <link href="https://unpkg.com/video.js/dist/video-js.css" rel="stylesheet">
  <script src="https://unpkg.com/video.js/dist/video.js"></script>
  <script src="https://unpkg.com/videojs-contrib-hls/dist/videojs-contrib-hls.js"></script>
<!--plugins-->
<script data-cfasync="false" src='../video-js/plugins/videojs.logobrand.js'></script>
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
*{margin:0;padding:0;box-sizing:border-box}body,html{height:100%}html{font-size:60%}body{line-height:1.5;font-family:sans-serif;background:#000}.vjs-resolution-button .vjs-menu-icon:before{content:'\f110';font-family:VideoJS;font-weight:400;font-style:normal;font-size:1.5em;line-height:2em}#dvr-vid:before,.tt-sh nav:before{content:''}.vjs-resolution-button .vjs-resolution-button-label{font-size:1em;line-height:3em;position:absolute;top:0;left:0;width:100%;height:100%;text-align:center;box-sizing:inherit}.vjs-resolution-button .vjs-menu .vjs-menu-content{width:3em;left:50%;margin-left:0}.tt-rl,.video-js{width:100%;height:100%}.vjs-menu{margin-left:-28px}.vjs-resolution-button .vjs-menu li{text-transform:none;font-size:1em}.vjs-thumbnail-holder{position:absolute;left:-1000px}.vjs-thumbnail{position:absolute;left:0;bottom:1.3em;opacity:0;transition:opacity .2s ease;-webkit-transition:opacity .2s ease;-moz-transition:opacity .2s ease;-mz-transition:opacity .2s ease}.pst,.tt-cn{position:relative}.vjs-progress-control.fake-active .vjs-thumbnail,.vjs-progress-control:active .vjs-thumbnail,.vjs-progress-control:hover .vjs-thumbnail{opacity:1}.vjs-progress-control:active .vjs-thumbnail:active,.vjs-progress-control:hover .vjs-thumbnail:hover{opacity:0}.video-js .vjs-progress-control:hover .vjs-mouse-display,.video-js .vjs-progress-holder .vjs-play-progress .vjs-time-tooltip,.vjs-play-progress:after{display:none}.vjs-big-play-button{left:0!important;top:0!important;bottom:0!important;right:0!important;border-radius:5px!important;margin:auto!important}.tt-cn,.tt-rl{display:-webkit-box;display:-moz-box;display:-ms-flexbox;display:-webkit-flex;display:flex}.tt-cn{z-index:11;background-color:rgba(0,0,0,.85);height:100%}.tt-rl{-webkit-flex-wrap:wrap;-ms-flex-wrap:wrap;flex-wrap:wrap;-webkit-box-pack:center;-moz-box-pack:center;-webkit-justify-content:center;-ms-flex-pack:center;justify-content:center;-webkit-align-content:center;-ms-flex-line-pack:center;align-content:center;-webkit-box-align:center;-moz-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;z-index:3}.tt-rl>*{-webkit-box-flex:0;-webkit-flex:0 auto;-ms-flex:0 auto;flex:0 auto}.pst{width:25%;padding:1px}#tt-nv,.pst a>span,.pst img{width:100%;height:100%;left:0;top:0}.pst>a{display:block;padding-top:75%}.pst a>span,.pst img{position:absolute}.pst img{z-index:1;padding:1px;background-color:#000}.pst a>span{z-index:2;background-color:rgba(0,0,0,.85);padding:.5rem;font-size:1.2rem;color:#fff;transition:all .2s;opacity:0;overflow:hidden;line-height:22px}.bt-rp,.bt-sh,.ttl{line-height:2rem;color:#fff}.pst a:hover>span{opacity:1}.pst a>span span{display:block;font-size:1rem;opacity:.5}.bt-rp,.bt-sh,.tt-sh{display:inline-block}.pst a>span strong{text-align:right;position:absolute;right:1rem;bottom:1rem;opacity:.3;font-size:.8rem}.bt-sh,.tt-sh{position:relative}.tt-bt,.ttl{-webkit-flex:0 0 100%;-ms-flex:0 0 100%;flex:0 0 100%;text-align:center;padding-top:1rem}.tt-bt{-webkit-align-self:flex-end;-ms-flex-item-align:end;align-self:flex-end}.bt-rp,.bt-sh{background-color:rgba(0,0,0,.5);border:0;padding:0 1rem;font-size:.8rem;border-radius:5px;text-transform:uppercase;font-weight:700;cursor:pointer}.bt-rp:hover,.bt-sh:hover{background-color:#000}.bt-sh{z-index:10}#tt-nv{position:fixed;z-index:9;-webkit-appearance:none;-moz-appearance:none;background-color:rgba(255,255,255,.6);display:none}#tt-nv:checked,#tt-nv:checked~nav{display:block}.tt-sh nav{position:absolute;z-index:10;bottom:2.5rem;width:160px;left:50%;margin-left:-80px;background-color:rgba(0,0,0,.7);font-size:0;padding:1rem 0;border-radius:5px;display:none}.tt-sh nav:before{left:0;right:0;bottom:-5px;margin:auto;width:0;position:absolute;height:0;border-left:5px solid transparent;border-right:5px solid transparent;border-top:5px solid rgba(0,0,0,.7)}.fc,.tw,.ws{background-image:url(assets/img/tics.png);width:40px;height:40px;border-radius:20px;display:inline-block;margin:0 3px;transition:all .2s;position:relative}#dvr-vid,.logo{position:absolute}.fc:hover,.tw:hover,.ws:hover{top:-2px;opacity:.8}.fc{background-color:#5e81d3}.tw{background-position:-40px 0;background-color:#42c0f3}.ws{background-position:-80px 0;background-color:#1ad722}.ttl{font-size:1.2rem;font-weight:400;padding-top:0;padding-bottom:1rem}@media (max-width:600px){.tt-cn{padding:0 20px}.pst{display:none}.pst:first-of-type,.pst:first-of-type+.pst,.pst:first-of-type+.pst+.pst,.pst:first-of-type+.pst+.pst+.pst{width:50%;display:block}.pst>a{padding-top:50%}}@media (min-width:700px){html{font-size:70%}}@media (min-width:1000px){html{font-size:100%}}.logo{left:15px;top:15px;z-index:10}.video-js .vjs-load-progress div,.video-js .vjs-slider{background-color:rgba(0,0,0,.3)!important}.kuzminplayer-play{display:none}#dvr-vid{width:320px;height:270px;padding:10px;background-color:#fff;border-radius:5px;left:0;top:0;right:0;bottom:0;margin:auto;z-index:9999;box-shadow:0 0 0 100vh rgba(0,0,0,.7)}#dvr-vid:before{position:fixed;z-index:1;left:0;top:0;width:100%;height:100%}#dvr-vid>div{position:relative;z-index:2}#dvr-vid>a{position:absolute;right:-10px;top:-10px;width:30px;height:30px;line-height:22px;text-align:center;border-radius:50%;font-size:15px;font-weight:700;color:#fff;border:3px solid #fff;background-color:#ef3e3e;text-decoration:none;box-shadow:0 0 20px rgba(0,0,0,.2);z-index:3}#dvr-vid>a:hover{top:-11px}
</style>

<div id="parent">

<?php $ads = get_option( 'kenplayer_ads' ); if($ads != ''){ 
$seconds = get_option( 'kenplayer_seconds' ); if(!$seconds){ $seconds = 10; }
?>
<div id="child">
  <a href='#' onclick='this.parentNode.parentNode.removeChild(this.parentNode)'><img src="fancy_close.png"/></a>
  <?php echo $ads; ?>
</div>
<?php } ?>

<?php
$uploads = wp_upload_dir();
?>
<video id="video" class="video-js vjs-default-skin vjs-big-play-centered"
  controls preload="auto" width="100%" height="100%"
  poster="<?php echo $thumbnail; ?>"
  data-setup='{"example_option":true}'>

 <?php
if (endsWith($mp4, '.flv')){
?>
 <source src="<?php echo $mp4; ?>" type='video/x-flv' />
<?php
} elseif (strpos($mp4, '.m3u8')){
?>
 <source src="<?php echo $mp4; ?>" type="application/x-mpegURL" />
<?php
} else {
?>
 <source src="<?php echo $mp4; ?>" type='video/mp4' />
<?php
}
?>
</video>

</div><!--/.parent-->

<script data-cfasync="false" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

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
<?php
}
?>
<!--<?php if (in_array('curl', get_loaded_extensions())) {
  echo "cURL installed. ";
} else {
  echo "cURL NOT installed. ";
}
echo ini_get('allow_url_fopen') ? "file_get_content Enabled" : "file_get_content Disabled";
?>-->
<!--Kenplayer v2.0-->