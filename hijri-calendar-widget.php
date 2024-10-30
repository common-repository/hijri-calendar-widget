<?php
/**
 * @link              http://bftech.info
 * @since             1.0.0
 * @package           Hijri_Calendar_Widget
 *
 * @wordpress-plugin
 * Plugin Name:       Hijri Calendar Widget
 * Plugin URI:        https://github.com/bf-tech/wp-plugin-hijri-calendar
 * Description:       Wordpress plugin for historically accurate Hijri conversions.
 * Version:           1.0.0
 * Author:            Fethi Bendimerad
 * Author URI:        http://bftech.info
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       hijri-calendar-widget
 * Domain Path:       /languages
 */
 
 /*
  *	Include JavaScript
  */
 function add_scripts(){
//  wp_enqueue_script('jstz', 'https://cdnjs.cloudflare.com/ajax/libs/jstimezonedetect/1.0.4/jstz.min.js', array(),'1.0.4', true);
    wp_enqueue_script('calendar-scripts', plugins_url().'/hijri-calendar-widget/js/script.js', array('jquery'),'1.0.0', true);
  }
  add_action('wp_enqueue_scripts','add_scripts');
  
  /*
   *	Include Class
   */
  include('class.hijri-calendar-widget.php');
   
  /*
   *	Register Widget
   */
  function register_hijri_calendar_widget(){
	     register_widget('Hijri_Calendar_Widget');
  }
  add_action('widgets_init','register_hijri_calendar_widget');