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
 //Example - end

/* ---------------------------------------------------------------------------------------------------------------------*/
// Hook the 'admin_menu' action hook, run the function named 'mdp_Add_My_Admin_Link()'
    add_action( 'admin_menu', 'mcfp_pf_Add_My_Admin_Link' );
 
// Add a new top level menu link to the ACP
function mcfp_pf_Add_My_Admin_Link()
{
  // add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function = '', $icon_url = '', $position = null );
  add_menu_page(
        'My First Page', // Title of the page
        'Mytheme Contact Form Plugin - Petit Four', // Text to show on the menu link
        'manage_options', // Capability requirement to see the link
        'includes/mcfp-pf-first-acp-page.php' // The 'slug' - file to display when clicking the link
    );
  // add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function = '' ); // demo
}


// has_cap was called with an argument… since version 2.0.0! ... --> set $cabablity = "manage_options" --> done

function mcfp_pf_Add_My_Admin_Actions()
{
	//Add to Settings menu

	     // add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function = '' );
	// add_options_page("Mytheme Contact Form Plugin Options", "Mytheme Contact Form Plugin", "manage_options", "Mytheme Contact Form Plugin", "mcptp_plugin_options");


       // or/and Add to Tools menu
  // add_management_page("Mytheme CPT Plugin Options", "Mytheme CPT Plugin", "manage_options", "Mytheme CPT Plugin", "mcptp_plugin_options");


}
// add_action('admin_menu', 'mcfp_Add_My_Admin_Actions');

function mcfp_pf_plugin_options() {
  // User Interface

}


function html_form_code_pf() {
    echo '<div class="container">';
    echo '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">';
    echo '<p>';
    echo 'Your Name (required) <br />';
    echo '<input type="text" name="cf-name" pattern="[a-zA-Z0-9 ]+" value="' . ( isset( $_POST["cf-name"] ) ? esc_attr( $_POST["cf-name"] ) : '' ) . '" size="40" />';
    echo '</p>';
    echo '<p>';
    echo 'Your Email (required) <br />';
    echo '<input type="email" name="cf-email" value="' . ( isset( $_POST["cf-email"] ) ? esc_attr( $_POST["cf-email"] ) : '' ) . '" size="40" />';
    echo '</p>';
    echo '<p>';
    echo 'Subject (required) <br />';
    echo '<input type="text" name="cf-subject" pattern="[a-zA-Z ]+" value="' . ( isset( $_POST["cf-subject"] ) ? esc_attr( $_POST["cf-subject"] ) : '' ) . '" size="40" />';
    echo '</p>';
    echo '<p>';
    echo 'Your Message (required) <br />';
    echo '<textarea rows="10" cols="35" name="cf-message">' . ( isset( $_POST["cf-message"] ) ? esc_attr( $_POST["cf-message"] ) : '' ) . '</textarea>';
    echo '</p>';
    echo '<p><input type="submit" name="cf-submitted" value="Send"/></p>';
    echo '</form>';
    echo '</div>';
}

function deliver_mail_pf() {

    // if the submit button is clicked, send the email
    if ( isset( $_POST['cf-submitted'] ) ) {

        // sanitize form values
        $name    = sanitize_text_field( $_POST["cf-name"] );
        $email   = sanitize_email( $_POST["cf-email"] );
        $subject = sanitize_text_field( $_POST["cf-subject"] );
        $message = esc_textarea( $_POST["cf-message"] );

        // get the blog administrator's email address
        $to = get_option( 'admin_email' );
        // $to = "xve73847@nbzmr.com";

        // $headers = "From: $name <$email>" . "\r\n";
        $headers = 'From: '.$name.' <'.$email.'> \r\n';

        // If email has been process for sending, display a success message
        if ( wp_mail( $to, $subject, $message, $headers ) ) {
            echo '<div>';
            echo '<p>Thanks for contacting me, expect a response soon.</p>';
            echo '</div>';
        } else {
            echo '<div class="container">';
            echo 'An unexpected error occurred';
            echo '</div>';
        }
    }
}

function cf_shortcode_pf() {
    ob_start();
    deliver_mail_pf();
    html_form_code_pf();

    return ob_get_clean();
}
add_shortcode( 'sitepoint_contact_form_pf', 'cf_shortcode_pf' );


// ----------------------------------






  // ----------------------------------

