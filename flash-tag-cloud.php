<?php
/*
Plugin Name: Flash Tag Cloud
Plugin URI: http://meiclamo.net/wordpress/wordpress-plugin-flash-tag-cloud
Description: Build a tag cloud using flash. Just write {flashtagcloud} on posts or pages where you wanna insert, then it will work.
Version: 0.4
Author: <a href="http://meiclamo.net/">智熏</a>
*/

$ol_flash = '';

if (isset($_POST['width'])) $flashtagcloud_settings['width'] = $_POST['width'];
if (isset($_POST['height'])) $flashtagcloud_settings['height'] = $_POST['height'];
if (isset($_POST['number'])) $flashtagcloud_settings['number'] = $_POST['number'];
if (isset($_POST['color'])) $flashtagcloud_settings['color'] = $_POST['color'];

if ($flashtagcloud_settings) {
  update_option('flashtagcloud_settings',$flashtagcloud_settings);
  $ol_flash = "Your settings have been saved.";
}
else $flashtagcloud_settings = get_option('flashtagcloud_settings');

function flashtagcloud_options_page() {

global $ol_flash, $flashtagcloud_settings, $_POST;
if (!$flashtagcloud_settings) { //set default
	$ol_flash = 'You run Flash Tag Cloud first. To get started, update options now - Click [Update Options] button at the bottom of this page.';
	$flashtagcloud_settings['width'] = 500;
	$flashtagcloud_settings['height'] = 400;
	$flashtagcloud_settings['number'] = 40;
	$flashtagcloud_settings['color'] = 'red';
}
if ( $ol_flash != '' ) echo '<div id="message" class="updated fade"><p>' . $ol_flash . '</p></div>';
?>
<div class="wrap">
	<h2>Configuration for Flash Tag Cloud</h2>
	<p>Default values are in parenthesis. Strongly recommended that no values under the horizontal line are altered.</p>
	<form action="" method="post">
	<fieldset>
	<table class="optiontable">
		<tr>
			<th>Flash Width(500):</th>
			<td><input id="width" name="width" type="text" value="<?php echo $flashtagcloud_settings['width'];?>">px</td>
		</tr>
		<tr>
			<th>Flash Height(400):</th>
			<td><input id="height" name="height" type="text" value="<?php echo $flashtagcloud_settings['height'];?>">px</td>
		</tr>
		<tr>
			<th>Color (red):</th>
			<td><select name="color">
<?php
$color = array('gray', 'cyan', 'red', 'yellowgreen', 'brown', 'orange', 'green', 'blue', 'violet');
foreach ( $color as $value ){
?><option value="<?php echo $value;?>"<?php if ($flashtagcloud_settings['color']==$value) echo ' selected';?>><?php 
echo $value;?></option>
<?php } ?></select></td>
		</tr>
		<tr>
			<th>Number of the displayed tags (40):</th>
			<td><input id="number" name="number" type="text" value="<?php echo $flashtagcloud_settings['number'];?>"></td>
		</tr>
	</table>
	<p class="submit"><input name="submit" value="Update Options &raquo;" type="submit"></p>
	</fieldset>
	</form>

</div>

<?php
}
function flashtagcloud($content) {
	global $flashtagcloud_settings;
	$siteurl = get_option('siteurl');
	$form = '<object type="application/x-shockwave-flash" data="' . $siteurl . '/wp-content/plugins/flash-tag-cloud/flash-tag-cloud.swf?tagf_url='.$siteurl.'/wp-content/plugins/flash-tag-cloud/" width="' . $flashtagcloud_settings['width'] . '" height="' . $flashtagcloud_settings['height'] . '"><param name="movie" value="' . $siteurl . '/wp-content/plugins/flash-tag-cloud/flash-tag-cloud.swf?tagf_url=' . $siteurl . '/wp-content/plugins/flash-tag-cloud/" /><param name="allowScriptAcess" value="sameDomain" /><param name="wmode" value="transparent"><param name="quality" value="best" /></object>';
	return preg_replace('|{flashtagcloud}|', $form, $content);
}

function flashtagcloud_admin_menu() {
	if ( function_exists('add_options_page') ) {
		add_options_page('Flash Tag Cloud Configuration','Flash Tag Cloud', 1, __FILE__,'flashtagcloud_options_page');
	}
}
add_filter('the_content', 'flashtagcloud', 1);
add_action('admin_menu', 'flashtagcloud_admin_menu');
?>
