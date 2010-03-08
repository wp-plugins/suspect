<?php if ( '/wp-content/plugins/suspect/suspect.php' === getenv('SCRIPT_NAME') ) exit('No direct script access allowed.');
/*
Plugin Name: Suspect
Plugin URI: http://github.com/ampt/suspect
Description: A debugging toolbar for Wordpress.
Author: Luke Gallagher
Version: 0.1
Author URI: http://notfornoone.com/
*/

if( ! version_compare(PHP_VERSION, '5.2.0', '>=') ) {
	exit('Suspect requires PHP 5.2.x or higher to run. You are currently running PHP ' . PHP_VERSION . '.');
}

global $wp_version;
if( ! version_compare($wp_version, '2.9', '>=') ) {
	exit('Suspect requires Wordpress 2.9.x or higher to run. You are currently running Wordpress ' . $wp_version . '.');	
}

define('SUSPECTPATH', dirname(__FILE__));
require(SUSPECTPATH . '/keywords.php');

/**
 * Suspect Class
 *
 * Generate basic profiling information
 */
class Suspect {
	public $total_query_time = 0;
	
	
	/**
	 * Suspect Constructor
	 * 
	 * Add actions and hook everything up
	 */
	public function __construct() {
		add_action('wp_print_footer_scripts', array($this, 'run'), 20);
		add_action('init', array($this, 'init'));
		
		if ( !defined('SAVEQUERIES') )
			define('SAVEQUERIES', TRUE);
	}
	
	
	/**
	 * Run Suspect
	 * 
	 * @return	void
	 */
	public function run() {
		global $wp_actions, $wpdb;
		include(SUSPECTPATH.'/views/suspect_view.php');
	}
	
	
	/**
	 * Output POST data
	 * 
	 * @return	void
	 */
	public function post_data() {
		$output = "\n";
		
		if( count($_POST) == 0 ) {
			$output .= "<tr><td>No POST data</td></tr>\n";
		}
		else {
			foreach( $_POST as $key => $val ) {
				if( !is_numeric($key) ) {
					$key = "'{$key}'";
				}
				
				$output .= "<tr><td class='first'>&#36;_POST[{$key}]</td>";
				$output .= "<td>";

				if( is_array($val) ) {
					$output .= "<pre>" . htmlspecialchars(stripslashes(print_r($val, TRUE))) . "</pre>";
				}
				else {
					$output .= htmlspecialchars(stripslashes($val));
				}

				$output .= "</td></tr>\n";
			}
		}
		
		echo $output;
	}
	
	
	/**
	 * Output database information
	 * 
	 * @return	void
	 */
	public function wpdb_data()
	{
		global $wpdb, $sp_mysql_keywords;
		$output = "\n";
		
		// Key words highlight pattern
		$pattern = implode('\b|\b', $sp_mysql_keywords);
		$pattern = "/(\b{$pattern}\b)/";
		
		if( count($wpdb->queries) == 0 ) {
			$output .= "<tr><td>No queries</td></tr>\n";
		} else {
			foreach( $wpdb->queries as $key => $val ) {
				$query = $val[0];
				$index = $key + 1;
				$this->total_query_time += $val[1];
				
				// Highlight key words
				$query = preg_replace($pattern, '<strong>$1</strong>', $query);
				
				// Highlight strings
				$query = preg_replace("%'([a-zA-Z_]+)?'%", '<span class="string">\'$1\'</span>', $query);
				$output .= '<tr><td class="first number">' . $index . '</td><td><pre>' . $query . '</pre></td><td class="time">' . (number_format($val[1], 4) * 1000) . '</td></tr>';
			}
		}
		
		echo $output;
	}
	
	
	/**
	 * Enqueue Javascript & CSS files
	 * 
	 * @return	void
	 */
	public function init() {
		wp_enqueue_script('jquery-cookie', WP_PLUGIN_URL . '/suspect/js/jquery.cookie.js', array('jquery'));
		wp_enqueue_script('suspect_script', WP_PLUGIN_URL . '/suspect/js/suspect.js', array('jquery'), '0.1');
		wp_enqueue_style('suspect_styles', WP_PLUGIN_URL . '/suspect/css/suspect.css', array(), '0.1', 'screen');
	}
}


$suspect = new Suspect();