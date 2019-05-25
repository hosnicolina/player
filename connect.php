<?php 
$nonce = wp_create_nonce( 'ken_transformer_ajax' );
?>
<script  type='text/javascript'>

// When the document loads do everything inside here ...
jQuery(document).ready(function(){

	jQuery('#active').click(function() { //start function when Random button is clicked
		jQuery.ajax({
			type: "post",url: "admin-ajax.php",data: { action: 'ken_transformer_connect', ken_transformer_license_key_ok: jQuery( '#ken_transformer_license_key_ok' ).val(), ken_transformer_order_code:  jQuery( '#ken_transformer_order_code' ).val(), _ajax_nonce: '<?php echo $nonce; ?>' },
			beforeSend: function() {jQuery("#loading").fadeIn('fast');jQuery("#formstatus").fadeOut("fast");}, //fadeIn loading just when link is clicked
			success: function(html){ //so, if data is retrieved, store it in html
				jQuery("#loading").fadeOut('slow');
				jQuery("#formstatus").html( html ); //show the html inside formstatus div
				jQuery("#formstatus").fadeIn("fast"); //animation

			}
		}); //close jQuery.ajax
		return false;
	})
})
-->
</script>
<style type='text/css'>
#loading {background:url(images/loading.gif) center top no-repeat; text-align:center;padding:33px 0px 0px 0px; font-size:12px;display:none; font-family:Verdana, Arial, Helvetica, sans-serif; }
</style>
<div class="wrap">
<?php
if (get_option('ken_transformer_importer_connect_status')){
$html= base64_decode(get_option('ken_transformer_importer_connect_status')); //get info
$xml=simplexml_load_string($html) or die("Error: Cannot get info"); //read info
$status=base64_encode($xml->status); //load library
$site=($xml->site); //load library
if (($site==$_SERVER['HTTP_HOST']) && ($status=="YWN0aXZhdGVk")) {
echo "<div id=\"message\" class=\"updated fade\" style='color:blue;'><p>Activated</p></div>\n";
}
}
?>

<form method="post" name="formsearch" >
<table class="form-table">
            <tr>
                <th style="width:100px;"><label for="sample_license_key">Purchased Email</label></th>
                <td ><input class="regular-text" type="text" id="ken_transformer_license_key_ok" name="ken_transformer_license_key_ok"  value="<?php echo get_option('ken_transformer_license_key_ok'); ?>" ></td>
            </tr>
			<tr>
                <th style="width:100px;"><label for="sample_license_key">Order Number#:</label></th>
                <td ><input class="regular-text" type="text" id="ken_transformer_order_code" name="ken_transformer_order_code" value=""></td>
            </tr>
        </table>
        <p class="submit">
            <input type="submit" name="activate_license" id='active' value="Activate" class="button-primary" />
        </p>
		  <input type="hidden" name="type" value="update_options" />
<div id='formstatus'></div>

<p><div id='loading'>LOADING!</div></p>
</form>

</div>