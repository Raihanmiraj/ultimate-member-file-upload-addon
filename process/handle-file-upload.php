
<?php
 
function um_submit_account_errors_hook2($args)
{
  
   $tab = sanitize_key($args['_um_account_tab']);
   switch ($tab) {
        case 'fileupload':{
                if (isset($args['um_file_upload'])) {
                    $args['um_file_upload'] = sanitize_text_field($args['um_file_upload']);
                }
                um_file_upload_function_handle();

                UM()->form()->add_error('single_user_fileupload', __('You must enter your password', 'ultimate-member'));
                break;
            }
    }
}

add_action('um_submit_account_errors_hook', 'um_submit_account_errors_hook2', 1, 1);
?>