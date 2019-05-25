<?php
/*
Plugin Name: KenPlayer Transformer
Plugin URI: http://xwpthemes.com
Description: KenPlayer Transformer
Version: 2.0.2
Author: Xwpthemes
Author URI: http://xwpthemes.com
*/
?>
<?php
if (!defined('PRODUCT_PREFIX')) define('PRODUCT_PREFIX', 'YWN0aXZhdGVk');

function magic_iframe_player_menu(){
    add_action( 'admin_init', 'update_ken_transformer' );
}
function kenplayer_set_my_default_settings()
{
$args = array(
'kenplayer_logo' => 'http://i.imgur.com/8ZQrXIK.png', 
'kenplayer_activation' => 'yes', 
'kenplayer_jwplayer' => 'yes', 
'kenplayer_responsive' => 'no', 
'kenplayer_xvideos' => 'on', 
'kenplayer_redtube' => 'on', 
'kenplayer_youporn' => 'on', 
'kenplayer_pornhub' => 'on', 
);
// Loop through args
foreach ( $args as $k => $v )
{
update_option($k, $v);
}
return;
}
register_activation_hook(__FILE__, 'kenplayer_set_my_default_settings');
if( !function_exists("ken_connect_curl") ) {
function ken_connect_curl($url){
    $curl	= curl_init();
    curl_setopt ($curl, CURLOPT_URL, $url);
    curl_setopt ($curl, CURLOPT_USERAGENT, "XWPCHECKER");
	curl_setopt	($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt ($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt ($curl, CURLOPT_ENCODING, "");
    $data	= curl_exec ($curl);
    curl_close ($curl);
    return $data;
}
}


if( !function_exists("ken_return_value") ) {
function ken_return_value() {
if ($_REQUEST['go']=='update'){return true;}
else
return false;
}
}
if( !function_exists("update_ken_transformer") ) {
function update_ken_transformer() {
  register_setting( 'kenplayer_config', 'kenplayer_logo' );
  register_setting( 'kenplayer_config', 'kenplayer_logo_url' );
  register_setting( 'kenplayer_config', 'kenplayer_ads' );
  register_setting( 'kenplayer_config', 'kenplayer_poster' );
  register_setting( 'kenplayer_config', 'kenplayer_seconds' );
  register_setting( 'kenplayer_config', 'kenplayer_cache' );
  register_setting( 'kenplayer_config', 'kenplayer_activation' );
  register_setting( 'kenplayer_config', 'kenplayer_customfield' );
  register_setting( 'kenplayer_config', 'kenplayer_jwplayer' );
  register_setting( 'kenplayer_config', 'kenplayer_xvideos' );
  register_setting( 'kenplayer_config', 'kenplayer_redtube' );
  register_setting( 'kenplayer_config', 'kenplayer_youporn' );
  register_setting( 'kenplayer_config', 'kenplayer_pornhub' );
  register_setting( 'kenplayer_config', 'kenplayer_youtube' );
  register_setting( 'kenplayer_config', 'kenplayer_xhamster' );
  register_setting( 'kenplayer_config', 'kenplayer_responsive' );
}
}
function script_js(){
	echo "<script type=\"text/javascript\" src=\"".plugins_url('js/fluidvids.js', __FILE__)."\"></script>";
	echo "<script>
    fluidvids.init({
      selector: ['iframe'],
      players: ['".$_SERVER['HTTP_HOST']."']
    });
    </script>";
}
function shortcode_button($plugin_array)
{
    //enqueue TinyMCE plugin script with its ID.
    $plugin_array["kenplayer_button_plugin"] =  plugin_dir_url(__FILE__) . "js/tinymce.js";
    return $plugin_array;
}

add_filter("mce_external_plugins", "shortcode_button");
function register_kenplayer_button($buttons)
{
    //register buttons with their id.
    array_push($buttons, "kenplayer");
    return $buttons;
}

if(get_option( 'kenplayer_responsive' ) == 'yes'){
add_action('wp_footer', 'script_js', 100);
}
if( !function_exists("kenplayer_config") ){
function kenplayer_config(){
?>
<h2>KenPlayer Transformer</h2>

  <form method="post" action="options.php">
    <?php settings_fields( 'kenplayer_config' ); ?>
    <?php do_settings_sections( 'kenplayer_config' ); 
	if (in_array('curl', get_loaded_extensions())) {
  echo "cURL is <span style=\"color:blue\">installed</span> on this server. ";
} else {
  echo "cURL is NOT <span style=\"color:red\">installed</span> on this server. ";
}
echo ini_get('allow_url_fopen') ? "file_get_content <span style=\"color:blue\">Enabled</span>" : "file_get_content <span style=\"color:red\">disabled</span>";

	?>
    <table class="form-table">
	<tr>
      <th scope="row">Active the transformation?</th>
      <td><select name="kenplayer_activation"><option value="no"<?php if(get_option( 'kenplayer_activation' ) == 'no'){ echo ' selected'; } ?>>Deactive</option><option value="yes"<?php if(get_option( 'kenplayer_activation' ) == 'yes'){ echo ' selected'; } ?>>Active</option></select><br><small>Active the feature transform all default embed code to videojs player?.</td>
      </tr>
	  <tr>
      <th scope="row">Custom player</th>
      <td><select name="kenplayer_jwplayer"><option value="no"<?php if(get_option( 'kenplayer_jwplayer' ) == 'no'){ echo ' selected'; } ?>>VideoJS</option><option value="yes"<?php if(get_option( 'kenplayer_jwplayer' ) == 'yes'){ echo ' selected'; } ?>>JWPlayer</option></select></td>
      </tr>
	  <tr>
      <th scope="row">Affect with:</th>
      <td>
	  <input type="checkbox" name="kenplayer_xvideos" id="kenplayer_xvideos" <?php if(get_option( 'kenplayer_xvideos' )=='on') echo 'checked="checked"';?>>Xvideos<br>
	  <input type="checkbox" name="kenplayer_youporn" id="kenplayer_youporn" <?php if(get_option( 'kenplayer_youporn' )=='on') echo 'checked="checked"';?>>Youporn<br>
	 
	  <input type="checkbox" name="kenplayer_redtube" id="kenplayer_redtube" <?php if(get_option( 'kenplayer_redtube' )=='on') echo 'checked="checked"';?>>Redtube<br>
	  <input type="checkbox" name="kenplayer_pornhub" id="kenplayer_pornhub" <?php if(get_option( 'kenplayer_pornhub' )=='on') echo 'checked="checked"';?>>Pornhub(test)<br>
	  </td>
      </tr>
      <tr>
      <th scope="row">URL of LOGO:</th>
      <td><input type="text" name="kenplayer_logo" value="<?php echo get_option( 'kenplayer_logo' ); ?>"/><br><small>Include http://</small></td>
      </tr>
	  <th scope="row">LOGO link to URL:</th>
      <td><input type="text" name="kenplayer_logo_url" value="<?php echo get_option( 'kenplayer_logo_url' ); ?>"/><br><small>Include http:// (leave blank to link to homepage)</small></td>
      </tr>
	  <tr>
      <th scope="row">Default poster:</th>
      <td><input type="text" name="kenplayer_poster" value="<?php echo get_option( 'kenplayer_poster' ); ?>"/><br><small>Include http://</small></td>
      </tr>
      <tr>
      <th scope="row">Insert advertising HTML code:</th>
      <td><textarea style="width: 250px; height: 150px;" name="kenplayer_ads"><?php echo get_option( 'kenplayer_ads' ); ?></textarea><br><small>Leave it blank to deactivate.</small></td>
      </tr>
      <tr>
      <th scope="row">Seconds display advertising:</th>
      <td><input type="text" name="kenplayer_seconds" value="<?php echo get_option( 'kenplayer_seconds' ); ?>" placeholder="10"/><br><small>If left blank by default is 10 seconds.</small></td>
      </tr>
	<tr>
      <th scope="row">Custom field of embed code:</th>
      <td><input type="text" name="kenplayer_customfield" value="<?php echo get_option( 'kenplayer_customfield' ); ?>" placeholder="embed_code"/><br><small>Put the custom field name of the embed code if you use embed code in custom field.</small></td>
      </tr>  
	<tr>
      <th scope="row">Active the Responsive with Fluidvids?</th>
      <td><select name="kenplayer_responsive"><option value="no"<?php if(get_option( 'kenplayer_responsive' ) == 'no'){ echo ' selected'; } ?>>Default theme option</option><option value="yes"<?php if(get_option( 'kenplayer_responsive' ) == 'yes'){ echo ' selected'; } ?>>Responsive with Fluidvids</option></select><br><small>Apply responsive with Fluidvids to videojs player?.</td>
      </tr>
      <tr>
      </tr>
    </table>
    <?php submit_button(); ?>
  </form>
<?php
}
}
function transformer_iframe($content) {
	$content = str_replace("redtube.com?id=", "redtube.com/?id=", $content);
	$content = str_replace("www.xvideos.com/embedframe", "flashservice.xvideos.com/embedframe", $content);
	$tubeservices = array(
		'\/\/flashservice.(.*).com\/embedframe\/([0-9]+)', /*xvideos.com*/
		'\/\/www.(.*).com\/embed\/([0-9]+)\/', /*youporn.com*/
		'\/\/www.(.*).com\/embed\/([A-z0-9]+)', /*pornhub.com*/
		'\/\/embed.(.*).com\/\?id=([0-9]+)', /*redtube.com*/
	);
	preg_match('/'. implode('|', $tubeservices) .'/', $content, $result);
	$result = array_values(array_filter($result));
	if(!empty($result)){
	$tubeserver = str_replace( array('www.', 'embed.', 'flashservice.'), '', $result[1]);
	$video 	= $result[2];

	$original="";
	if(($tubeserver == 'xvideos')&&(get_option( 'kenplayer_xvideos' )=='on')){
		$original = '//flashservice.xvideos.com/embedframe/' . $video;
	}elseif(($tubeserver == 'youporn')&&(get_option( 'kenplayer_youporn' )=='on')){
		$original = '//www.youporn.com/embed/'.$video;
	}elseif(($tubeserver == 'pornhub')&&(get_option( 'kenplayer_pornhub' )=='on')){
		$original = '//www.pornhub.com/embed/'.$video;
	}elseif(($tubeserver == 'youtube')&&(get_option( 'kenplayer_youtube' )=='on')){
		$original = '//www.youtube.com/embed/'.$video;
	}elseif(($tubeserver == 'redtube')&&(get_option( 'kenplayer_redtube' )=='on')){
		
		$original = '//embed.redtube.com/?id='. $video;
	}
	
	if (get_option( 'kenplayer_jwplayer' )=='yes'){
		$newplayer = plugins_url('/jwplayer/player.php?tubeserver='.$tubeserver.'&id='.$video.'&etc=', __FILE__);
			if(($tubeserver == 'youtube')&&(get_option( 'kenplayer_youtube' )=='on')){
				$newplayer = plugins_url('/jwplayer/player-drive.php?tubeserver='.base64_encode('https://www.youtube.com/watch?v='.$video), __FILE__);
			}
		}
	else{
		$newplayer = plugins_url('/player/player.php?tubeserver='.$tubeserver.'&id='.$video.'&etc=', __FILE__);
			if(($tubeserver == 'youtube')&&(get_option( 'kenplayer_youtube' )=='on')){
				$newplayer = plugins_url('/player/player-drive.php?tubeserver='.base64_encode('https://www.youtube.com/watch?v='.$video), __FILE__);
			}
		}

	if($original != ''){

		$content = str_replace($original, $newplayer, $content);
		}
	$content = str_replace( array('http:', 'https:'), '', $content);
	$content = str_replace('iframe src', 'iframe allowfullscreen="true" src', $content);
	}
	return $content;
}
function transformer_start() { ob_start("transformer_iframe"); }
function transformer_end() { ob_end_flush(); }

function shortcode_videos_transformer($atts, $link=null) {
	$datas = array(
		'drive.google.com',
		'www.youtube.com',
	);
	$tubeserver='';
	$video='';
	if (stristr($link, 'xvideos.com')) {
		preg_match('/\/\/www.xvideos.com\/video([0-9]+)\//', $link, $idxvideos);
		$tubeserver='xvideos';
		$video=$idxvideos[1];
	} elseif (stristr($link, 'pornhub.com')) {
		$idpornhub = substr($link, strpos($link, '?viewkey=')+9);
		$tubeserver='pornhub';
		$video=$idpornhub;
	} elseif (stristr($link, 'redtube.com')) {
		$idredtube = substr($link, strpos($link, 'redtube.com/')+12);
		$tubeserver='redtube';
		$video=$idredtube;
	} elseif (stristr($link, 'youporn.com') || stristr($link, 'youporngay.com')) {
		preg_match('/\/watch\/([0-9]+)\//', $link, $idyouporn);
		
		$tubeserver='youporn';
		$video=$idyouporn[1];
	}  elseif (endsWith($link, '.mp4') || endsWith($link, '.flv')) {
		$urlPlayer = plugins_url("player/player-direct.php?tubeserver=".$link, __FILE__);
	}
	if (get_option( 'kenplayer_jwplayer' )=='yes'){
		
		if ($tubeserver!="" && $video!="") {$urlPlayer = plugins_url("jwplayer/player.php?tubeserver=".$tubeserver."&id=".$video, __FILE__);}
		if (endsWith($link, '.mp4') || endsWith($link, '.flv')) {
		$urlPlayer = plugins_url("jwplayer/player-direct.php?tubeserver=".base64_encode($link), __FILE__);
		
	}
	foreach ($datas as $data){
	if (stristr($link, $data)){
		  $urlPlayer = plugins_url("jwplayer/player-drive.php?tubeserver=".base64_encode($link), __FILE__);
	}
	} 
	}
	else{
		if ($tubeserver!="" && $video!="") {$urlPlayer = plugins_url("player/player.php?tubeserver=".$tubeserver."&id=".$video, __FILE__);}
		if (endsWith($link, '.mp4') || endsWith($link, '.flv')) {
		$urlPlayer = plugins_url("player/player-direct.php?tubeserver=".base64_encode($link), __FILE__);
	}
	foreach ($datas as $data){
	if (stristr($link, $data)){
		  $urlPlayer = plugins_url("player/player-drive.php?tubeserver=".base64_encode($link), __FILE__);
	}
	} 
	}
	return '<iframe src="'.$urlPlayer.'" frameborder="0" allowfullscreen mozallowfullscreen webkitallowfullscreen msallowfullscreen width="735" height="400"></iframe>';
}


function endsWith($haystack, $needle)
{
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }

    return (substr($haystack, -$length) === $needle);
}
include dirname( __FILE__ ) . "/create_tag_func.php";

function kenplayer_get_thumbnail( $size='thumbnail', $attributes='') {
	global $post;
	if (has_post_thumbnail( $post->ID ) ):
	$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
	$ketqua= $image[0];
	endif;
if(empty($ketqua)) {
    $ketqua = "";
  }
	 return $ketqua;
}
function kenplayer_get_first_image() {
  global $post, $posts;
  $first_img = '';
  ob_start();
  ob_end_clean();
  $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
  $first_img = $matches[1][0];
 
  if(empty($first_img)) {
    $first_img = "";
  }
  return $first_img;
}

function kenplayer_get_thumb_image() {
global $post;
$thumb="";
$thumb = get_post_meta( get_the_ID(), "thumb", true ); 
if(empty($thumb)) {
$thumb=kenplayer_get_thumbnail();
}
if(empty($thumb)){
$thumb=kenplayer_get_first_image();
} else {
$thumb="";
}
return $thumb;
}

function kenplayer_custom_field_filter($metadata, $object_id, $meta_key, $single) {
	global $post, $wpdb;
	
	if($meta_key==get_option( 'kenplayer_customfield' ) && isset($meta_key)){
		//use $wpdb to get the value
        $value = $wpdb->get_var( "SELECT meta_value FROM $wpdb->postmeta WHERE post_id = $object_id AND  meta_key = '".$meta_key."'" );
		$video=trim($value);
		if(endsWith($video, '.mp4') || endsWith($video, '.flv')){
			if (get_option( 'kenplayer_jwplayer' )=='yes'){
				$urlPlayer = plugins_url("jwplayer/player-direct.php?tubeserver=".urlencode($video), __FILE__);
				}
			else{
				$urlPlayer = plugins_url("player/player-direct.php?tubeserver=".urlencode($video), __FILE__);
				}
				$content='<iframe src="'.$urlPlayer.'" frameborder="0" allowfullscreen mozallowfullscreen webkitallowfullscreen msallowfullscreen width="735" height="400"></iframe>';
		} else {
			$content=transformer_iframe($video);
		}
		
		return $content;
	}
}

?>