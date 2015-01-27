<?php
/**
 * Load text domain
 */
function wrs_text_domain ()
{
    load_plugin_textdomain ( WRS_DOMAIN, FALSE, dirname ( WRS_PLUGIN_PATH ) . '/languages/' );
}

/**
 * Check if user has role
 * @param      $role
 * @param null $user_id
 *
 * @return bool
 */
function wrs_check_user_role( $role, $user_id = null ) {

    if ( is_numeric( $user_id ) )
        $user = get_userdata( $user_id );
    else
        $user = wp_get_current_user();

    if ( empty( $user ) )
        return false;

    return in_array( $role, (array) $user->roles );
}