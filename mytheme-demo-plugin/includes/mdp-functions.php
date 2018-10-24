<?php
/*
 * Add my new menu to the Admin Control Panel
 */

// Example
function wp_first_shortcode(){
	echo "Hello, This is your another shortcode!";
}
add_shortcode('first', 'wp_first_shortcode');

function form_creation(){
	?>
	<form>
	First name: <input type="text" name="firstname"><br>
	Last name: <input type="text" name="lastname"><br>
	Message: <textarea name="message"> Enter text here...</textarea>
	</form>
	<?php
}
add_shortcode('test', 'form_creation');
// Example - end


// Hook the 'admin_menu' action hook, run the function named 'mdp_Add_My_Admin_Link()'
add_action( 'admin_menu', 'mdp_Add_My_Admin_Link' );
 
// Add a new top level menu link to the ACP
function mdp_Add_My_Admin_Link()
{
  add_menu_page(
        'My First Page', // Title of the page
        'Mytheme Demo Plugin', // Text to show on the menu link
        'manage_options', // Capability requirement to see the link
        'includes/mdp-first-acp-page.php' // The 'slug' - file to display when clicking the link
    );
}

function mdp_Add_My_Admin_Actions()
{
	//Add to Settings menu

	// add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function = '' );
	add_options_page("Mytheme Demo Plugin", "Mytheme Demo Plugin", 1, "Mytheme Demo Plugin", "mdp_admin_actions");

	// or/and Add to Tools menu
	// add_management_page("Mytheme Demo Plugin", "Mytheme Demo Plugin", 1, "Mytheme Demo Plugin", "mdp_admin_actions");
}
add_action('admin_menu', 'mdp_Add_My_Admin_Actions');


function myPost_custom_init() {

    $args = array(

      'public' => true,

      'label'  => 'My_Posts_from_plugin',

      'supports' => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),

     'taxonomies'  => array( 'taxanomy_1' ),

      'has_archive' => false,
      'can_export'          => true,
      'exclude_from_search' => false,
      'yarpp_support'       => true,

      'publicly_queryable' => true,

      'rewrite' => array('slug' => 'myposts'),

      'show_in_menu'       => true,

      'register_meta_box_cb' => 'theme_slug_custom_meta_box',

    );

    register_post_type( 'my_post', $args );

}
add_action( 'init', 'mytheme-demo-plugin\includes\myPost_custom_init' );

register_activation_hook( __FILE__, 'myPost_custom_init');