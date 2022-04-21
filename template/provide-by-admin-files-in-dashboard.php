<?php

function ProvideByAdminFileInDashBoard()
{


     global $wpdb;
    if(isset($_GET['folderid'])){
      $folderid = $_GET['folderid'];
      $results = $wpdb->get_results(
        $wpdb->prepare(" SELECT * FROM  {$wpdb->prefix}um_admin_fileupload_client_member   LEFT JOIN {$wpdb->prefix}postmeta on {$wpdb->prefix}postmeta.post_id =  {$wpdb->prefix}um_admin_fileupload_client_member.link  AND {$wpdb->prefix}postmeta.meta_key='_wp_attached_file'  LEFT JOIN {$wpdb->prefix}posts on {$wpdb->prefix}posts.ID = {$wpdb->prefix}um_admin_fileupload_client_member.link WHERE  {$wpdb->prefix}um_admin_fileupload_client_member.linkwith =%d  AND {$wpdb->prefix}um_admin_fileupload_client_member.membership > 0 ",  $folderid )
    );
    }else{
      $results = $wpdb->get_results(
        $wpdb->prepare(" SELECT * FROM  {$wpdb->prefix}um_admin_fileupload_client_member   LEFT JOIN {$wpdb->prefix}postmeta on {$wpdb->prefix}postmeta.post_id =  {$wpdb->prefix}um_admin_fileupload_client_member.link  AND {$wpdb->prefix}postmeta.meta_key='_wp_attached_file'  LEFT JOIN {$wpdb->prefix}posts on {$wpdb->prefix}posts.ID = {$wpdb->prefix}um_admin_fileupload_client_member.link WHERE   linkwith is Null   " )
    );
    }
  
     
$actionurl = site_url().'/wp-admin/admin.php?page=userpage';
 
    $createfolder = plugin_dir_url(__DIR__).'icons/createfolder.png';
    $newoutput  = '';
    $newoutput .= '<div onClick="createFolderHandler()" class="create-folder"><img src="'.$createfolder.'" /><span>Create Folder</span></div><br>';
    $newoutput .= '<div id="folder-name" class="folder-name hidefolder" ><form method="post" action="'.$actionurl.'"><input type="text" name="createfolder" /><input type="submit" name="savefiolder" value="Save" />';
    $newoutput .= '<select  name="um_file_upload_admin_member" class="select"  id="job" name="user_job">'; 
    

          
  $um_roles = get_option( 'um_roles', array() );
 for($i=0;$i<count($um_roles);$i++){
    $newoutput .= "<option  class=\"option\"  value=\"um_{$um_roles[$i]}\">{$um_roles[$i]}</option>";
 }
 
 
       $newoutput .= '</select>  </form></div>'; 
   
 
  
    if(isset($_GET['message'])){
      $message =$_GET['message'];
      $newoutput .= '<p class="um-notice success"><i class="um-icon-ios-close-empty" onclick="jQuery(this).parent().fadeOut();"></i>' . $message . '</p>';
    }

    $newoutput .= '<div class="file-upload-container">
  
   <div class="file-upload-content">';
    foreach ($results as $value) {
      
    if( is_numeric($value->membership)){
      continue;
    }
         $title = substr($value->post_title, 0, 10);
     $meta_value =  str_replace(' ', '%20', $value->meta_value);
        $link = wp_upload_dir()['baseurl'] . '/' . $meta_value;
         $img_url = plugin_dir_url(__DIR__).'icons/image.png';
       $doc_url = plugin_dir_url(__DIR__).'icons/doc.png';
        $pdf_url = plugin_dir_url(__DIR__).'icons/pdf.png';
       $download_url = plugin_dir_url(__DIR__).'icons/download.png';
        $text_url = plugin_dir_url(__DIR__).'icons/text.png';
        // $linkfordelete = plugin_dir_url(__DIR__). 'process/file-delete-from-adminprovided.php?id='.$value->id.'&type='.$value->f_type;
      $linkfordelete =site_url(). '/wp-admin/admin.php?page=userpage&id='.$value->id.'&type='.$value->f_type.'&deleteprovide=1';

         $closeimgurl = plugin_dir_url(__DIR__).'icons/close.png';
         $folder_url = plugin_dir_url(__DIR__).'icons/folder.png';
        // "icons/close.png"
        
        if($value->f_type == 'folder' ){
          $link = site_url().'/wp-admin/admin.php?page=userpage&folderid=' .$value->id ;
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
  

}
add_shortcode('provide-by-admin-dashboard', 'ProvideByAdminFileInDashBoard');