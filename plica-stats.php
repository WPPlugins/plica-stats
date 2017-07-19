<?php
/*
 Plugin Name: Plica Stats
 Plugin URI: http://plicazaragozame.es
 Description: Stats for Plica Zaragozame
 Author: César Laso (Plica Zaragozame S.L.)
 Author URI: http://cesarlaso.com
 Text Domain: plica-stats
 Version: 1.0.161
 */
 
define('PLICA_STATS_VERSION', '1.0.161');
define('PLICA_STATS_BASE_URL', 'index.php?page=plica-stats/plica-stats.php');

define('PLICA_STATS_URL', 'http://plicastats.plicazaragozame.es');
define('PLICA_STATS_TRACKER_URL', PLICA_STATS_URL . '/tracker.js');
define('PLICA_STATS_URL_SWF', PLICA_STATS_URL . '/open-flash-chart.swf');

define('PLICA_STATS_API_URL', PLICA_STATS_URL . '/api');
define('PLICA_STATS_API_URL_REGISTER', PLICA_STATS_API_URL . '/register');
define('PLICA_STATS_API_URL_TOP_REFERERS_BY_DATE', PLICA_STATS_API_URL . '/%d/top-referers/%d');
define('PLICA_STATS_API_URL_TOP_REFERERS_BY_LAST_DAYS', PLICA_STATS_API_URL . '/%d/top-referers/last/%d');
define('PLICA_STATS_API_URL_TOP_REFERERS_BY_ALL', PLICA_STATS_API_URL . '/%d/top-referers/all');
define('PLICA_STATS_API_URL_TOP_SEARCHES_BY_DATE', PLICA_STATS_API_URL . '/%d/top-searches/%d');
define('PLICA_STATS_API_URL_TOP_SEARCHES_BY_LAST_DAYS', PLICA_STATS_API_URL . '/%d/top-searches/last/%d');
define('PLICA_STATS_API_URL_TOP_SEARCHES_BY_ALL', PLICA_STATS_API_URL . '/%d/top-searches/all');
define('PLICA_STATS_API_URL_TOP_VISITS_BY_DATE', PLICA_STATS_API_URL . '/%d/top-visits/%d');
define('PLICA_STATS_API_URL_TOP_VISITS_BY_LAST_DAYS', PLICA_STATS_API_URL . '/%d/top-visits/last/%d');
define('PLICA_STATS_API_URL_TOP_VISITS_BY_ALL', PLICA_STATS_API_URL . '/%d/top-visits/all');

define('PLICA_STATS_API_URL_GRAPH_LAST30DAYS', PLICA_STATS_API_URL . '/%d/graph/last30days');

function plica_stats_id() {
	return get_option('plica_stats_id');
}

function plica_stats_api_register() {
	$url = PLICA_STATS_API_URL_REGISTER;
	$wh = new WP_Http();
	$body = http_build_query(array(
		'url' => get_bloginfo('url'),
	));
	$res = $wh->request($url, array('method'=>'POST', 'body'=>$body));
	$id = (int)json_decode($res['body']);
	add_option('plica_stats_id', $id) or
		update_option('plica_stats_id', $id);
}

function plica_stats_activate() {
	plica_stats_api_register();
}
function plica_stats_deactivate() {
	delete_option('plica_stats_id');
}

register_activation_hook( __FILE__, 'plica_stats_activate' );
register_deactivation_hook(__FILE__, 'plica_stats_deactivate');


function plica_stats_api_get_raw($url) {
	$wh = new WP_Http();
	$res = $wh->request($url);
	if (isset($res->errors)) {
		return null;
	}
	return $res['body'];
}

function plica_stats_api_get($url) {
	$wh = new WP_Http();
	$res = $wh->request($url);
	if (isset($res->errors)) {
		return null;
	}
	return json_decode($res['body']);
}

