<?php


 /**
 * Add export and erase user's data in privacy tab
 *
 * @param $args
 */
add_action( 'um_after_fileupload', 'um_after_fileupload' );
function um_after_fileupload( $args ) {
	 
}

function new_modify_user_table( $column ) {
    $column['view_um_file_upload'] = 'View File';
 
    return $column;
}
add_filter( 'manage_users_columns', 'new_modify_user_table' );

function new_modify_user_table_row( $val, $column_name, $user_id ) {
    switch ($column_name) {
        case 'view_um_file_upload' :
            return '<a href="'.site_url().'/wp-admin/admin.php?page=userpage&user='.$user_id.'">View</a>';
       default:
    }
    return $val;
}
add_filter( 'manage_users_custom_column', 'new_modify_user_table_row', 10, 3 );
