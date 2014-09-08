<?php
/*
Plugin Name: Gestures
Plugin URI: http://premium.wpmudev.org/
Description: Brings basic gestures to WordPress using Tocca JS
Author: Chris Knowles
Version: 1.0
Author URI: http://twitter.com/ChrisKnowles
*/

/*
 *  Enqueue Gesture Scripts
 */
function gesture_enqueue_scripts() {

	/* only need to enqueue if a single page */
	if ( !is_admin() ) {
	
		// Register the init script
		wp_register_script( 'gestures_init',  plugins_url( 'js/init.js', __FILE__ ), array('jquery'), null, true );
				
		// Now we can localize the script with our data.
		$gestures_array = get_option( 'gestures_settings' , array('container' => '#page', 'destination' => get_site_url() ) );
		wp_localize_script( 'gestures_init', 'gestures', $gestures_array );

		// Enqueue the scripts.
		wp_enqueue_script( 'tocca',  plugins_url( 'js/tocca.js', __FILE__ ), array('jquery'), null, true );
		wp_enqueue_script( 'gestures_init' );		

	}
	
}
 
add_action( 'wp_enqueue_scripts' , 'gesture_enqueue_scripts' );

/* options courtesy of WordPress Settings Generator (http://http://wpsettingsapi.jeroensormani.com/) */

add_action( 'admin_menu', 'gestures_add_admin_menu' );
add_action( 'admin_init', 'gestures_settings_init' );


function gestures_add_admin_menu(  ) { 

	add_options_page( 'Gestures', 'Gestures', 'manage_options', 'gestures_comments', 'gestures_comments_options_page' );

}


function gestures_settings_exist(  ) { 

	if( false == get_option( 'gestures_settings' ) ) { 

		add_option( 'gestures_settings' );

	}

}


function gestures_settings_init(  ) { 

	register_setting( 'gestures_settings', 'gestures_settings' );

	add_settings_section(
		'gestures_settings_section', 
		__( 'Add gesture navigation to your WordPress site', 'gestures' ), 
		'gestures_settings_section_callback', 
		'gestures_settings'
	);

	add_settings_field( 
		'container', 
		__( 'Content container identifier (eg #page)', 'gestures' ), 
		'gestures_container_render', 
		'gestures_settings', 
		'gestures_settings_section' 
	);

	add_settings_field( 
		'destination', 
		__( 'Double-tap takes user where? (enter URL)', 'gestures' ), 
		'gestures_destination_render', 
		'gestures_settings', 
		'gestures_settings_section' 
	);


}


function gestures_container_render(  ) { 

	$options = get_option( 'gestures_settings' , array('container' => '#page', 'destination' => get_site_url() ));
	?>
	<input type='text' name='gestures_settings[container]' value='<?php echo $options['container']; ?>'>
	<?php

}


function gestures_destination_render(  ) { 

	$options = get_option( 'gestures_settings' , array('destination' => '#page', 'destination' => get_site_url() ));
	?>
	<input type='text' name='gestures_settings[destination]' value='<?php echo $options['destination']; ?>'>
	<?php

}


function gestures_settings_section_callback(  ) { 

	echo __( '<p>Enter the identifier for the container and a destination for the double-tap.</p>', 'gestures' );

}


function gestures_comments_options_page(  ) { 

	?>
	<form action='options.php' method='post'>
		
		<h2>Gestures</h2>
		
		<?php
		settings_fields( 'gestures_settings' );
		do_settings_sections( 'gestures_settings' );
		submit_button();
		?>
		
	</form>
	<?php

}

?>