function plica_stats_api_top_referers_by_date($time) {
	$url = sprintf(PLICA_STATS_API_URL_TOP_REFERERS_BY_DATE, plica_stats_id(), $time);
	return plica_stats_api_get($url);
}
function plica_stats_api_top_referers_by_last_days($days) {
	$url = sprintf(PLICA_STATS_API_URL_TOP_REFERERS_BY_LAST_DAYS, plica_stats_id(), $days);
	return plica_stats_api_get($url);
}
function plica_stats_api_top_referers_by_all() {
	$url = sprintf(PLICA_STATS_API_URL_TOP_REFERERS_BY_ALL, plica_stats_id());
	return plica_stats_api_get($url);
}

function plica_stats_api_top_searches_by_date($time) {
	$url = sprintf(PLICA_STATS_API_URL_TOP_SEARCHES_BY_DATE, plica_stats_id(), $time);
	return plica_stats_api_get($url);
}
function plica_stats_api_top_searches_by_last_days($days) {
	$url = sprintf(PLICA_STATS_API_URL_TOP_SEARCHES_BY_LAST_DAYS, plica_stats_id(), $days);
	return plica_stats_api_get($url);
}
function plica_stats_api_top_searches_by_all() {
	$url = sprintf(PLICA_STATS_API_URL_TOP_SEARCHES_BY_ALL, plica_stats_id());
	return plica_stats_api_get($url);
}


function plica_stats_api_top_visits_by_date($time) {
	$url = sprintf(PLICA_STATS_API_URL_TOP_VISITS_BY_DATE, plica_stats_id(), $time);
	return plica_stats_api_get($url);
}
function plica_stats_api_top_visits_by_last_days($days) {
	$url = sprintf(PLICA_STATS_API_URL_TOP_VISITS_BY_LAST_DAYS, plica_stats_id(), $days);
	return plica_stats_api_get($url);
}
function plica_stats_api_top_visits_by_all() {
	$url = sprintf(PLICA_STATS_API_URL_TOP_VISITS_BY_ALL, plica_stats_id());
	return plica_stats_api_get($url);
}



function plica_stats_url_cut_http($url) {
	return substr($url, 7);
}
function plica_stats_url_cut($url, $max_length=50, $extra='...') {
	if (strlen($url) > $max_length) {
		$extra_l = strlen($extra);
		return substr($url, 0, $max_length-$extra_l) . $extra;
	}
	return $url;
}

function plica_str_starts_with($str1, $str2) {
	$l1 = strlen($str1);
	$l2 = strlen($str2);
	if ($l1 < $l2) return false;
	return (substr($str1, 0, $l2) == $str2);
}


function plica_stats_google_title_blog($guid) {
	
}

function plica_stats_good_title($title) {
	$home = get_bloginfo('url');
	
	if (($title == $home) || ($title == ($home . '/'))) {
		return __('Home');
	} else {
		if (plica_str_starts_with($title, $home)) {
			
			if (preg_match('/tag\/(.+)\//', $title, $matches)) {
				return __('Tag') . ': ' . $matches[1];
			}
			if (preg_match('/page\/(.+)\//', $title, $matches)) {
				return __('Page') . ': ' . $matches[1];
			}
			if (preg_match('/category\/(.+)\//', $title, $matches)) {
				return __('Category') . ': ' . $matches[1];
			}
			
			global $wpdb;
			$post_guid = $title;
			$post_title = $wpdb->get_var($wpdb->prepare("SELECT post_title FROM $wpdb->posts WHERE guid=%s", $post_guid));
			if ($post_title != '') {
				return $post_title;
			} else {
				return plica_stats_url_cut($title);
			}
		} else {
			return plica_stats_url_cut($title);
		}
	}
}

