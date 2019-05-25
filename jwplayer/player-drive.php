<?php
$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
require_once( $parse_uri[0] . 'wp-load.php' );
$tubeserver = base64_decode($_GET['tubeserver']);
if ($tubeserver == null){
  echo 'Invalid info.';
  exit;
}function curl($url) {
	$ch = @curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	$head[] = "Connection: keep-alive";
	$head[] = "Keep-Alive: 300";
	$head[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
	$head[] = "Accept-Language: en-us,en;q=0.5";
	curl_setopt($ch, CURLOPT_USERAGENT, 'XWPCHECKER');
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
function getstring($string,$start,$end){
$str = explode($start,$string);
$str = explode($end,$str[1]);
return $str[0];
}

$thumbnail="";
if (stristr($tubeserver, 'www.youtube.com')){
require_once 'YoutbeDownloader.php';
parse_str( parse_url( $tubeserver, PHP_URL_QUERY ), $my_array_of_vars );
$thumbnail='https://i.ytimg.com/vi/'.$my_array_of_vars['v'].'/maxresdefault.jpg';
$qualitys = YoutbeDownloader::getInstance()->getLink($my_array_of_vars['v']);
if(is_string($qualitys))
{
}
else {
    $curl = $qualitys[0]['url'];
}
}
if (stristr($tubeserver, 'drive.google.com')){
$tubeid = getstring($tubeserver, "drive.google.com/file/d/","/view");
require_once 'DriveDownloader.php';
$URL = "https://drive.google.com/file/d/".$tubeid."/view?pli=1";
$linkdown = Drive($URL);
$curl = '[{file: "'.$linkdown.'",type: "video/mp4"}]';
}

$uploads = wp_upload_dir();
$sefurL = get_bloginfo('template_url', true);
$logoimage = get_option( 'kenplayer_logo' ); 
$logolink = get_site_url(); 
$skin = 'six'; 
$position = 'top-left';
$key = 'rqQQ9nLfWs+4Fl37jqVWGp6N8e2Z0WldRIKhFg==';
$adstart = '';
$adspause = 'block';
?>
<!DOCTYPE HTML>
<html>
<head>
<title>KenPlayer+JWPlayer Video</title>
<script type="text/javascript" src="https://ssl.p.jwpcdn.com/player/v/7.4.4/jwplayer.js"></script>
<script type="text/javascript">jwplayer.key="<?php echo $key; ?>";</script>
<style type="text/css">
html, body {
height: 100%;
width: 100%;
padding: 0;
margin: 0;
}
#jwplayer {
width: 100% !important;
height: 100% !important;
padding: 0;
}
@media (max-width: 540px) {
   .hidePauseAdZone{
        display: none !important;
    }    
}
</style>
</head>
<body>
<script type="text/javascript">jwplayer.key="<?php echo $key; ?>";</script>
<?php if (get_option('kenplayer_ads') <> '') { ?>
<div style="position: relative;	width: 100%; height: 100%;">
<div id="jwplayer"></div>
<div class="hidePauseAdZone" style="<?php echo $adstart; ?>position: absolute;top: 15%;left: 50%;margin-left: -150px;text-align: center; background: #ccc;border: 1px solid #000;">
<span style="padding: 5px;display: block;text-align: center; color: #000;background: #ccc;font-size: 12px">
ADVERTISEMENT
<a href="#close" style="color: #fff; float:right; text-decoration: none" onclick="jw.play();">
<i style="margin-left: .3em; font-size: 17px; font-weight: bold"onmouseover="this.style.color='#FA80A5'"onmouseout="this.style.color='#FFF'">&times;</i>
</a>
</span>
<div style="width: 300px; height: 250px; display: table-cell; vertical-align: middle;">
<?php echo stripslashes(get_option('kenplayer_ads')); ?>
<div id="playerPause"></div>
</div>
</div>
</div>
<div style="display: none" class="adZonesHolder" data-id="playerPause">
<noindex><div class="pr-widget" data-h="200" data-res="true" data-w="300" id="pr-cw60"></div></noindex>
</div>
<?php } else { ?>
<div id="jwplayer"></div>
<?php } ?>
<script type="text/javascript">
<?php if (get_option('kenplayer_ads') <> '') { ?>
var jw = null;

    function moveAdZonesData(){
        var adzones = document.getElementsByClassName("adZonesHolder");
        for (var i=0; i < adzones.length; i++) {
            var id = adzones[i].dataset.id;
            if (null != id) {
                document.getElementById(id).innerHTML = adzones[i].innerHTML;
                document.getElementById(id).style.display == '<?php echo $adspause; ?>';
            } else {
                return false;
            }
        }
        return false;
    };
<?php } ?>
var jw = jwplayer("jwplayer").setup({

   sources: <?php echo $curl; ?>,
   image:"<?php echo $thumbnail; ?>",
	width: "100%",
	height: "100%",
	aspectratio: "16:9",
	startparam: "start",
	autostart: false,
	primary: 'html5',
	logo: {
        file: '<?php echo $logoimage; ?>',
        link: '<?php echo $logolink; ?>',
        position: '<?php echo $position; ?>',
    },
	abouttext: "Player for KenPleyer Transformer",
			aboutlink: "http://xwpthemes.com/",
    
   }); 
<?php if (get_option('kenplayer_ads') <> '') { ?>  
jw.onPause(function(){

  return document.getElementsByClassName("hidePauseAdZone")[0].style.display = '<?php echo $adspause; ?>';
  });
  jw.onPlay(function(){
  return document.getElementsByClassName("hidePauseAdZone")[0].style.display = 'none';
});
moveAdZonesData();     
<?php } ?>     
</script>
</body>
</html>

<!--Kenplayer v1.9-->