<?php
/*
Plugin Name: Spit or Swallow Top Farms
Plugin URI: http://406.co.za/wordpress/plugins/spit-or-swallow-top-farms/
Description: Add a sidebar widget with the top 10 rated wine estates in South Africa. You can also use <? get_top_farms(); ?> directly in your template files if widgets are not your thing.
Author: Jan Laubscher
Version: 2.0.1
Author URI: http://406.co.za/
*/

class SosWidget extends WP_Widget {
    /** constructor */
    function SosWidget() {
        parent::WP_Widget(false, $name = 'Spit or Swallow Widget');	
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {		
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
		$count = $instance['count'];
		
		if($instance['route']){
			$route = '?cat='.$instance['route'];
		}else{
			$route = '';
		}
        
		
			echo $before_widget; 
				if ( $title )
                echo $before_title . $title . $after_title; 
						
						
				include_once(ABSPATH . WPINC . '/feed.php');
				$rss = fetch_feed('http://spitorswallow.co.za/wp-farmrank.php'.$route);
				$rss->enable_order_by_date(false);
				
				if (!is_wp_error( $rss ) ) : // Checks that the object is created correctly 
					$maxitems = $rss->get_item_quantity($count); 
					$rss_items = $rss->get_items(0, $maxitems); 
				endif;
				echo '<ul class="toprank">';
				if ($maxitems == 0){
					echo '<li>Not Available.</li>';
				}else{
				
					foreach ( $rss_items as $item ) : ?>
					<li>
					<a href="<?php echo $item->get_permalink(); ?>" title="View <?php echo $item->get_title(); ?>'s profile" target="_blank">
					<?php echo $item->get_title(); ?></a><span><?=$item->get_content()?></span>
					</li>
					<?php endforeach; 
				}
                 echo '</ul>';
                 
              echo $after_widget; 
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {				
	$instance = $old_instance;
	$instance['title'] = strip_tags($new_instance['title']);
	$instance['count'] = strip_tags($new_instance['count']);
	$instance['route'] = strip_tags($new_instance['route']);
    return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {				
        $title = esc_attr($instance['title']);
		$count = esc_attr($instance['count']);
		$route = esc_attr($instance['route']);
        ?>
            <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
            
            <p><label for="<?php echo $this->get_field_id('route'); ?>"><?php _e('Select a Route'); ?> 
             <select class="widefat" id="<?php echo $this->get_field_id('route'); ?>" name="<?php echo $this->get_field_name('route'); ?>">
             <option value="">All Wineries</option>
             <option value="23" <? if($route==23){?> selected="selected"<? }?> >Breedekloof</option>
             <option value="7" <? if($route==7){?> selected="selected"<? }?>>Constantia</option>
             <option value="5" <? if($route==5){?> selected="selected"<? }?>>Darling</option>
             <option value="26" <? if($route==26){?> selected="selected"<? }?>>Durbanville</option>
             <option value="27" <? if($route==27){?> selected="selected"<? }?>>Franschhoek</option>
             <option value="71" <? if($route==71){?> selected="selected"<? }?>>Klein Karoo</option>
             <option value="9" <? if($route==9){?> selected="selected"<? }?>>Olifantsriver</option>
             <option value="22" <? if($route==22){?> selected="selected"<? }?>>Overberg</option>
             <option value="20" <? if($route==20){?> selected="selected"<? }?>>Paarl</option>
             <option value="3" <? if($route==3){?> selected="selected"<? }?>>Robertson</option>
             <option value="15" <? if($route==15){?> selected="selected"<? }?>>Stellenbosch</option>
             <option value="4" <? if($route==4){?> selected="selected"<? }?>>Swartland</option>
             <option value="6" <? if($route==6){?> selected="selected"<? }?>>Tulbagh</option>
             <option value="24" <? if($route==24){?> selected="selected"<? }?>>Wellington</option>
             <option value="21" <? if($route==21){?> selected="selected"<? }?>>Worcester</option>
             </select></label></p>
            
             <p><label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Wineries to Display'); ?> <input class="widefat" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="text" value="<?php echo $count; ?>" /></label></p>
             
             
        <?php 
    }

} 

function top_farms_activated() {
global $pagenow;
	if ( $pagenow == 'plugins.php' || $pagenow == 'widgets.php' ){
		if(!is_active_widget('widget_top_frames')){
			echo '<div id="akismet-warning" class="updated fade"><p>Thank you for activating the Spit or Swallow Widget. You now need to add it to one of your website sidebars by using your <a href="'.get_bloginfo('url').'/wp-admin/widgets.php">widget manager.</a></p></div>';
		}
	}
}

// register SosWidget widget
add_action('widgets_init', create_function('', 'return register_widget("SosWidget");'));
add_action('admin_notices', 'top_farms_activated');