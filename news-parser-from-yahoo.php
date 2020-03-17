<?php
/*
Plugin Name: News parser from yahoo.com (test task)
Description: Write a news parser for the economic section https://finance.yahoo.com/ and the section https://www.yahoo.com/entertainment/ - Every two hours, check for new entries in the source - Posts are published as wordpress entries in the / news section / and / entertainment /, respectively, and stored in the database of the site. - Save the picture from the news on your website (upload to the / uploads / folder) + assign it as thumbnail for recording.
Version: 1.0.0
Author: Vlad Korneev
Text Domain: news_parser_from_yahoo
*/

define( 'NPFY_TEXT_DOMAIN', 'news_parser_from_yahoo' );
define( 'NPFY_PATH_PLUGIN', plugin_dir_path( __FILE__ ) );
define( 'NPFY_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

require_once( NPFY_PATH_PLUGIN . 'class.NPFYImportSection.php' );
$NPFYImportSection = new NPFYImportSection();

register_activation_hook( __FILE__, 'npfy_activation_plugin' );
function npfy_activation_plugin() {
	wp_clear_scheduled_hook( 'adding_new_entries_from_rss' );
	wp_schedule_event( time(), 'two_hours', 'adding_new_entries_from_rss' );
}

register_deactivation_hook( __FILE__, 'npfy_deactivation_plugin' );
function npfy_deactivation_plugin() {
	wp_clear_scheduled_hook( 'adding_new_entries_from_rss' );
}