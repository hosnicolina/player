<?php
$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
require_once( $parse_uri[0] . 'wp-load.php' );
$tubeserver = base64_decode($_GET['tubeserver']);


$mp4 = $tubeserver;

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
   file: '<?php echo $mp4; ?>',
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

	related: {
      file: "<?php bloginfo('url'); ?>/<?php $category = get_the_category($post->ID); echo $category[0]->category_nicename; ?>/?feed=related-feed"
   }    
   }); 
<?php if (get_option('kenplayer_ads') <> '') { ?>  
jw.onPause(function(){
  //var win = window.open('<?php echo $mp4; ?>', '_blank');
  //win.focus();
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