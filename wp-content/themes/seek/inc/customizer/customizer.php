<?php 
/**
 * Seek Theme Customizer.
 *
 * @package Seek
 */

//customizer core option
require get_template_directory() . '/inc/customizer/core/customizer-core.php';

//customizer 
require get_template_directory() . '/inc/customizer/core/default.php';
/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function seek_customize_register( $wp_customize ) {

	// Load custom controls.
	require get_template_directory() . '/inc/customizer/core/control.php';

	// Load customize sanitize.
	require get_template_directory() . '/inc/customizer/core/sanitize.php';

	// Load customize callback.
	require get_template_directory() . '/inc/customizer/core/callback.php';

	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';

	
	/*theme option panel details*/
	require get_template_directory() . '/inc/customizer/theme-option.php';	
	// Register custom section types.
	$wp_customize->register_section_type( 'Seek_Customize_Section_Upsell' );

	// Register sections.
	$wp_customize->add_section(
		new Seek_Customize_Section_Upsell(
			$wp_customize,
			'theme_upsell',
			array(
				'title'    => esc_html__( 'Seek Pro', 'seek' ),
				'pro_text' => esc_html__( 'Upgrade To Pro', 'seek' ),
				'pro_url'  => 'https://www.themeinwp.com/theme/seek-pro/',
				'priority'  => 1,
			)
		)
	);
}
add_action( 'customize_register', 'seek_customize_register' );


/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 *
 * @since 1.0.0
 */
function seek_customize_preview_js() {

	wp_enqueue_script( 'seek_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20130508', true );

}
add_action( 'customize_preview_init', 'seek_customize_preview_js' );


function seek_customizer_css() {
	wp_enqueue_script('seek_customize_admin_js', get_template_directory_uri().'/assets/twp/js/customizer-admin.js', array('customize-controls'));

	wp_enqueue_style( 'seek_customize_controls', get_template_directory_uri() . '/assets/twp/css/customizer-controll.css' );
}
add_action( 'customize_controls_enqueue_scripts', 'seek_customizer_css',0 );
