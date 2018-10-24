<?php
/**
 * @package  MythemePlugin
 */
namespace Inc\Base;
class Activate
{
	public static function activate() {
		flush_rewrite_rules();
		if ( get_option( 'mytheme_cpt_advanced_plugin' ) ) {
			return;
		}
		$default = array();
		update_option( 'mytheme_cpt_advanced_plugin', $default );
	}
}