function plica_stats_table($table_title, $th_title, $th_title_value, $data, $max_rows) {
	?>
	<h4 class="table-title"><?php echo $table_title;?></h4>
	
	<p class="total">
		<?php if (isset($data->total)) { ?>
		<?php echo __('Total', 'plica-stats');?>:	<?php echo $data->total;?>
		<?php } ?>
	</p>
	<?php
	$rows = $data->rows;
	if (count($rows) == 0) {
		return;
	}
	?>
	<table>
	<tr>
		<th><?php echo $th_title;?></th>
		<th class="counter"><?php echo $th_title_value;?></th>
	</tr>
	<?php
	$max = min(count($rows), $max_rows);
	for ($i=0; $i<$max; $i++) {
		$r = $rows[$i];
		$tr_class = ($i%2==0) ? 'alternate' : '';
		?>
		<tr class="<?php echo $tr_class;?>">
			<td>
				<?php if (plica_str_starts_with($r->title, 'http')) { ?>
				<a href="<?php echo $r->title;?>">
					<?php echo plica_stats_good_title($r->title);?>
				</a>
				<?php } else { ?>
					<?php echo $r->title;?>
				<?php } ?>
			</td>
			<td class="counter"><?php echo $r->value;?></td>
		</tr>
		<?php
	}
	?>
	</table>
	<?php
}


function plica_stats_graph() {
	$swf_ofc = sprintf(PLICA_STATS_API_URL_GRAPH_LAST30DAYS, plica_stats_id());
	?>
	<div class="plica-stats-graph">
		<p>
			<a href=""><?php _e('Days', 'plica-stats');?></a>
			<a href=""><?php _e('Weeks', 'plica-stats');?></a>
			<a href=""><?php _e('Months', 'plica-stats');?></a>
		</p>
		<div id="plica-stats-graph-wrapper">
			<iframe class="plica-stats-graph-iframe" src="<?php echo PLICA_STATS_URL_SWF;?>?ofc=<?php echo $swf_ofc;?>"></iframe>	
		</div>
	</div>
	<?php
}


function plica_stats_time_today() {
	$now = time();
	$today = mktime(0, 0, 0, date('n', $now), date('j', $now), date('Y', $now));
	return $today;
}

function plica_stats_referers() {
	$today = plica_stats_time_today();
	$yesterday = $today - (24*60*60);
?>
<div class="plica-stats-div">
	<h3>
		<?php _e('Referers', 'plica-stats');?>
		-
		<a class="see-full-report" href="<?php echo PLICA_STATS_BASE_URL;?>&plica-stats-page=referers-full-report">
			<?php _e('See full report', 'plica-stats');?>
		</a>
	</h3>
	<?php
		plica_stats_table(
			__('Today', 'plica-stats'),
			__('Web','plica-stats'),
			__('Views','plica-stats'),
			plica_stats_api_top_referers_by_date($today),
			7
		);
	?>
	<?php
		plica_stats_table(
			__('Yesterday', 'plica-stats'),
			__('Web','plica-stats'),
			__('Views','plica-stats'),
			plica_stats_api_top_referers_by_date($yesterday),
			4
		);
	?>
</div>
<?php
}


function plica_stats_searches() {
	$today = plica_stats_time_today();
	$yesterday = $today - (24*60*60);
?>
<div class="plica-stats-div">
	<h3>
		<?php _e('Search engine terms', 'plica-stats');?>
		-
		<a class="see-full-report" href="<?php echo PLICA_STATS_BASE_URL;?>&plica-stats-page=searches-full-report">
			<?php _e('See full report', 'plica-stats');?>
		</a>
	</h3>
	<?php
		plica_stats_table(
			__('Today', 'plica-stats'),
			__('Search','plica-stats'),
			__('Views','plica-stats'),
			plica_stats_api_top_searches_by_date($today),
			7
		);
	?>
	<?php
		plica_stats_table(
			__('Yesterday', 'plica-stats'),
			__('Search','plica-stats'),
			__('Views','plica-stats'),
			plica_stats_api_top_searches_by_date($yesterday),
			4
		);
	?>
</div>
<?php
}


