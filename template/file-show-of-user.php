<?php

function FileShowOfUser()
{
  if(isset($_GET['id'])  && isset($_GET['delete'])  && isset($_GET['type']) && $_GET['type']=='folder'){
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
 
  }else  if(isset($_GET['id']) && isset($_GET['delete'])  && isset($_GET['type']) && $_GET['type']=='file'){
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
 
    $root_file = wp_upload_dir(__DIR__)['basedir'];
 
    // var_dump( um_user('role')) ;
    global $wpdb;
    if(isset($_GET['folderid'])){
      $folderid = $_GET['folderid'];
      $results = $wpdb->get_results(
        $wpdb->prepare(" SELECT * FROM  {$wpdb->prefix}um_file_upload   LEFT JOIN {$wpdb->prefix}postmeta on {$wpdb->prefix}postmeta.post_id =  {$wpdb->prefix}um_file_upload.link  AND {$wpdb->prefix}postmeta.meta_key='_wp_attached_file'  LEFT JOIN {$wpdb->prefix}posts on {$wpdb->prefix}posts.ID = {$wpdb->prefix}um_file_upload.link WHERE {$wpdb->prefix}um_file_upload.user_id=%d  AND {$wpdb->prefix}um_file_upload.linkwith =%d", um_profile_id(), $folderid )
    );
    }else{
      $results = $wpdb->get_results(
        $wpdb->prepare(" SELECT * FROM  {$wpdb->prefix}um_file_upload   LEFT JOIN {$wpdb->prefix}postmeta on {$wpdb->prefix}postmeta.post_id =  {$wpdb->prefix}um_file_upload.link  AND {$wpdb->prefix}postmeta.meta_key='_wp_attached_file'  LEFT JOIN {$wpdb->prefix}posts on {$wpdb->prefix}posts.ID = {$wpdb->prefix}um_file_upload.link WHERE {$wpdb->prefix}um_file_upload.user_id=%d AND linkwith is Null", um_profile_id())
    );
    }
  
   

 
    $createfolder = plugin_dir_url(__DIR__).'icons/createfolder.png';
    $fileiconlink = plugin_dir_url(__DIR__).'icons/file.png';
    $newoutput  = '';
    $newoutput .= '<div class="file-upload-option"><div  class="create-folder"><img src="'.$fileiconlink.'" /><span>File Upload</span></div><div onClick="createFolderHandler()" class="create-folder"><img src="'.$createfolder.'" /><span>Create Folder</span></div></div><br>';
    $newoutput .= '<div id="folder-name" class="folder-name hidefolder" ><input type="text" name="folder_name" /><input type="submit" name="savefiolder" value="Save" /></div>';  
    if (isset($_GET['folderid'])) {
      $folderid = $_GET['folderid'];
      $newoutput .= '<input type="hidden" value="'.$folderid.'" name="folderid"   />';
    }
  
    if(isset($_GET['message'])){
      $message =$_GET['message'];
      $newoutput .= '<p class="um-notice success"><i class="um-icon-ios-close-empty" onclick="jQuery(this).parent().fadeOut();"></i>' . $message . '</p>';
    }

    $newoutput .= '<div class="file-upload-container">
  
   <div class="file-upload-content">';
    foreach ($results as $value) {
      
    
         $title = substr($value->post_title, 0, 10);
     $meta_value =  str_replace(' ', '%20', $value->meta_value);
        $link = wp_upload_dir()['baseurl'] . '/' . $meta_value;
         $img_url = plugin_dir_url(__DIR__).'icons/image.png';
       $doc_url = plugin_dir_url(__DIR__).'icons/doc.png';
        $pdf_url = plugin_dir_url(__DIR__).'icons/pdf.png';
       $download_url = plugin_dir_url(__DIR__).'icons/download.png';
        $text_url = plugin_dir_url(__DIR__).'icons/text.png';
        $linkfordelete = site_url(). '/account/fileupload?delete=1&id='.$value->id.'&type='.$value->f_type;
         $closeimgurl = plugin_dir_url(__DIR__).'icons/close.png';
         $folder_url = plugin_dir_url(__DIR__).'icons/folder.png';
        // "icons/close.png"
        
        if($value->f_type == 'folder' ){
          $link = site_url().'/account/fileupload/?folderid=' .$value->id ;
          $title = substr($value->f_name, 0, 10);
          $newoutput .= '<div class="file-single"><a href="'.$linkfordelete.'" class="file-download-button"><img class="close-image" src="'.$closeimgurl.'" alt=""></a>
         <a href="'.$link.'" class="file-download-button"><img class="file-image-type" src="'.$folder_url.'" alt=""></a>
         <h1 class="file-upload-title">'. $title.'</h1>
         <a href="'.$link.'" class="file-download-button"><img  class="file-download-button-download" src="'.$download_url.'" alt=""></a>
       </div>';
        }else if(preg_match('/\b(\.jpeg|\.JPEG|\.jpg|\.JPG|\.png|\.PNG|\.gif|\.GIF)\b/', $value->meta_value , $matches, PREG_OFFSET_CAPTURE, 0)){

        $title = substr($value->post_title, 0, 10);
        $newoutput .= '<div class="file-single"><a href="'.$linkfordelete.'" class="file-download-button"><img class="close-image" src="'.$closeimgurl.'" alt=""></a>
       <a href="'.$link.'" class="file-download-button"><img class="file-image-type" src="'.$img_url.'" alt=""></a>
       <h1 class="file-upload-title">'. $title.'</h1>
       <a href="'.$link.'" class="file-download-button"><img  class="file-download-button-download" src="'.$download_url.'" alt=""></a>
     </div>';
    }else if(preg_match('/\b(\.doc|\.docs|\.docx|\.DOC|\.DOCS|\.DOCX)\b/', $value->meta_value, $matches, PREG_OFFSET_CAPTURE, 0)){
   $newoutput .= '<div class="file-single"><a href="'.$linkfordelete.'" class="file-download-button"><img class="close-image" src="'.$closeimgurl.'" alt=""></a>
      <a href="'.$link.'" class="file-download-button"><img class="file-image-type" src="'.$doc_url.'" alt=""></a>
       <h1 class="file-upload-title">'. $title.'</h1>
       <a href="'.$link.'" class="file-download-button"><img  class="file-download-button-download" src="'.$download_url.'" alt=""></a>
     </div>';
}else if(preg_match('/\b(\.pdf|\.PDF)\b/', $value->meta_value, $matches, PREG_OFFSET_CAPTURE, 0)){
$newoutput .= '<div class="file-single"><a href="'.$linkfordelete.'" class="file-download-button"><img class="close-image" src="'.$closeimgurl.'" alt=""></a>
      <a href="'.$link.'" class="file-download-button"><img class="file-image-type" src="'.$pdf_url.'" alt=""></a>
       <h1 class="file-upload-title">'. $title.'</h1>
       <a href="'.$link.'" class="file-download-button"><img  class="file-download-button-download" src="'.$download_url.'" alt=""></a>
     </div>';
}else {
      $newoutput .= '<div class="file-single"><a href="'.$linkfordelete.'" class="file-download-button"><img class="close-image" src="'.$closeimgurl.'" alt=""></a>
      <a href="'.$link.'" class="file-download-button"><img class="file-image-type" src="'.$text_url.'" alt=""></a>
       <h1 class="file-upload-title">'. $title.'</h1>
       <a href="'.$link.'" class="file-download-button"><img  class="file-download-button-download" src="'.$download_url.'" alt=""></a>
     </div>';
}
}
$newoutput .= ' </div> </div>';
    echo $newoutput;
   
    //  {$wpdb->prefix}upload_dir()
    ?>

 
	 <?php

}
add_shortcode('file_show_user', 'FileShowOfUser');