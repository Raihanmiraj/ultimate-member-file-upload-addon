<?php

function ShowUploadedFile()
{
    global $wp;
    $queryurl = add_query_arg($wp->query_vars, home_url());
    $url_components = parse_url($queryurl);
    parse_str($url_components['query'], $params);
    $user_name = $params['um_user'];
    global $wpdb;
    $results = $wpdb->get_results(
        $wpdb->prepare("SELECT * FROM  {$wpdb->prefix}um_file_upload   INNER JOIN {$wpdb->prefix}postmeta on {$wpdb->prefix}postmeta.post_id =  {$wpdb->prefix}um_file_upload.link  AND {$wpdb->prefix}postmeta.meta_key='_wp_attached_file'  INNER JOIN {$wpdb->prefix}posts on {$wpdb->prefix}posts.ID = {$wpdb->prefix}um_file_upload.link  INNER JOIN {$wpdb->prefix}users on {$wpdb->prefix}users.ID = {$wpdb->prefix}um_file_upload.user_id WHERE {$wpdb->prefix}users.user_login=%s", $user_name)
    );
    $output = '';
    $output .= '<main>';
    $newoutput = '';
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
        $linkfordelete = '';
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
    // echo $newoutput;
}
add_shortcode('showuploadfile', 'ShowUploadedFile');