function plica_stats_page_searches_full_report() {
	$hoy = plica_stats_time_today();
	?>
	<h2><?php _e('Plica Stats - Top search engine terms', 'plica-stats');?></h2>
	<p><?php _e('Updated every 12 hours', 'plica-stats');?></p>
	<p>
		<a href="#plica-stats-div-report-7-days"><?php _e('Last 7 days', 'plica-stats');?></a> -
		<a href="#plica-stats-div-report-30-days"><?php _e('Last 30 days', 'plica-stats');?></a> - 
		<a href="#plica-stats-div-report-365-days"><?php _e('Last 365 days', 'plica-stats');?></a> -
		<a href="#plica-stats-div-report-all"><?php _e('All time', 'plica-stats');?></a>
	</p>
	<div id="plica-stats-div-report-7-days" class="plica-stats-div-report">
	<h3><?php _e('Last 7 days', 'plica-stats');?></h3>
	<?php for ($i=0; $i<7; $i++) { ?>
		<div class="plica-stats-div">
		<?php
		$fecha = $hoy - ($i*(24*60*60));
		plica_stats_table(
			date('j F Y', $fecha),
			__('Search','plica-stats'),
			__('Views','plica-stats'),
			plica_stats_api_top_searches_by_date($fecha),
			10
		);
		?>
		</div>
		<?php if (($i - 1) % 2 == 0) { ?> <div style="clear:both"></div><?php } ?>
	<?php } ?>
	</div>
	<div style="clear:both"></div>
	
	<h3><?php _e('Summarize', 'plica-stats');?></h3>
	
	<div id="plica-stats-div-report-30-days" class="plica-stats-div-report">
	<?php
	plica_stats_table(
			__('Last 30 days', 'plica-stats'),
			__('Search','plica-stats'),
			__('Views','plica-stats'),
			plica_stats_api_top_searches_by_last_days(30),
			20
	);
	?>
	</div>
	<div style="clear:both"></div>
	
	<div id="plica-stats-div-report-365-days" class="plica-stats-div-report">
	<?php 
	plica_stats_table(
			__('Last 365 days', 'plica-stats'),
			__('Search','plica-stats'),
			__('Views','plica-stats'),
			plica_stats_api_top_searches_by_last_days(365),
			20
	);
	?>
	</div>
	<div style="clear:both"></div>
	
	<div id="plica-stats-div-report-all" class="plica-stats-div-report">
	<?php 
	plica_stats_table(
			__('All time', 'plica-stats'),
			__('Search','plica-stats'),
			__('Views','plica-stats'),
			plica_stats_api_top_searches_by_all(),
			20
	);
	?>
	</div>
	<?php
}
function plica_stats_page_referers_full_report() {
	$hoy = plica_stats_time_today();
	?>
	<h2><?php _e('Plica Stats - Top referers', 'plica-stats');?></h2>
	<p><?php _e('Updated every 12 hours', 'plica-stats');?></p>
	<p>
		<a href="#plica-stats-div-report-7-days"><?php _e('Last 7 days', 'plica-stats');?></a> -
		<a href="#plica-stats-div-report-30-days"><?php _e('Last 30 days', 'plica-stats');?></a> - 
		<a href="#plica-stats-div-report-365-days"><?php _e('Last 365 days', 'plica-stats');?></a> -
		<a href="#plica-stats-div-report-all"><?php _e('All time', 'plica-stats');?></a>
	</p>
	<div id="plica-stats-div-report-7-days" class="plica-stats-div-report">
	<h3><?php _e('Last 7 days', 'plica-stats');?></h3>
	<?php for ($i=0; $i<7; $i++) { ?>
		<div class="plica-stats-div">
		<?php
		$fecha = $hoy - ($i*(24*60*60));
		plica_stats_table(
			date('j F Y', $fecha),
			__('Referer','plica-stats'),
			__('Views','plica-stats'),
			plica_stats_api_top_referers_by_date($fecha),
			10
		);
		?>
		</div>
		<?php if (($i - 1) % 2 == 0) { ?> <div style="clear:both"></div><?php } ?>
	<?php } ?>
	</div>
	<div style="clear:both"></div>
	
	<h3><?php _e('Summarize', 'plica-stats');?></h3>
	
	<div id="plica-stats-div-report-30-days" class="plica-stats-div-report">
	<?php
	plica_stats_table(
			__('Last 30 days', 'plica-stats'),
			__('Referer','plica-stats'),
			__('Views','plica-stats'),
			plica_stats_api_top_referers_by_last_days(30),
			20
	);
	?>
	</div>
	<div style="clear:both"></div>
	
	<div id="plica-stats-div-report-365-days" class="plica-stats-div-report">
	<?php 
	plica_stats_table(
			__('Last 365 days', 'plica-stats'),
			__('Referer','plica-stats'),
			__('Views','plica-stats'),
			plica_stats_api_top_referers_by_last_days(365),
			20
	);
	?>
	</div>
	<div style="clear:both"></div>
	
	<div id="plica-stats-div-report-all" class="plica-stats-div-report">
	<?php 
	plica_stats_table(
			__('All time', 'plica-stats'),
			__('Referer','plica-stats'),
			__('Views','plica-stats'),
			plica_stats_api_top_referers_by_all(),
			20
	);
	?>
	</div>
	<?php
}

