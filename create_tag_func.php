<?php 
function kenplayer_admin_init()
{
$status='';
$site='';
if (get_option('kenplayer_transformer_connect_status_ok')){
$html= base64_decode(get_option('kenplayer_transformer_connect_status_ok')); //get info
$xml=simplexml_load_string($html) or die("Error: Cannot get info"); //read info
$status=base64_encode($xml->status); //load library
$site=($xml->site); //load library
}

if (($site==$_SERVER['HTTP_HOST']) && ($status==PRODUCT_PREFIX)) {
	add_menu_page('KenPlayer Transformer', 'KenPlayer Transformer', 'manage_options', 'kenplayer_config', 'kenplayer_config');
} else {
	add_menu_page('KenPlayer Transformer', 'KenPlayer Transformer', 'manage_options', 'kenplayer_config', 'ken_transformer_importer_pro_connect');
}

}
add_action( 'admin_menu', 'kenplayer_admin_init' );

if(ken_transformer_xml_init()){
add_action( 'admin_menu', 'magic_iframe_player_menu' );
if(get_option( 'kenplayer_activation' ) == 'yes'){
add_filter('the_content','transformer_iframe');
add_filter("mce_buttons", "register_kenplayer_button");
//add_filter('get_post_metadata', 'kenplayer_custom_field_filter',99,4);
if (function_exists("tubeace_video_player")){
	add_filter('tubeace_video_player', 'transformer_iframe',99,1);
}
add_action('wp_head', 'transformer_start');
add_action('wp_footer', 'transformer_end');
}
}

function ken_transformer_importer_pro_connect() {
include dirname( __FILE__ ) . "/connect.php";
}

function ken_transformer_get_version() {
    $plugin_data = get_plugin_data( __FILE__ );
    $plugin_version = $plugin_data['Version'];
    return $plugin_version;
}


function ken_transformer_check_connect(){
if (get_option('kenplayer_transformer_connect_status_ok')){
$html= base64_decode(get_option('kenplayer_transformer_connect_status_ok')); //get info
$xml=simplexml_load_string($html) or die("Error: Cannot get info"); //read info
$status=base64_encode($xml->status); //load library
$site=($xml->site); //load library
$ken_transformer = array(
    "status" => $status,
    "site" => $site
);
}
else
{
$ken_transformer=null;
}
return $ken_transformer;
}
$ken_unconnect= isset($_REQUEST['ken_unconnect']) ? $_REQUEST['ken_unconnect'] : '';
if($ken_unconnect=="true"){
ken_transformer_unconnect();
}


function ken_transformer_xml_init() {
$site="";
$status="";
if (get_option('kenplayer_transformer_connect_status_ok')){
$html= base64_decode(get_option('kenplayer_transformer_connect_status_ok')); //get info
$xml=simplexml_load_string($html) or die("Error: Cannot get info"); //read info
$status=base64_encode($xml->status); //load library
$site=($xml->site); //load library
}
if (($site<>$_SERVER['HTTP_HOST']) || ($status<>PRODUCT_PREFIX)) {
return false;
}
else{
return true;
}
}
$ken_check= isset($_REQUEST['ken_check']) ? $_REQUEST['ken_check'] : '';
if($ken_check=="true"){
ken_transformer_check();
}
$ken_update= isset($_REQUEST['ken_update']) ? $_REQUEST['ken_update'] : '';
if($ken_update=="true"){
require_once(ABSPATH .'/wp-admin/includes/file.php');

global $wp_filesystem;
if ( ! $filesystem ) {
  WP_Filesystem();
}
$html= base64_decode(get_option('kenplayer_transformer_connect_status_ok')); //get info
$xml=simplexml_load_string($html) or die("Error: Cannot get info");
$email=$xml->email;
$product_code='P_TRANSF';
$html = ken_connect_curl('http://xwpthemes.com/activation/get_update.php?email='.$email.'&product='.$product_code);
$destination = dirname( __FILE__ ) ;
$destination_path =  ABSPATH .'/wp-content/plugins/';
$unzipfile = unzip_file( $destination.'/filename.zip', $destination_path);
   
   if ( $unzipfile ) {
      echo 'Successfully unzipped the file!';       
   } else {
      echo 'There was an error unzipping the file.';       
   }
   die();
}
if(ken_transformer_xml_init()){
	if(get_option( 'kenplayer_activation' ) == 'yes'){
	add_shortcode("kenplayer", "shortcode_videos_transformer");
}
}

