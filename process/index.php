<?php 
 
               if(isset($_POST['file_upload_submit'])){
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
// 	wp_redirect( $wordpress_upload_dir['url'] . '/' . basename( $new_file_path ) );

}
  
    global $wpdb;
    $tablename=$wpdb->prefix.'um_file_upload';

    $data=array(
        'user_id' =>  um_profile_id(),
        'link' =>  $upload_id );
       $wpdb->insert( $tablename, $data); 
     wp_redirect(site_url().'/account/fileupload/?');
    }
 


 
 

?>