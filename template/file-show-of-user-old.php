<?php

function FileShowOfUser()
{
 
    $root_file = wp_upload_dir(__DIR__)['basedir'];
 
    // var_dump( um_user('role')) ;
    global $wpdb;
    $results = $wpdb->get_results(
        $wpdb->prepare(" SELECT * FROM  {$wpdb->prefix}um_file_upload   INNER JOIN {$wpdb->prefix}postmeta on {$wpdb->prefix}postmeta.post_id =  {$wpdb->prefix}um_file_upload.link  AND {$wpdb->prefix}postmeta.meta_key='_wp_attached_file'  INNER JOIN {$wpdb->prefix}posts on {$wpdb->prefix}posts.ID = {$wpdb->prefix}um_file_upload.link WHERE {$wpdb->prefix}um_file_upload.user_id=%d", um_profile_id())
    );

    $output = '';
    $output .= '<main>';
    $createfolder = plugin_dir_url(__DIR__).'icons/createfolder.png';
    $newoutput  = '';
    $newoutput .= '<div onClick="createFolderHandler()" class="create-folder"><img src="'.$createfolder.'" /><span>Create Folder</span></div><br><br>';
    $newoutput .= '<div class="folder-name" ><input type="text" name="folder_name" /><input type="submit" name="savefiolder" value="Save" /></div>';  
    if(isset($_GET['message'])){
      $message =$_GET['message'];
      $newoutput = '<p class="um-notice success"><i class="um-icon-ios-close-empty" onclick="jQuery(this).parent().fadeOut();"></i>' . $message . '</p>';
    }

    $newoutput .= '<div class="file-upload-container">
  
   <div class="file-upload-content">';
    foreach ($results as $value) {
      
    
         $title = substr($value->post_title, 0, 10);
     $meta_value =  str_replace(' ', '%20', $value->meta_value);
        $link = wp_upload_dir()['baseurl'] . '/' . $meta_value;
        $output .= "<a href={$link}><div class=\"folder\"><i class=\"material-icons\">description";
      $img_url = plugin_dir_url(__DIR__).'icons/image.png';
       $doc_url = plugin_dir_url(__DIR__).'icons/doc.png';
        $pdf_url = plugin_dir_url(__DIR__).'icons/pdf.png';
       $download_url = plugin_dir_url(__DIR__).'icons/download.png';
        $text_url = plugin_dir_url(__DIR__).'icons/text.png';
        $linkfordelete = plugin_dir_url(__DIR__). 'process/file-delete-from-user.php?id='.$value->id;
         $closeimgurl = plugin_dir_url(__DIR__).'icons/close.png';
        // "icons/close.png"
if(preg_match('/\b(\.jpeg|\.JPEG|\.jpg|\.JPG|\.png|\.PNG|\.gif|\.GIF)\b/', $value->meta_value , $matches, PREG_OFFSET_CAPTURE, 0)){

        $title = substr($value->post_title, 0, 10);
        $newoutput .= '<div class="file-single"><a href="'.$linkfordelete.'" class="file-download-button"><img class="close-image" src="'.$closeimgurl.'" alt=""></a>
       <a href="'.$link.'" class="file-download-button"><img class="file-image-type" src="'.$img_url.'" alt=""></a>
       <h1 class="file-upload-title">'. $title.'</h1>
       <a href="'.$link.'" class="file-download-button"><img  class="file-download-button-download" src="'.$download_url.'" alt=""></a>
     </div>';
     $output .= '<img src="'.$img_url.'" alt="Girl in a jacket" width="500" height="600">';
}else if(preg_match('/\b(\.doc|\.docs|\.docx|\.DOC|\.DOCS|\.DOCX)\b/', $value->meta_value, $matches, PREG_OFFSET_CAPTURE, 0)){
     $output .= "<a href={$link}><div class=\"folder\"><i class=\"material-icons\">description";
      $newoutput .= '<div class="file-single"><a href="'.$linkfordelete.'" class="file-download-button"><img class="close-image" src="'.$closeimgurl.'" alt=""></a>
      <a href="'.$link.'" class="file-download-button"><img class="file-image-type" src="'.$doc_url.'" alt=""></a>
       <h1 class="file-upload-title">'. $title.'</h1>
       <a href="'.$link.'" class="file-download-button"><img  class="file-download-button-download" src="'.$download_url.'" alt=""></a>
     </div>';
}else if(preg_match('/\b(\.pdf|\.PDF)\b/', $value->meta_value, $matches, PREG_OFFSET_CAPTURE, 0)){
     $output .= "<a href={$link}><div class=\"folder\"><i class=\"material-icons\">description";
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

      
        $output .= "<p class=\"cooltip\">0 folders / 0 files</p>
    </i>
    <h1>{$title}</h1>
   
  </div></a>";
    }
    $newoutput .= ' </div>
  
  </div>';
    $output .= '</main>';
    echo $newoutput;
   
    //  {$wpdb->prefix}upload_dir()
    ?>


	 <?php

}
add_shortcode('file_show_user', 'FileShowOfUser');