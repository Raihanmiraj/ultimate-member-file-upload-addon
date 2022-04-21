<?php

add_action('admin_head', 'my_custom_fonts');

function my_custom_fonts()
{
    echo '<link rel="stylesheet" href="' . plugin_dir_url(__DIR__) . 'include/style.css" type="text/css" media="all" /><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">';
    ?>
<style>
  li {
    margin-bottom:0px!important;
    cursor: pointer;
}
</style>
    <?php
}

function my_admin_menu()
{
    add_menu_page(
        __('File Upload', 'Ulimate Member'),
        __('File Upload', 'Ulimate Member'),
        'manage_options',
        'userpage',
        'my_admin_page_contents',
        'dashicons-schedule',
        3

    );
}

add_action('admin_menu', 'my_admin_menu');

function my_admin_page_contents()
{
  if(isset($_FILES['um_file_upload_admin']) && isset($_GET['user'])){
       
    $membership = $_POST['um_file_upload_admin_member'];
   $path = preg_replace('/wp-content.*$/','',__DIR__);
   require_once($path."wp-load.php");
  $target_dir_array = wp_upload_dir();
     $target_dir = $target_dir_array['basedir'].'/';
      $name_icon = $_POST['um_file_upload_admin'];
     require_once( ABSPATH . 'wp-admin/includes/file.php' );
                    
       $wordpress_upload_dir = wp_upload_dir();
            
       $i = 1; // number of tries when the file with the same name is already exists
                    
       $profilepicture = $_FILES['um_file_upload_admin'];
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
   $tablename=$wpdb->prefix.'um_admin_fileupload_client_member';
   $user_id = $_GET['user'];
   $pageredirect =$_POST['pageredirect'];
   $redirectto = '';
   if($pageredirect=='adminupload'){
    $redirectto = '&adminupload=1';
   }
   if(isset($_POST['folderid'])){
       $data=array(
           'user_id' =>  um_profile_id(),
           'link' =>  $upload_id,
           'f_name'=>$profilepicture['name'],
           'f_type'=>'file',
           'linkwith'=>$_POST['folderid'],
           'membership' =>  $_GET['user']);
         $wpdb->insert( $tablename, $data);
     
         $home_url=site_url().'/wp-admin/admin.php?page=userpage&upload=true&folderid='.$_POST['folderid'].'&user='.$user_id.$redirectto;
         header("Location: $home_url");
         exit();
   }else{
       $data=array(
           'user_id' =>  um_profile_id(),
           'link' =>  $upload_id,
           'f_name'=>$profilepicture['name'],
           'f_type'=>'file',
           'membership' =>   $_GET['user'] );
         $wpdb->insert( $tablename, $data);
            
$home_url=site_url().'/wp-admin/admin.php?page=userpage&upload=true'.'&user='.$user_id.$redirectto;
header("Location: $home_url");
exit();
   }
   


   $home_url=site_url().'/wp-admin/admin.php?page=userpage&upload=true';
   header("Location: $home_url");
    exit();



}
  if(isset($_POST['savefolderadmin']) && isset($_POST['folder_name'])  && isset($_GET['user'])){
     global $wpdb;
    $tablename=$wpdb->prefix.'um_admin_fileupload_client_member';
    if(isset($_POST['folderid']) && $_POST['folderid']!=''){
        $data=array(
            'user_id' =>  um_profile_id(),
            'f_name'=>$_POST['folder_name'],
            'f_type'=>'folder',
            'linkwith'=>$_POST['folderid'],
             'membership' => $_GET['user']
        );
          $wpdb->insert( $tablename, $data);
          $home_url=site_url().'/wp-admin/admin.php?page=userpage&folderid='.$_POST['folderid'].'&user='.$_GET['user'];
          header("Location: $home_url");
          exit();
    }else{
        $data=array(
            'user_id' => um_profile_id(),
            'f_name'=>$_POST['folder_name'],
            'f_type'=>'folder',
              'membership' =>$_GET['user']
        );
          $wpdb->insert( $tablename, $data); 
          $home_url=site_url().'/wp-admin/admin.php?page=userpage&user='.$_GET['user'];
          header("Location: $home_url");
          exit();
    }
    
    
  }
  if(isset($_GET['id']) && isset($_GET['deletefromuser'])){
    $fileid= $_GET['id'];
    $user = $_GET['userid'];
    global $wpdb;
$results = $wpdb->get_results(
    $wpdb->prepare(" SELECT * FROM  {$wpdb->prefix}um_file_upload   LEFT JOIN {$wpdb->prefix}postmeta on {$wpdb->prefix}postmeta.post_id =  {$wpdb->prefix}um_file_upload.link  AND {$wpdb->prefix}postmeta.meta_key='_wp_attached_file'  LEFT JOIN {$wpdb->prefix}posts on {$wpdb->prefix}posts.ID = {$wpdb->prefix}um_file_upload.link WHERE   {$wpdb->prefix}um_file_upload.id=%d"  , $fileid)
);
$root_file = wp_upload_dir(__DIR__)['basedir'];
$filepath = $root_file .'/'.$results[0]->meta_value;
 
if(count($results)>0){
    
    $sql = "DELETE {$wpdb->prefix}um_file_upload , {$wpdb->prefix}posts, {$wpdb->prefix}postmeta FROM    {$wpdb->prefix}um_file_upload LEFT JOIN {$wpdb->prefix}posts on {$wpdb->prefix}um_file_upload.link = {$wpdb->prefix}posts.ID LEFT JOIN {$wpdb->prefix}postmeta on {$wpdb->prefix}um_file_upload.link = {$wpdb->prefix}postmeta.post_id WHERE   {$wpdb->prefix}um_file_upload.id ='$fileid' ";
   $results = $wpdb->get_results(
        $wpdb->prepare($sql)
    );
       $delete = apply_filters( 'wp_delete_file',$filepath );
    if ( ! empty( $delete ) ) {
        @unlink( $filepath );
    }
    $home_url=site_url().'/wp-admin/admin.php?page=userpage&user='. $user.'&message=Successfully Deleted';
    header("Location: $home_url");
     exit();
}else{
    $home_url=site_url().'/wp-admin/admin.php?page=userpage&user='. $user.'&message=Error';
    header("Location: $home_url");
     exit(); 
}
   
 }
    if (isset($_GET['user'])) {
        $user_name = $_GET['user'];

        global $wpdb;
        $userprofileArray = $wpdb->get_results(
            $wpdb->prepare("SELECT  *
        FROM  {$wpdb->prefix}users    LEFT JOIN {$wpdb->prefix}usermeta on {$wpdb->prefix}users.ID = {$wpdb->prefix}usermeta.user_id AND {$wpdb->prefix}usermeta.meta_key  = 'profile_photo'
         WHERE {$wpdb->prefix}users.ID=%d", $user_name)
        );
      



     
        if (isset($_GET['folderid'])) {
            $folderid = $_GET['folderid'];
            $results = $wpdb->get_results(
                $wpdb->prepare(" SELECT * FROM  {$wpdb->prefix}um_file_upload   LEFT JOIN {$wpdb->prefix}postmeta on {$wpdb->prefix}postmeta.post_id =  {$wpdb->prefix}um_file_upload.link  AND {$wpdb->prefix}postmeta.meta_key='_wp_attached_file'  LEFT JOIN {$wpdb->prefix}posts on {$wpdb->prefix}posts.ID = {$wpdb->prefix}um_file_upload.link WHERE {$wpdb->prefix}um_file_upload.user_id=%d  AND {$wpdb->prefix}um_file_upload.linkwith =%d", $user_name, $folderid)
            );
        } else {
            $results = $wpdb->get_results(
                $wpdb->prepare(" SELECT * FROM  {$wpdb->prefix}um_file_upload   LEFT JOIN {$wpdb->prefix}postmeta on {$wpdb->prefix}postmeta.post_id =  {$wpdb->prefix}um_file_upload.link  AND {$wpdb->prefix}postmeta.meta_key='_wp_attached_file'  LEFT JOIN {$wpdb->prefix}posts on {$wpdb->prefix}posts.ID = {$wpdb->prefix}um_file_upload.link WHERE {$wpdb->prefix}um_file_upload.user_id=%d AND linkwith is Null", $user_name)
            );
        }
      

        $output = '';
        $output .= '<main>';
        $createfolder = plugin_dir_url(__DIR__).'icons/createfolder.png';
        $newoutput  = '';
       
   
   
         

  if (isset($_GET['adminupload'])) {
    $newoutput .= '<div onClick="createFolderHandler()" class="create-folder mt-5"><img src="'.$createfolder.'" /><span>Create Folder</span></div><br>';
    $newoutput .= '<form action="" method="post"> <div id="folder-name" class="folder-name hidefolder" ><input type="text" name="folder_name" /><input type="hidden" name="folderid" value="'.$_GET['folderid'].'" /><input type="submit" name="savefolderadmin" value="Save" /></div></form>';
  }else{    
      $newoutput .= ' <div id="clientuploadfilediv" class="file-upload-container">

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
            // $linkfordelete = plugin_dir_url(__DIR__). 'process/file-delete-from-admin.php?id='.$value->id.'&type='.$value->f_type.'&userid='.$user_name;
            $linkfordelete =site_url(). '/wp-admin/admin.php?page=userpage&id='.$value->id.'&type='.$value->f_type.'&userid='.$user_name.'&deletefromuser=1';
            $closeimgurl = plugin_dir_url(__DIR__).'icons/close.png';
            $folder_url = plugin_dir_url(__DIR__).'icons/folder.png';
            // "icons/close.png"
        
            if ($value->f_type == 'folder') {
                $link = site_url().'/wp-admin/admin.php?page=userpage&user=' .$user_name . '&folderid=' .$value->id ;
                // $link = site_url().'/wp-admin/admin.php?page=userpage&user=' .$user_name ;
                $title = substr($value->f_name, 0, 10);
                $newoutput .= '<div class="file-single"><a href="'.$linkfordelete.'" class="file-download-button"><img class="close-image" src="'.$closeimgurl.'" alt=""></a>
         <a href="'.$link.'" class="file-download-button"><img class="file-image-type" src="'.$folder_url.'" alt=""></a>
         <h1 class="file-upload-title">'. $title.'</h1>
         <a href="'.$link.'" class="file-download-button"><img  class="file-download-button-download" src="'.$download_url.'" alt=""></a>
       </div>';
            } elseif (preg_match('/\b(\.jpeg|\.JPEG|\.jpg|\.JPG|\.png|\.PNG|\.gif|\.GIF)\b/', $value->meta_value, $matches, PREG_OFFSET_CAPTURE, 0)) {
                $title = substr($value->post_title, 0, 10);
                $newoutput .= '<div class="file-single"><a href="'.$linkfordelete.'" class="file-download-button"><img class="close-image" src="'.$closeimgurl.'" alt=""></a>
       <a href="'.$link.'" class="file-download-button"><img class="file-image-type" src="'.$img_url.'" alt=""></a>
       <h1 class="file-upload-title">'. $title.'</h1>
       <a href="'.$link.'" class="file-download-button"><img  class="file-download-button-download" src="'.$download_url.'" alt=""></a>
     </div>';
                $output .= '<img src="'.$img_url.'" alt="Girl in a jacket" width="500" height="600">';
            } elseif (preg_match('/\b(\.doc|\.docs|\.docx|\.DOC|\.DOCS|\.DOCX)\b/', $value->meta_value, $matches, PREG_OFFSET_CAPTURE, 0)) {
                $output .= "<a href={$link}><div class=\"folder\"><i class=\"material-icons\">description";
                $newoutput .= '<div class="file-single"><a href="'.$linkfordelete.'" class="file-download-button"><img class="close-image" src="'.$closeimgurl.'" alt=""></a>
      <a href="'.$link.'" class="file-download-button"><img class="file-image-type" src="'.$doc_url.'" alt=""></a>
       <h1 class="file-upload-title">'. $title.'</h1>
       <a href="'.$link.'" class="file-download-button"><img  class="file-download-button-download" src="'.$download_url.'" alt=""></a>
     </div>';
            } elseif (preg_match('/\b(\.pdf|\.PDF)\b/', $value->meta_value, $matches, PREG_OFFSET_CAPTURE, 0)) {
                $output .= "<a href={$link}><div class=\"folder\"><i class=\"material-icons\">description";
                $newoutput .= '<div class="file-single"><a href="'.$linkfordelete.'" class="file-download-button"><img class="close-image" src="'.$closeimgurl.'" alt=""></a>
      <a href="'.$link.'" class="file-download-button"><img class="file-image-type" src="'.$pdf_url.'" alt=""></a>
       <h1 class="file-upload-title">'. $title.'</h1>
       <a href="'.$link.'" class="file-download-button"><img  class="file-download-button-download" src="'.$download_url.'" alt=""></a>
     </div>';
            } else {
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
    }
    $output .= '</main>';

        $image_name = isset($userprofileArray[0]->meta_value) ? wp_upload_dir()['baseurl'] . '/ultimatemember' . '/' . $user_name . '/' . $userprofileArray[0]->meta_value : site_url() . '/wp-content/plugins/ultimate-member/assets/img/default_avatar.jpg';
        $user_email = $userprofileArray[0]->user_email;
        $display_name = $userprofileArray[0]->display_name;

        ?>
	<div class="container rounded bg-white   mb-5">
	 <div class="row" style="height: 100vh;">
        <div class="col-md-3 border-right" style="border-right: 1px solid rgb(226, 225, 225);">
				<div class="d-flex flex-column align-items-center text-center p-3 py-5"><img class="rounded-circle  " width="150px" src="<?php echo $image_name; ?>"><span class="font-weight-bold"><?php echo $display_name; ?></span><span class="text-black-50"><?php echo $user_email; ?></span><span> </span></div>
        <ul class="list-group">
          <?php
          $adminuploadactive='';
          $clientuploadactive='';
      
if($_GET['adminupload']){
  $adminuploadactive='active';

}else{
  $clientuploadactive='active';
}
$clientuploadfileurl =   site_url(). '/wp-admin/admin.php?page=userpage&user='.$_GET['user'];
$adminuploadfileurl =   site_url(). '/wp-admin/admin.php?page=userpage&adminupload=1&user='.$_GET['user'];
          ?>
            
  <a class="link-for-file" href="<?php echo $clientuploadfileurl; ?>"><li id="clientuploadfile" class="list-group-item  <?php echo $clientuploadactive; ?>"  aria-current="true">Client Uploaded File</li></a>
  <a class="link-for-file" href="<?php echo $adminuploadfileurl; ?>"><li id="adminuploadfile" class="list-group-item <?php echo $adminuploadactive; ?>"  >Admin Uploaded File</li></a>
 
</ul>
 
        <form class="form-for-uploadfile" action="" method="post" enctype="multipart/form-data" >
        <h1 class="h1">Add Image </h1>
  <?php if(isset($_GET['upload'])){

      ?>
      <div class="alert alert-success alert-white rounded">
   <strong>Success!</strong> File Has Uploaded
</div>
      <?php
  }   ?>
        <fieldset class="fieldset">  
          <label class="label-form" for="image">Image</label>
          <input  class="input" type="file" id="image" name="um_file_upload_admin"/>
          <?php
           $pageredirect = '';

           if(isset($_GET['adminupload'])){
     $pageredirect = 'adminupload';
     echo '<input type="hidden" name="pageredirect" value="'.$pageredirect.'" />';
           }
if(isset($_GET['folderid'])){
echo   '<input type="hidden" name="folderid" value="'.$_GET['folderid'].'"/>';
}

?>

 
          </fieldset>
       
        <button  class="button-upload-file"  type="submit">Upload File</button>
        
       </form>
			</div>

			<div class="col-md-9">  

		<?php	 
      echo $newoutput; ?>
    
    <?php
    if(isset($_GET['adminupload'])){
      do_shortcode('[file-upload-for-specific-user-show]'); 

    }
    ?>
			</div>
		</div>	</div>
	<?php
} else {
      include "file-upload-by-admin-for-member.php";  // echo plugin_dir_path(dirname(dirname(dirname(__FILE__))));
        // echo "hey";

    }

}