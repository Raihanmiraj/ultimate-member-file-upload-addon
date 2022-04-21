<?php



add_filter('um_account_content_hook_fileupload', 'um_account_content_hook_fileupload');
function um_account_content_hook_fileupload($output)
{
    ob_start();
    ?>

	<div class="um-field">

	<?php echo do_shortcode('[file_show_user]'); ?>

	<?php echo do_shortcode('[upload_form]'); ?>

  	</div>

	<?php
echo $_POST['hey'];
    $output .= ob_get_contents();
    ob_end_clean();
    return $output;
}