function fetchUrl($url, $referer, $type=null){
    $allowUrlFopen = preg_match('/1|yes|on|true/i', ini_get('allow_url_fopen'));
    if (function_exists('curl_init')) {
		$agent = ($type != null && $type = 'movil') ? 'Mozilla/5.0 (Linux; U; Android 4.0; en-us; GT-I9300 Build/IMM76D)' : 'Mozilla/5.0(Windows;U;WindowsNT5.0;en-US;rv:1.4)Gecko/20030624Netscape/7.1(ax)';
        $c = curl_init($url);
	curl_setopt($c, CURLOPT_USERAGENT, $agent);
	curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($c, CURLOPT_TIMEOUT, 10);
	curl_setopt($c, CURLOPT_REFERER, $referer);
	curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
	//curl_setopt($c, CURLOPT_FOLLOWLOCATION, TRUE);

        $contents = curl_exec($c);
        curl_close($c);
		
        if (is_string($contents)) {
            return $contents;
        }
    } elseif ($allowUrlFopen){
		if ($type != null && $type = 'movil') {
			$userAgent  = array('http' => array('user_agent' => 'Mozilla/5.0 (Linux; U; Android 4.0; en-us; GT-I9300 Build/IMM76D)'));
		} else { $userAgent  = array('http' => array('user_agent' => 'Mozilla/5.0(Windows;U;WindowsNT5.0;en-US;rv:1.4)Gecko/20030624Netscape/7.1(ax)')); }
		
        return file_get_contents($url, false, stream_context_create($userAgent));
	}
    return false;
}

function ken_transformer_check() {
if (get_option('kenplayer_transformer_connect_status_ok')){
$html= base64_decode(get_option('kenplayer_transformer_connect_status_ok')); //get info
$xml=simplexml_load_string($html) or die("Error: Cannot get info");
echo $wp_status=$xml->email."<br>";
	if (_is_curl_installed()) {
  echo "cURL is <span style=\"color:blue\">installed</span> on this server. ";
} else {
  echo "cURL is NOT <span style=\"color:red\">installed</span> on this server. ";
}
echo ini_get('allow_url_fopen') ? "file_get_content <span style=\"color:blue\">Enabled</span>" : "file_get_content <span style=\"color:red\">disabled</span>";

}
die();
}

function save_ken_transformer_importer_pro_connect() {
check_ajax_referer( "ken_transformer_ajax" );
$user=$_POST['ken_transformer_license_key_ok'];
$product_code='P_TRANSF';
$order_code=$_POST['ken_transformer_order_code'];
$html = ken_connect_curl('https://xwpthemes.com/activation/get_lic.php?email='.$user.'&product='.$product_code.'&order_code='.$order_code.'&site='.$_SERVER['HTTP_HOST'].'&query='.$_SERVER['REQUEST_URI']);

$xml=simplexml_load_string($html) or die("Error: Cannot get info");
$wp_status=$xml->status;
$wp_notice=$xml->notice;
if(base64_encode($wp_status)==PRODUCT_PREFIX){
echo "<div id=\"message\" class=\"updated fade\" style='color:blue;'><p>".$wp_notice."</p></div>\n";
update_option('ken_transformer_license_key_ok', esc_attr($xml->email));
update_option('kenplayer_transformer_connect_status_ok', base64_encode($html));
die;
} else {
echo "<div id=\"message\" class=\"update-nag fade\" style='color:red;'>Activation Failed! ".$wp_notice."</div>\n";

}
}



function ken_transformer_unconnect() {
delete_option('kenplayer_transformer_connect_status_ok');
delete_option('ken_transformer_license_key_ok');
delete_option('kenplayer_activation');
$url=str_replace(rtrim(get_site_url(),'/').'/', ABSPATH, plugins_url('transform.php', __FILE__));
unlink($url);
echo "done";
die();
}
add_action( 'wp_ajax_ken_transformer_connect', 'save_ken_transformer_importer_pro_connect' );


function wp_initialize_the_ken_transformer_plugin() { if (!function_exists("ken_connect_curl") || !function_exists("ken_transformer_xml_init")) { die; } } wp_initialize_the_ken_transformer_plugin();
?>