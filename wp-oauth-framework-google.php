<?php
defined( 'ABSPATH' ) or die( "No script kiddies please!" );

/**
 * WPOF Google login
 *
 * Plugin Name:       WPOF Google login
 * Description:       Enables login and registration using Google
 * Version:           1.0.0
 * Author:            Koen Gabriëls
 * Author URI:        http://www.appsaloon.be
 */
add_filter( 'wpof_registered_services', 'wpof_google_add_service' );

function wpof_google_add_service( $registered_services ) {
    $config = array(
        'authorize_endpoint' => 'https://accounts.google.com/o/oauth2/v2/auth',
        'token_endpoint' => 'https://www.googleapis.com/oauth2/v4/token',
        'credentials_in_request_body' => false,
        'use_comma_separated_scope' => false,
        'user_info_endpoint' => 'https://www.googleapis.com/oauth2/v3/userinfo',
        'user_info_endpoint_method' => 'post',
        'plugin_folder' => __DIR__,
        'plugin_file' => __FILE__ ,
        'style_url' => plugins_url( 'css/social-login.css', __FILE__ ),
        'scope' => array( 'openid', 'email' ),
    );
    $registered_services[] = new \wp_oauth_framework\classes\Oauth_Service( 'Google', $config );
    return $registered_services;
}

add_filter( 'wpof_sanitize_settings_Google', 'wpof_google_sanitize_settings' );

function wpof_google_sanitize_settings( $settings ) {
    return $settings;
}

add_filter( 'wpof_user_info_data_Google', 'wpof_google_user_info', 10, 1 );

function wpof_google_user_info( $user_info ) {
    return array(
        'user_id' => $user_info['sub'],
        'name' => $user_info['given_name'],
        'email' => $user_info['email'],
    );
}