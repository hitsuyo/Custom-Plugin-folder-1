<?php
/*
 * Add my new menu to the Admin Control Panel
 */

// Example
/*
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

*/
// Example - end


// Hook the 'admin_menu' action hook, run the function named 'mdp_Add_My_Admin_Link()'
add_action( 'admin_menu', 'mmp_Add_My_Admin_Link' );
 
// Add a new top level menu link to the ACP
function mmp_Add_My_Admin_Link()
{
  add_menu_page(
        'My First Page', // Title of the page
        'Mytheme Metabox Plugin', // Text to show on the menu link
        'manage_options', // Capability requirement to see the link
        'includes/mmp-first-acp-page' // The 'slug' - file to display when clicking the link
    );
}

function mmp_Add_My_Admin_Actions()
{
	//Add to Settings menu

	// add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function = '' );
	add_options_page("Mytheme Metabox Plugin", "Mytheme Metabox Plugin", "manage_options", "Mytheme Metabox Plugin", "mmp_plugin_options");

	// or/and Add to Tools menu
	// add_management_page("Mytheme Demo Plugin", "Mytheme Demo Plugin", 1, "Mytheme Demo Plugin", "mdp_admin_actions");
}
add_action('admin_menu', 'mmp_Add_My_Admin_Actions');

function mmp_plugin_options() {
  if ( !current_user_can( 'manage_options' ) )  {
    wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
  }
  echo '<div class="wrap">';
  echo '<p>Here is where the form would go if I actually had options.</p>';

  echo '</div>';
}


// -------------------------------------------
// META BOX

function theme_slug_custom_meta_box() {

    add_meta_box( 'ABC', esc_html__( 'ABC', 'theme-textdomain' ), 'theme_slug_team_designation', array( 'book' ) ); 

    // add_meta_box( 'Checkbox', esc_html__( 'Size', 'theme-textdomain' ), 'cd_meta_box_cb', array( 'book' ) );

    add_meta_box( 'Checkbox', esc_html__( 'Size', 'theme-textdomain' ), 'cd_meta_box_cb', array( 'my_post' ) );

}
add_action( 'add_meta_boxes', 'theme_slug_custom_meta_box' );


function theme_slug_team_designation( $post ) {

    $team_designation     = get_post_custom( $post -> ID );

    $team_designation_box = isset( $team_designation[ 'ABC' ] ) ? esc_attr( $team_designation[ 'ABC' ][ 0 ] ) : '';

 

    wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );

    ?>

 

    <p>

        <input type="text" class="widefat" name="ABC" id="abc" value="<?php echo $team_designation_box; ?>"/>

    </p>

    <?php

}

function cd_meta_box_cb()
{

    // $post is already set, and contains an object: the WordPress post

    global $post;

    $values = get_post_custom( $post->ID );

    $text = isset( $values['my_meta_box_text'] ) ? $values['my_meta_box_text'] : '';

    $selected = isset( $values['my_meta_box_select'] ) ? esc_attr( $values['my_meta_box_select'] ) : '';

    $check = isset( $values['my_meta_box_check'] ) ? esc_attr( $values['my_meta_box_check'] ) : '';

     

    // We'll use this nonce field later on when saving.

    wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );

    ?>

    <!-- <p>

        <label for="my_meta_box_text">Text Label</label>

        <input type="text" name="my_meta_box_text" id="my_meta_box_text" value=" php echo $text; ?>" />

    </p> -->

     

    <p>

        <label for="my_meta_box_select">Size</label>

        <select name="my_meta_box_select" id="my_meta_box_select">

            <option value="S" <?php selected( $selected, 's' ); ?>>S</option>

            <option value="M" <?php selected( $selected, 'm' ); ?>>M</option>

            <option value="L" <?php selected( $selected, 'l' ); ?>>L</option>

        </select>

        <label>Giá trị sẽ được lưu khi bạn bấm "Cập nhật"</label>

    </p>

     

    <p>

        <input type="checkbox" id="my_meta_box_check" name="my_meta_box_check" <?php checked( $check, 'on' ); ?> />

        <label for="my_meta_box_check">Do not check this</label>

    </p>

    <?php    

}


// Save data of Meta box into Theme
function theme_slug_meta_save( $post_id ) {

    // Return/Bail out if doing autosave

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {return;}

    // Checking if the nonce isn't there, or we can't verify it, then we should return

    if ( ! isset( $_POST[ 'meta_box_nonce' ] ) || ! wp_verify_nonce( $_POST[ 'meta_box_nonce' ], 'my_meta_box_nonce' ) ) {return;}

    // Checking if the current user can't edit this post, then we should return

    if ( ! current_user_can( 'edit_posts' ) ) {return;}

    // Saving the data in meta box

    // Saving the team designation in the meta box

    if ( isset( $_POST[ 'abc' ] ) ) {

        update_post_meta( $post_id, 'ABC', sanitize_text_field( $_POST[ 'abc' ] ) ); 
    }

    if ( isset( $_POST[ 'my_meta_box_select' ] ) ) {

        update_post_meta( $post_id, 'Size', sanitize_text_field( $_POST[ 'my_meta_box_select' ] ) ); 
    }


    if ( isset( $_POST[ 'my_meta_box_select' ] ) ) {

        update_post_meta( $post_id, 'Kích thước', sanitize_text_field( $_POST[ 'my_meta_box_select' ] ) ); 

    }

}

add_action( 'save_post', 'theme_slug_meta_save' );

// META BOX -end

add_action( 'setting_from_plugin', 'theme_slug_custom_meta_box' );
add_action( 'setting_from_plugin', 'theme_slug_team_designation' );
add_action( 'setting_from_plugin', 'cd_meta_box_cb' );
add_action( 'setting_from_plugin', 'theme_slug_meta_save' );

// if( is_plugin_active( 'mytheme-cpt-plugin/mytheme-cpt-plugin.php' ) ) {
//     
// }
register_activation_hook( __FILE__, 'theme_slug_custom_meta_box');
register_activation_hook( __FILE__, 'theme_slug_team_designation');
register_activation_hook( __FILE__, 'cd_meta_box_cb');
register_activation_hook( __FILE__, 'theme_slug_meta_save');