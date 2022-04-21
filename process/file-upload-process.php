
<?php
 /*
function um_submit_account_errors_hook2($args)
{
    global $current_user;

    if (!isset($args['_um_account']) && !isset($args['_um_account_tab'])) {
        return;
    }

    $tab = sanitize_key($args['_um_account_tab']);

    if (!wp_verify_nonce($args['um_account_nonce_' . $tab], 'um_update_account_' . $tab)) {
        UM()->form()->add_error('um_account_security', __('Are you hacking? Please try again!', 'ultimate-member'));
    }

    switch ($tab) {
        case 'fileupload':{
                // if (isset($args['um_file_upload'])) {
                //     $args['um_file_upload'] = sanitize_text_field($args['um_file_upload']);
                // }
                	 
                 if(isset($_FILES['um_file_upload'])){
	$path = preg_replace('/wp-content.*$/','',__DIR__);
 
    require_once($path."wp-load.php");
   $target_dir_array = wp_upload_dir();
      $target_dir = $target_dir_array['basedir'].'/';
     

        //taking image in $name_icon
        $name_icon = $_POST['um_file_upload'];
    
        // WordPress environment
		  //require_once( ABSPATH . 'wp-admin/includes/file.php' );
                     
       $wordpress_upload_dir = wp_upload_dir();
             
        $i = 1; // number of tries when the file with the same name is already exists
                     
        $profilepicture = $_FILES['um_file_upload'];
        $new_file_path = $wordpress_upload_dir['path'] . '/' . $profilepicture['name'];
        $new_file_mime = mime_content_type($profilepicture['tmp_name']);
                     
        if (empty($profilepicture)) {
            die();
        }
                     
        if ($profilepicture['error']) {
            die($profilepicture['error']);
        }
                     
        if ($profilepicture['size'] > wp_max_upload_size()) {
            die('It is too large than expected.');
        }
                     
        if (!in_array($new_file_mime, get_allowed_mime_types())) {
            die('WordPress doesn\'t allow this type of uploads.');
        }
                     
        while (file_exists($new_file_path)) {
            $i++;
            $new_file_path = $wordpress_upload_dir['path'] . '/' . $i . '_' . $profilepicture['name'];
        }
         
        // looks like everything is OK
        // looks like everything is OK
if( move_uploaded_file( $profilepicture['tmp_name'], $new_file_path ) ) {
	

	$upload_id = wp_insert_attachment( array(
		'guid'           => $new_file_path, 
		'post_mime_type' => $new_file_mime,
		'post_title'     => preg_replace( '/\.[^.]+$/', '', $profilepicture['name'] ),
		'post_content'   => '',
		'post_status'    => 'inherit'
	), $new_file_path );

	// wp_generate_attachment_metadata() won't work if you do not include this file
	require_once( ABSPATH . 'wp-admin/includes/image.php' );

	// Generate and save the attachment metas into the database
	wp_update_attachment_metadata( $upload_id, wp_generate_attachment_metadata( $upload_id, $new_file_path ) );

	// Show the uploaded file in browser
	wp_redirect( $wordpress_upload_dir['url'] . '/' . basename( $new_file_path ) );

}
  
    global $wpdb;
    $tablename=$wpdb->prefix.'um_file_upload';

    $data=array(
        'user_id' =>  um_profile_id(),
        'link' =>  $upload_id );
       $wpdb->insert( $tablename, $data); 
    }
                UM()->form()->add_error('single_user_fileupload', __('You must enter your password', 'ultimate-member'));
                break;
            }
    }
}

add_action('um_submit_account_errors_hook', 'um_submit_account_errors_hook2', 1, 1);*/
?>