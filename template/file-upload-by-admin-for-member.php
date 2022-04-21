<?php
  if(isset($_GET['id']) && isset($_GET['deleteprovide'])){
    $fileid= $_GET['id'];
    $userid = $_GET['userid'];
    global $wpdb;
$results = $wpdb->get_results(
    $wpdb->prepare(" SELECT * FROM  {$wpdb->prefix}um_admin_fileupload_client_member   LEFT JOIN {$wpdb->prefix}postmeta on {$wpdb->prefix}postmeta.post_id =  {$wpdb->prefix}um_admin_fileupload_client_member.link  AND {$wpdb->prefix}postmeta.meta_key='_wp_attached_file'  LEFT JOIN {$wpdb->prefix}posts on {$wpdb->prefix}posts.ID = {$wpdb->prefix}um_admin_fileupload_client_member.link WHERE   {$wpdb->prefix}um_admin_fileupload_client_member.id=%d"  , $fileid)
);
$root_file = wp_upload_dir(__DIR__)['basedir'];
$filepath = $root_file .'/'.$results[0]->meta_value;
 
if(count($results)>0){
    $sql = "DELETE {$wpdb->prefix}um_admin_fileupload_client_member , {$wpdb->prefix}posts, {$wpdb->prefix}postmeta FROM    {$wpdb->prefix}um_admin_fileupload_client_member LEFT JOIN {$wpdb->prefix}posts on {$wpdb->prefix}um_admin_fileupload_client_member.link = {$wpdb->prefix}posts.ID LEFT JOIN {$wpdb->prefix}postmeta on {$wpdb->prefix}um_admin_fileupload_client_member.link = {$wpdb->prefix}postmeta.post_id WHERE   {$wpdb->prefix}um_admin_fileupload_client_member.id ='$fileid' ";
   $results = $wpdb->get_results(
        $wpdb->prepare($sql)
    );
       $delete = apply_filters( 'wp_delete_file',$filepath );
    if ( ! empty( $delete ) ) {
        @unlink( $filepath );
    }
    if(isset($_GET['folderid'])){
        $home_url=site_url().'/wp-admin/admin.php?page=userpage&folderid='.$_GET['folderid'].'&message=Successfully Deleted';
    }else{
        $home_url=site_url().'/wp-admin/admin.php?page=userpage&message=Successfully Deleted';
        header("Location: $home_url");
         exit(); 
    }
   
    header("Location: $home_url");
     exit();
}else{
    $home_url=site_url().'/wp-admin/admin.php?page=userpage&message=Error';
    header("Location: $home_url");
     exit(); 
}
$home_url=site_url().'/wp-admin/admin.php?page=userpage&yes=ok';
header("Location: $home_url");
 exit(); 
 }
if(isset($_POST['createfolder'])){
  global $wpdb;
  $tablename=$wpdb->prefix.'um_admin_fileupload_client_member';
  if(isset($_POST['folderid'])){
      $data=array(
          'user_id' =>  um_profile_id(),
          'f_name'=>$_POST['createfolder'],
          'f_type'=>'folder',
          'linkwith'=>$_POST['folderid'],
           'membership' =>$_POST['um_file_upload_admin_member']
      );
        $wpdb->insert( $tablename, $data);
        $home_url=site_url().'/wp-admin/admin.php?page=userpage&upload=true&folderid='.$_POST['folderid'];
        header("Location: $home_url");
        exit();
  }else{
      $data=array(
          'user_id' =>  um_profile_id(),
          'f_name'=>$_POST['createfolder'],
          'f_type'=>'folder',
            'membership' =>$_POST['um_file_upload_admin_member']  
      );
        $wpdb->insert( $tablename, $data);
        $home_url=site_url().'/wp-admin/admin.php?page=userpage&upload=true';
        header("Location: $home_url");
        exit();
  }
  
}else if(isset($_FILES['um_file_upload_admin'])){
       
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
      if(isset($_POST['folderid'])){
          $data=array(
              'user_id' =>  um_profile_id(),
              'link' =>  $upload_id,
              'f_name'=>$profilepicture['name'],
              'f_type'=>'file',
              'linkwith'=>$_POST['folderid'],
              'membership' =>  $membership );
            $wpdb->insert( $tablename, $data);
            $home_url=site_url().'/wp-admin/admin.php?page=userpage&upload=true&folderid='.$_POST['folderid'];
            header("Location: $home_url");
            exit();
      }else{
          $data=array(
              'user_id' =>  um_profile_id(),
              'link' =>  $upload_id,
              'f_name'=>$profilepicture['name'],
              'f_type'=>'file',
              'membership' =>  $membership );
            $wpdb->insert( $tablename, $data);
               
$home_url=site_url().'/wp-admin/admin.php?page=userpage&upload=true';
header("Location: $home_url");
exit();
      }
      

 
      $home_url=site_url().'/wp-admin/admin.php?page=userpage&upload=true';
      header("Location: $home_url");
       exit();

 

  }
?>

 
 
<div class="container rounded">
<div class="row" style="height: 100vh;">
  <div class="col-md-3 border-right" style="border-right: 1px solid rgb(226, 225, 225);">

  <div id="fileuploadid" class="file-upload">
<div class="row">
    <div class="col-md-12">
      <form class="form-for-uploadfile" action="<?php echo  site_url().'/wp-admin/admin.php?page=userpage'; ?>" method="post" enctype="multipart/form-data" >
        <h1 class="h1">Add Image </h1>
  <?php if(isset($_GET['upload'])){

      ?>
      <div class="alert alert-success alert-white rounded">
  
  <strong>Success!</strong> File Has Uploaded
</div>
      <?php
  }
     
  ?>
        <fieldset class="fieldset">  
          <label class="label-form" for="image">Image</label>
          <input  class="input" type="file" id="image" name="um_file_upload_admin"/>
        
         <label  class="label-form"  for="job">Membership:</label>
          <select  name="um_file_upload_admin_member" class="select-member"  id="job" name="user_job">
            <?php
  $um_roles = get_option( 'um_roles', array() );
 for($i=0;$i<count($um_roles);$i++){
     echo "<option  class=\"option\"  value=\"um_{$um_roles[$i]}\">{$um_roles[$i]}</option>";
 }
?>
   </select>
          <?php
if(isset($_GET['folderid'])){
echo   '<input type="hidden" name="folderid" value="'.$_GET['folderid'].'"/>';
}

?>

 
          </fieldset>
       
        <button  class="button-upload-file"  type="submit">Upload File</button>
        
       </form>
        </div>
      </div>
</div>
  
</div>

<div class="col-md-9">
<?php echo do_shortcode('[provide-by-admin-dashboard]'); ?>
</div>
</div>	</div>
 