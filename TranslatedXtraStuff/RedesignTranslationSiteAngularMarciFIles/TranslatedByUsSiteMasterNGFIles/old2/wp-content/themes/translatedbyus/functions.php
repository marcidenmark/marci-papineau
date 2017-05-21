<?php
	
	function tbu_scripts() {
	//wp_enqueue_script( 'functions', get_stylesheet_directory_uri() . '/js/functions.js', array(), '1.0.0', true );
	wp_enqueue_style( 'sparkling', get_template_directory_uri () .'/style.css');
	wp_enqueue_script( 'jquery-mb-browser', get_stylesheet_directory_uri() . '/js/jquery.mb.browser.js', array(), '1.0.0', true );
	wp_enqueue_script( 'bootstrap', get_stylesheet_directory_uri() . '/js/bootstrap.min.js', array(), '1.0.0', true );
	wp_enqueue_script( 'bootstrap-select', get_stylesheet_directory_uri() . '/js/bootstrap-select.js', array(), '1.0.0', true );
	wp_enqueue_style( 'dashicons', '/wp-includes/css/dashicons.min.css');
	wp_enqueue_style( 'roboto', 'https://fonts.googleapis.com/css?family=Roboto+Slab:400,300,700');

	wp_enqueue_style( 'custom-styles', get_stylesheet_directory_uri () .'/css/custom-styles.css');
	
	

	
	if (get_page_template_slug() == "page-home-one-step.php" || is_front_page() || get_page_template_slug() == "page-landing-page-1.php" || get_page_template_slug() == "page-landing-page-2.php"){
		wp_enqueue_script( 'jquery.printElement.min.js', get_stylesheet_directory_uri() . '/js/jquery.printElement.min.js', array(), '1.0.1', true );
		wp_enqueue_script( 'scripts', get_stylesheet_directory_uri() . '/js/scripts-one-step.js', array(), '1.0.2', true );
		wp_enqueue_script( 'dropzone', get_stylesheet_directory_uri() . '/js/dropzone.js', array(), '1.0.0', true );
		wp_enqueue_script( 'jquery-validate', get_stylesheet_directory_uri() . '/js/jquery.validate.min.js', array(), '1.0.0', true );
//		wp_enqueue_script( 'jquery-steps', get_stylesheet_directory_uri() . '/js/jquery.steps.min.js', array(), '1.0.0', true );
//		wp_enqueue_style( 'jquery.steps', get_stylesheet_directory_uri() . '/jquery.steps.css');
		wp_enqueue_style( 'dropzone', get_stylesheet_directory_uri() . '/dropzone.css');
		wp_enqueue_script( 'magicsuggest', get_stylesheet_directory_uri() . '/js/magicsuggest-min.js', array(), '2.1.4', true );
		wp_enqueue_style( 'magicsuggest', get_stylesheet_directory_uri() . '/magicsuggest-min.css');
		wp_enqueue_script( 'jquery-waypoints', get_stylesheet_directory_uri() . '/js/jquery.waypoints.min.js', array(), '1.0.0', true );
		wp_enqueue_script( 'jquery-sticky', get_stylesheet_directory_uri() . '/js/sticky.min.js', array(), '1.0.0', true );	
	}
	
}

add_action( 'wp_enqueue_scripts', 'tbu_scripts' );



/*
* WPServed
*/


	/*
	* Register menus.
	*/
	function wp_register_menus() {
		register_nav_menus(
			array(
				'footer-nav' => 'Footer Navigation',
				'top-sub-nav' => 'Additional Nav in Top'
			)
		);
	}
	add_action( 'init', 'wp_register_menus' );

	/*
	* Additional functions
	*/

		if ( ! function_exists( 'translatedbyus_header_menu' ) ) :
		/**
		 * Header menu (should you choose to use one)
		 */
		function translatedbyus_header_menu() {
		  // display the WordPress Custom Menu if available
		  wp_nav_menu(array(
		    'menu'              => 'primary',
		    'theme_location'    => 'primary',
		    'depth'             => 2,
		    'container'         => false,
		    'container_class'   => '',
		    'menu_class'        => 'nav navbar-nav',
		    'fallback_cb'       => 'wp_bootstrap_navwalker::fallback',
		    'walker'            => new wp_bootstrap_navwalker()
		  ));
		} /* end header menu */
		endif;

		if ( ! function_exists( 'translatedbyus_additional_header_menu' ) ) :
		/**
		 * Header menu (should you choose to use one)
		 */
		function translatedbyus_additional_header_menu() {
		  // display the WordPress Custom Menu if available
		  wp_nav_menu(array(
		    'menu'              => 'top-sub-nav',
		    'theme_location'    => 'top-sub-nav',
		    'depth'             => 1,
		    'container'         => false,
		    'container_class'   => '',
		    'menu_class'        => 'nav navbar-nav',
		    'fallback_cb'       => 'wp_bootstrap_navwalker::fallback',
		    'walker'            => new wp_bootstrap_navwalker()
		  ));
		} /* end header menu */
		endif;
	