function plica_stats_page_visits_full_report() {
	$hoy = plica_stats_time_today();
	?>
	<h2><?php _e('Plica Stats - Top visits', 'plica-stats');?></h2>
	<p><?php _e('Updated every 12 hours', 'plica-stats');?></p>
	<p>
		<a href="#plica-stats-div-report-7-days"><?php _e('Last 7 days', 'plica-stats');?></a> -
		<a href="#plica-stats-div-report-30-days"><?php _e('Last 30 days', 'plica-stats');?></a> - 
		<a href="#plica-stats-div-report-365-days"><?php _e('Last 365 days', 'plica-stats');?></a> -
		<a href="#plica-stats-div-report-all"><?php _e('All time', 'plica-stats');?></a>
	</p>

	<div id="plica-stats-div-report-7-days" class="plica-stats-div-report">
	<h3><?php _e('Last 7 days', 'plica-stats');?></h3>
	<?php for ($i=0; $i<7; $i++) { ?>
		<div class="plica-stats-div">
		<?php
		$fecha = $hoy - ($i*(24*60*60));
		plica_stats_table(
			date('j F Y', $fecha),
			__('Page','plica-stats'),
			__('Views','plica-stats'),
			plica_stats_api_top_visits_by_date($fecha),
			10
		);
		?>
		</div>
		<?php if (($i - 1) % 2 == 0) { ?> <div style="clear:both"></div><?php } ?>
	<?php } ?>
	</div>
	<div style="clear:both"></div>
	
	<h3><?php _e('Summarize', 'plica-stats');?></h3>
	
	<div id="plica-stats-div-report-30-days" class="plica-stats-div-report">
	<?php
	plica_stats_table(
			__('Last 30 days', 'plica-stats'),
			__('Page','plica-stats'),
			__('Views','plica-stats'),
			plica_stats_api_top_visits_by_last_days(30),
			20
	);
	?>
	</div>
	<div style="clear:both"></div>
	
	<div id="plica-stats-div-report-365-days" class="plica-stats-div-report">
	<?php 
	plica_stats_table(
			__('Last 365 days', 'plica-stats'),
			__('Page','plica-stats'),
			__('Views','plica-stats'),
			plica_stats_api_top_visits_by_last_days(365),
			20
	);
	?>
	</div>
	<div style="clear:both"></div>
	
	<div id="plica-stats-div-report-all" class="plica-stats-div-report">
	<?php 
	plica_stats_table(
			__('All time', 'plica-stats'),
			__('Page','plica-stats'),
			__('Views','plica-stats'),
			plica_stats_api_top_visits_by_all(),
			20
	);
	?>
	</div>
	<?php
}


