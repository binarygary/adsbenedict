<?php
/*
Plugin Name: Ads Benedict
Version: 0.1
Description: Add Ads to Your WordPress
Author: Gary Kovar
Author URI: http://www.binarygary.com
Text Domain: adsbenedict
Domain Path: /languages
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

require('post-types/adsbenedict.php');
require('taxonomies/zone.php');
require('taxonomies/advertisers.php');

function adsbenedict_shortcode($attr) {
	
	foreach ($attr as $index=>$key) {
		//get ads that are assigned to this zone...and do something with them
		if ('zone'==$index) {
			$args = array (
				'tax_query' => array ( 
					array (
						'taxonomy' => $index,
						'field'	=> 'slug',
						'terms' => $key,
						'operator' => 'IN',
					),
				),
				'post_type'	=> array( 'adsbenedict' ),
				'fields' => 'ids',
			);
			$adids=new WP_Query($args);
			shuffle($adids->posts);
			
			$url=get_post_meta($adids->posts[0],'adsbenedict_url',true);
			echo "<a href=$url>";
			echo get_the_post_thumbnail($adids->posts[0],'full');
			echo "</a>";
			
		}
	}
	
}
add_shortcode('adsbenedict','adsbenedict_shortcode');