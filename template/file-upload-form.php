<?php
function form_include()
{

    $content = ''; /* creates a string variable */
    $content .= '<form method="post" action="'.plugin_dir_url(__DIR__).'process/index.php" enctype="multipart/form-data">';  
 
    $content .= '<input type="file" name="um_file_upload" /><br /><br />';
    $content .= '<input type="submit" name="file_upload_submit" value="Upload File" /><br /><br />';
    $content .= '</form>'; 
    return $content;
}
add_shortcode('upload_form', 'form_include');