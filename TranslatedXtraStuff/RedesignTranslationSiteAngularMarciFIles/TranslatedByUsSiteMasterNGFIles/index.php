<?php

/**
 * Redirection
 */
if ( ! file_exists( 'dev_enviroment' ) && ( empty( $_SERVER['HTTPS'] ) || $_SERVER['HTTPS'] == 'off' ) ){
    header( 'HTTP/1.1 301 Moved Permanently' );
    header( 'Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
    exit();
}

/**
 * Dependencies
 */

global $current_page;

$url = 'http';

if ( isset( $_SERVER["HTTPS"] ) && $_SERVER["HTTPS"] == "on") {
	$url .= "s";
}

$url .= "://";

if ( $_SERVER["SERVER_PORT"] != "80" ) {
	$url .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
} else {
	$url .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
}

$current_page = parse_url( $url, PHP_URL_PATH );

/**
 * Gets SEO tag
 * @param  string $tag title || description
 * @return string      tag
 */
function get_seo_tag( $tag = 'title' ) {

	global $current_page;

	include( 'app/config.php' );

	if ( isset( $meta_tags[ $current_page ] ) ) {

		return $meta_tags[ $current_page ][ $tag ];

	} else {

		return $meta_tags['/404'][ $tag ];

	}

}



/**
 * Gets current page view
 * @return void
 */
function get_view() {

	global $current_page;

	include( 'app/config.php' );

	if ( isset( $routing[ $current_page ] ) ) {

		include( 'views/' . $routing[ $current_page ] );

	} else {

		include( 'views/404.html' );

	}


}

/**
 * View
 */
require_once( 'main.php' );
