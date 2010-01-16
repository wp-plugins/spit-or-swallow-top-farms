<?php
/*
Plugin Name: Spit or Swallow Top Farms
Plugin URI: http://406.co.za/wordpress/plugins/spit-or-swallow-top-farms/
Description: Add a sidebar widget with the top 10 rated wine estates in South Africa. You can also use <? get_top_farms(); ?> directly in your template files if widgets are not your thing.
Author: Jan Laubscher
Version: 1
Author URI: http://406.co.za/
*/
//get_top_farms
function get_top_farms(){
	echo "<script type='text/javascript' src='http://www.spitorswallow.co.za/wp-farmrank.php' ></script>";
}

function widget_top_frames($args) {
	  extract($args);
	  echo $before_widget;
	  echo $before_title;?>Top Ranked Wineries<?php echo $after_title;
	  echo '<ul>';
	  get_top_farms();
	  echo '</ul>';
	  echo $after_widget;
}

function top_farms_init(){
	register_sidebar_widget(__('Top Ranked Wineries'), 'widget_top_frames');    
	
}


function top_farms_activated() {
global $pagenow;
	if ( $pagenow == 'plugins.php' || $pagenow == 'widgets.php' ){
		if(!is_active_widget('widget_top_frames')){
			echo '<div id="akismet-warning" class="updated fade"><p>Thank you for activating the Spit or Swallow Top Farm Widget. You now need to add it one of your websites sidedar by using your <a href="'.get_bloginfo('url').'/wp-admin/widgets.php">widget manager</a></p></div>';
		}
	}
}

add_action("plugins_loaded", "top_farms_init");
add_action('admin_notices', 'top_farms_activated');
?>