function plica_stats_visits() {
	$today = plica_stats_time_today();
	$yesterday = $today - (24*60*60);
?>
<div class="plica-stats-div">
	<h3>
		<?php _e('Top visits', 'plica-stats');?>
		-
		<a class="see-full-report" href="<?php echo PLICA_STATS_BASE_URL;?>&plica-stats-page=visits-full-report">
			<?php _e('See full report', 'plica-stats');?>
		</a>
	</h3>
	<?php
		plica_stats_table(
			__('Today', 'plica-stats'),
			__('Page','plica-stats'),
			__('Views','plica-stats'),
			plica_stats_api_top_visits_by_date($today),
			7
		);
	?>
	<?php
		plica_stats_table(
			__('Yesterday', 'plica-stats'),
			__('Page','plica-stats'),
			__('Views','plica-stats'),
			plica_stats_api_top_visits_by_date($yesterday),
			4
		);
	?>
</div>
<?php
}

function plica_stats_styles() {
?>
	<style type="text/css">
		.plica-wrap h3 {
			border-bottom: 1px solid #ccc;
		}
		
		.plica-stats-div {
			width: 44%;
			float: left;
			margin-right: 4%;
		}
		.plica-wrap table {
			clear: left;
			width: 100%;
		}
		.plica-wrap tr {
			height: 22px;
		}
		.plica-wrap tr.alternate {
			background-color: #E6F0FF;
		}
		.plica-wrap th {
			border-bottom:2px solid #ccc;
			text-align:left;
		}
		.plica-wrap th.counter, .plica-wrap td.counter {
			text-align: center;
			width:6em;
		}
		.plica-wrap td {
		}
		.plica-stats-graph-iframe {
			width: 650px;
			height: 265px;
			margin: 0 auto;
		}
		#plica-stats-graph-wrapper {
			width: 650px;
			height: 265px;
			margin: 0 auto;
			background: #fff;
		}
		
		.plica-wrap h4.table-title {
			float: left;
		}
		.plica-wrap p.total {
			float: right;
		}
		.plica-wrap a.see-full-report {
			text-decoration: none;
			font-size: 0.8em;
		}
		
		
	</style>
<?php
}

function plica_stats_default_page() {
?>
	<h2><?php _e('Plica Stats', 'plica-stats');?></h2>
	<p><?php _e('Updated every hour', 'plica-stats');?></p>
	<?php plica_stats_graph(); ?>
	<div style="clear:both"></div>
	<?php
		plica_stats_referers();
		plica_stats_visits();
	?>
	<div style="clear:both"></div>
	<?php plica_stats_searches(); ?>
	<?php
}

function plica_stats_home() { 
	plica_stats_styles();
	?>
	<div class="wrap plica-wrap plica-stats-wrap">
	<?php
	$plica_pages = array(
		'' => 'plica_stats_default_page',
		'visits-full-report' => 'plica_stats_page_visits_full_report',
		'searches-full-report' => 'plica_stats_page_searches_full_report',
		'referers-full-report' => 'plica_stats_page_referers_full_report',
	);
	$plica_page = isset($_GET['plica-stats-page']) ? $_GET['plica-stats-page'] : '';
	if (array_key_exists($plica_page, $plica_pages)) {
		call_user_func($plica_pages[$plica_page]);
	}
	?>
	</div>
<?php 
}

function plica_stats_wp_footer() {
	$id = plica_stats_id();
?>
<script type="text/javascript">
	var plica_stats_id = "<?php echo $id;?>";
</script>
<script type="text/javascript" src="<?php echo PLICA_STATS_TRACKER_URL;?>"></script>
<?php
}

function plica_stats_admin_menu() {
	$capability = 'read';
	add_dashboard_page(__('Plica Stats', 'plica-stats'), __('Plica Stats', 'plica-stats'), $capability, __FILE__, 'plica_stats_home');
}

function plica_stats_init_language() {
	$plugin_dir = basename(dirname(__FILE__));
	$lang_dir = '/languages';
	load_plugin_textdomain( 'plica-stats', WP_PLUGIN_DIR . $plugin_dir . $lang_dir, $plugin_dir . $lang_dir);
}
add_action('init', 'plica_stats_init_language');

function plica_stats_init() {
	if (is_admin()) {
		add_action('admin_menu', 'plica_stats_admin_menu');
	} else {
		add_action('wp_footer', 'plica_stats_wp_footer');
	}
}
add_action('init', 'plica_stats_init');

?>