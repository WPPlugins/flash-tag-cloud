<?
// Loading configurations
require_once "../../../wp-config.php";

global $wpdb;
$siteurl = get_option('siteurl');
$tag_base = (get_option('tag_base') == '') ? 'tag/' : get_option('tag_base') . '/';
$flashtagcloud_settings = get_option('flashtagcloud_settings');
$use_pretty_urls = (get_option('permalink_structure') !='') ? TRUE : FALSE;

if ( $use_pretty_urls ) $base_url = $site_url . '/'. $tag_base;
else $base_url = $site_url . '/index.php?tag=';

$maximum_tag_count = (is_int($flashtagcloud_settings['number'])) ? $flashtagcloud_settings['number'] : 40;

// Print flash configurations

echo '&tagColor=' . $flashtagcloud_settings['color'];
echo "&base_url=$base_url";
echo "&tagFontName=ygo220";
echo "&tagMini=50";
echo "&tagMinimize=1.2";
echo "&tagMaximize=2.3";
echo "&tagFontSize=18";
echo "&tagUseage=15";
echo "&tagBlurRange=2";
echo "&tagMoveScale=5";
echo "&tagXpadding=100";
echo "&tagYpadding=50";
echo "&tagAlphaRange=2";
echo "&tagComeback=7";
echo "&tagScaleSpeed=5";
echo '&url=' . $siteurl;

$total_tag_count = $wpdb->get_var("SELECT COUNT(DISTINCT a.term_id) FROM `" . $wpdb->term_taxonomy . "` a WHERE a.taxonomy = 'post_tag' AND a.count != 0");

// Get total tags list.
$total_tags = $wpdb->get_results("SELECT a.name, a.slug, b.count FROM  `" . $wpdb->terms . "` a, `" . $wpdb->term_taxonomy . "` b WHERE a.term_id = b.term_id AND b.taxonomy = 'post_tag' AND b.count != 0 GROUP BY a.term_id", 'ARRAY_N');

if ( $maximum_tag_count <= $total_tag_count ) $displayed_tag_count = $maximum_tag_count;
else $displayed_tag_count = $total_tag_count;

// Generate random numbers.
$random_made = 0;
$random_log = "";
for ($i=0; $i < ($total_tag_count + $maximum_tag_count); $i++) {
	mt_srand((double) microtime() * 2147483647);
	$random_tags = mt_rand(0, $total_tag_count - 1);
	if ( !eregi($random_tags, $random_log) ) {
		$random_nums[$random_made] = $random_tags;
		$random_log .= $random_tags . "_";
		$random_made++;
		if ( $random_made == $displayed_tag_count ) break;
	} else continue;
}
// Print output.
for ( $i=0; $i < $displayed_tag_count; $i++ ) {
	$tag_name = $total_tags[$random_nums[$i]][0];
	$tag_slug = $total_tags[$random_nums[$i]][1];
	$tag_counts = $total_tags[$random_nums[$i]][2];
	echo '&tag' . ($i + 1) . '=' . $tag_name;
	echo '&slug' . ($i + 1) . '=' . $tag_slug;
	echo '&useage' . ($i + 1) . '=' . $tag_counts;
}

?>
