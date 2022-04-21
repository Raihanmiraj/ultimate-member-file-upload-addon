<?php 
  include_once dirname(dirname(dirname(dirname(__DIR__)))).'/wp-config.php';
  if(isset($_GET['id']) && isset($_GET['type']) && $_GET['type']=='folder'){
    $fileid= $_GET['id'];
    global $wpdb;
$results = $wpdb->get_results(
    $wpdb->prepare(" SELECT * FROM  {$wpdb->prefix}um_file_upload   WHERE {$wpdb->prefix}um_file_upload.user_id=%d AND  {$wpdb->prefix}um_file_upload.id=%d", um_profile_id() , $fileid)
);
$root_file = wp_upload_dir(__DIR__)['basedir'];

 
if(count($results)>0){
    $user_id = um_profile_id();
    $sql = "DELETE {$wpdb->prefix}um_file_upload , {$wpdb->prefix}posts, {$wpdb->prefix}postmeta FROM    {$wpdb->prefix}um_file_upload LEFT JOIN {$wpdb->prefix}posts on {$wpdb->prefix}um_file_upload.link = {$wpdb->prefix}posts.ID LEFT JOIN {$wpdb->prefix}postmeta on {$wpdb->prefix}um_file_upload.link = {$wpdb->prefix}postmeta.post_id WHERE ( {$wpdb->prefix}um_file_upload.user_id = '$user_id' AND {$wpdb->prefix}um_file_upload.id ='$fileid') OR  {$wpdb->prefix}um_file_upload.linkwith='$fileid'";
   $results = $wpdb->get_results(
        $wpdb->prepare($sql)
    );
    $filepath = $root_file .'/'.$results[0]->meta_value;
       $delete = apply_filters( 'wp_delete_file',$filepath );
    if ( ! empty( $delete ) ) {
        @unlink( $filepath );
    }
    $home_url=site_url().'/account/fileupload?message=Successfully Deleted';
    header("Location: $home_url");
     exit();
}else{
    $home_url=site_url().'/account/fileupload?message=Error';
    header("Location: $home_url");
     exit(); 
}

  }else  if(isset($_GET['id'])  && isset($_GET['type']) && $_GET['type']=='file'){
    $fileid= $_GET['id'];
    global $wpdb;
$results = $wpdb->get_results(
    $wpdb->prepare(" SELECT * FROM  {$wpdb->prefix}um_file_upload   INNER JOIN {$wpdb->prefix}postmeta on {$wpdb->prefix}postmeta.post_id =  {$wpdb->prefix}um_file_upload.link  AND {$wpdb->prefix}postmeta.meta_key='_wp_attached_file'  INNER JOIN {$wpdb->prefix}posts on {$wpdb->prefix}posts.ID = {$wpdb->prefix}um_file_upload.link WHERE {$wpdb->prefix}um_file_upload.user_id=%d AND  {$wpdb->prefix}um_file_upload.id=%d", um_profile_id() , $fileid)
);
$root_file = wp_upload_dir(__DIR__)['basedir'];
$filepath = $root_file .'/'.$results[0]->meta_value;
 
if(count($results)>0){
    $user_id = um_profile_id();
    $sql = "DELETE {$wpdb->prefix}um_file_upload , {$wpdb->prefix}posts, {$wpdb->prefix}postmeta FROM    {$wpdb->prefix}um_file_upload INNER JOIN {$wpdb->prefix}posts on {$wpdb->prefix}um_file_upload.link = {$wpdb->prefix}posts.ID INNER JOIN {$wpdb->prefix}postmeta on {$wpdb->prefix}um_file_upload.link = {$wpdb->prefix}postmeta.post_id WHERE {$wpdb->prefix}um_file_upload.user_id = '$user_id' AND {$wpdb->prefix}um_file_upload.id ='$fileid' ";
   $results = $wpdb->get_results(
        $wpdb->prepare($sql)
    );
       $delete = apply_filters( 'wp_delete_file',$filepath );
    if ( ! empty( $delete ) ) {
        @unlink( $filepath );
    }
    $home_url=site_url().'/account/fileupload?message=Successfully Deleted';
    header("Location: $home_url");
     exit();
}else{
    $home_url=site_url().'/account/fileupload?message=Error';
    header("Location: $home_url");
     exit(); 
}
   
 } 
 
 

 
// var_dump($results) ;

 
  
        // global $wpdb;
        // $tablename=$wpdb->prefix.'um_admin_fileupload_client_member';
   
 
   


   
 
  


 

?>