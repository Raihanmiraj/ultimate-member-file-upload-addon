<?php

add_filter('um_account_page_default_tabs_hook', 'my_custom_tab_in_um', 100);
function my_custom_tab_in_um($tabs)
{
    $tabs[500]['fileupload']['icon'] = 'um-faicon-file';
    $tabs[500]['fileupload']['title'] = 'File Upload';
    $tabs[500]['fileupload']['custom'] = true;
    $tabs[500]['fileupload']['show_button'] = false;
    
    $tabs[900]['fileprovidebyadmin']['icon'] = 'um-faicon-file';
    $tabs[900]['fileprovidebyadmin']['title'] = 'Provide By Admin';
    $tabs[900]['fileprovidebyadmin']['custom'] = true;
    $tabs[900]['fileprovidebyadmin']['show_button'] = false;
    return $tabs;
}

add_action('um_account_tab__fileupload', 'um_account_tab__fileupload');
function um_account_tab__fileupload($info)
{
    global $ultimatemember;
    extract($info);

    // $output = $ultimatemember->account->get_tab_output('fileupload');
    $output = $ultimatemember->account->get_tab_output('fileprovidebyadmin');
    if ($output) {echo $output;}
}

/* add new tab called "mytab" */

// add_filter('um_account_page_default_tabs_hook', 'my_custom_tab_in_umx', 100 );
// function my_custom_tab_in_umx( $tabs ) {
//     // $tabs[800]['fileprovidebyadmin']['icon'] = 'um-faicon-pencil';
//     // $tabs[800]['fileprovidebyadmin']['title'] = 'My Custom Tab';
//     // $tabs[800]['fileprovidebyadmin']['custom'] = true;
//     $tabs[900]['fileprovidebyadmin']['icon'] = 'um-faicon-file';
//     $tabs[900]['fileprovidebyadmin']['title'] = 'Provide By Admin';
//     $tabs[900]['fileprovidebyadmin']['custom'] = true;
//     return $tabs;
// }

/* make our new tab hookable */

add_action('um_account_tab__fileprovidebyadmin', 'um_account_tab__fileprovidebyadmin');
function um_account_tab__fileprovidebyadmin($info)
{
    global $ultimatemember;
    extract($info);

    $output = $ultimatemember->account->get_tab_output('mytab');
    if ($output) {echo $output;}
}

/* Finally we add some content in the tab */

add_filter('um_account_content_hook_fileprovidebyadmin', 'um_account_content_hook_fileprovidebyadmin');
function um_account_content_hook_fileprovidebyadmin($output)
{
    ob_start();
    ?>

	<div class="um-field">

		<!-- Here goes your custom content -->
        <?php echo do_shortcode('[file_show_provide_by_admin]'); ?>

<?php //echo do_shortcode('[upload_form]'); ?>
	</div>

	<?php

    $output .= ob_get_contents();
    ob_end_clean();
    return $output;
}