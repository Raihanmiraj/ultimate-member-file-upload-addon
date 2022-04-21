<?php
/**
 * Plugin Name:       File Upload UM
 * Plugin URI:        https://raihanmiraj.com/
 * Description:       Custom FIle Upload System Integrated With Ultimate Member Plugin
 * Version:           1.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Raihan Miraj
 * Author URI:        https://raihanmiraj.com/
 * License:           GPL v2 or later
 */

global $jal_db_version;
$jal_db_version = '1.0';

function jal_install()
{
    global $wpdb;
    global $jal_db_version;
    $table_name = $wpdb->prefix . 'um_file_upload';
    $table_name2 = $wpdb->prefix . 'um_admin_fileupload_client_member';
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $table_name (
		`id` int AUTO_INCREMENT ,
		`user_id` int(11) NOT NULL,
    `f_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
		`link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
		`f_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `linkwith` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
		`time` timestamp NULL DEFAULT current_timestamp(),
		 PRIMARY KEY (id)
	  ) $charset_collate;";

      $sql2 = "CREATE TABLE $table_name2 (
        `id` int(11) AUTO_INCREMENT,
        `user_id` int(11) DEFAULT NULL,
        `link` int(11) DEFAULT NULL,
        `f_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `f_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `linkwith` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `membership` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `time` timestamp NULL DEFAULT current_timestamp(),
		PRIMARY KEY (id)
  ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
     dbDelta($sql2);
       include 'unused/file-replaced-process.php';
    add_option('jal_db_version', $jal_db_version);
}



register_activation_hook(__FILE__, 'jal_install');
// {$wpdb->prefix}enqueue_style( 'style', "https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" );
wp_register_script('my_script_jquery', plugin_dir_url(__FILE__) . 'include/jquery.min.js', array('jquery'),'');
 
wp_enqueue_script('my_script_jquery');

// wp_register_script('my_script', plugin_dir_url(__FILE__) . 'include/script.js', array(''),'');
 
// wp_enqueue_script('my_script');


wp_register_style('um_fileupload', plugin_dir_url(__FILE__) . 'include/style.css', array(), '');
 



include_once "template/userpagedashboard.php";
include_once "template/file-upload-form.php";
include_once "template/file-show-of-user.php";
include_once "template/show-upload-file.php";
include_once "template/file-upload-for-specific-user-show.php";
include_once "template/file-upload-tab.php";
include_once "template/custom-dashboard-tab.php";

include_once "template/provide-by-admin-file.php";
include_once "template/create-cutom-tab.php";
include_once "template/provide-by-admin-files-in-dashboard.php";

 