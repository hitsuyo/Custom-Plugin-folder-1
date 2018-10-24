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
    add_action( 'admin_menu', 'mcptp_Add_My_Admin_Link' );
 
// Add a new top level menu link to the ACP
function mcptp_Add_My_Admin_Link()
{
  // add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function = '', $icon_url = '', $position = null );
  add_menu_page(
        'My First Page', // Title of the page
        'Mytheme CPT Plugin', // Text to show on the menu link
        'manage_options', // Capability requirement to see the link
        'includes/mcptp-first-acp-page.php' // The 'slug' - file to display when clicking the link
    );
  // add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function = '' ); // demo
}

function my_menu_render() {
    global $title;
    ?>
        <h2><?php echo $title;?></h2>
        My New Menu Page!!
        <?php
}

// has_cap was called with an argument… since version 2.0.0! ... --> set $cabablity = "manage_options" --> done

function mcptp_Add_My_Admin_Actions()
{
	//Add to Settings menu

	     // add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function = '' );
	// add_options_page("Mytheme CPT Plugin Options", "Mytheme CPT Plugin", "manage_options", "Mytheme CPT Plugin", "mcptp_plugin_options");


       // or/and Add to Tools menu
  // add_management_page("Mytheme CPT Plugin Options", "Mytheme CPT Plugin", "manage_options", "Mytheme CPT Plugin", "mcptp_plugin_options");


  add_options_page("Mytheme CPT Plugin Options", "Mytheme CPT Plugin", "manage_options", "Mytheme CPT Plugin", "mcptp_plugin_options_2");
  add_management_page("Mytheme CPT Plugin Options", "Mytheme CPT Plugin", "manage_options", "Mytheme CPT Plugin", "mcptp_plugin_options_2");


}
add_action('admin_menu', 'mcptp_Add_My_Admin_Actions');

function mcptp_plugin_options() {
  // User Interface
  if ( !current_user_can( 'manage_options' ) )  {
    wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
  }
  echo '<div class="wrap">';
  echo '<p>Here is where the form would go if I actually had options.</p>';

  echo '</div>';

  echo '<form action="options.php" method="get">';
  echo 'Post type name: <input type="text" name="post_type_name"><br>';
  // echo 'Last name: <input type="text" name="lname"><br>';
  echo '<input type="submit" value="Submit">';
  // echo '<input type="submit" formmethod="post" value="Submit using POST">';
  echo '</form>';

}

function mcptp_plugin_options_2() {
  global $mfmp_options;

  // User Interface
  if ( !current_user_can( 'manage_options' ) )  {
    wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
  }
  echo '<div class="wrap">';
  echo '<p>Here is where the form would go if I actually had options.</p>';

  echo '</div>';

  echo '<form action="myPost_custom_init" method="get">';
  echo 'Post type names: <input type="text" name="post_type_name" value="'.$mfmp_options['twitter_url'].'"><br>';
  // echo 'Last name: <input type="text" name="lname"><br>';
  echo '<label class="description">'._e("Enter your Twitter URL","mfwp_domain").'</label>';

  echo '<input type="submit" value="Submit">';
  // echo '<input type="submit" formmethod="post" value="Submit using POST">';
  echo '</form>';

}


// ----------------------------------
function myPost_custom_init($name) {
    $name = 'my_post';

    $args = array(

      'public' => true,

      'label'  => 'My_Posts_from_plugin',

      'supports' => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),

     'taxonomies'  => array( 'taxonomy_1' ),

      'has_archive' => true,
      'can_export'          => true,
      'exclude_from_search' => false,
      'yarpp_support'       => true,

      'publicly_queryable' => true,

      'rewrite' => array('slug' => 'myposts'),

      'show_in_menu'       => true,

      'register_meta_box_cb' => 'theme_slug_custom_meta_box',

    );
      // register_post_type( 'my_post', $args );  //default
      register_post_type( $name , $args );  //default

}
  add_action( 'init', 'myPost_custom_init' ); // important to activate plugin
// add_action( 'init', 'mytheme-cpt-plugin\includes\myPost_custom_init' ); // important to activate plugin


register_activation_hook( __FILE__, 'myPost_custom_init');

// -------------------------------------------

function netgloo_custom_post_types() {

  // Here we go, this is the only thing you need to modify to registers your CPTs:
  // write inside this array ($types) an array for each CPT you want
  // (and if you need only one CPT simply let one array)
  $types = array(

    // Bicycles
    array('type'          => 'bicycle',
          'typePlural'    => 'bicycles',
          'labelSingle'   => 'Bicycle',
          'labelPlural'   => 'Bicycles'
      ),

    // Movies
    array('type'          => 'movie',
          'typePlural'    => 'movies',
          'labelSingle'   => 'Movie',
          'labelPlural'   => 'Movies'
      ),
    // Furniture
    array('type'          => 'furniture',
          'typePlural'    => 'furnitures',
          'labelSingle'   => 'Furniture',
          'labelPlural'   => 'Furnitures'
      )
  );

  // This foreach loops the $types array and creates labels and arguments for each CPTs
  foreach ($types as $type) {

    $typeSingle = $type['type'];
    $typePlural = $type['typePlural'];
    $labelSingle = $type['labelSingle'];
    $labelPlural = $type['labelPlural'];

    // Labels: here you can translate the strings in your language.
    // These strings will be displayed in the admin panel
    $labels = array(
      'name'               => _x( $labelPlural, ' post type general name' ),
      'singular_name'      => _x( $labelSingle, ' post type singular name' ),
      'add_new'            => _x( '-> Add New ', $labelSingle ),
      'add_new_item'       => __( 'Add new '. $labelSingle ),
      'edit_item'          => __( 'Modify '. $labelSingle ),
      'new_item'           => __( 'New '. $labelSingle ),
      'all_items'          => __( '-> CPT '. $labelPlural ),
      'view_item'          => __( 'Mostra '. $labelSingle ),
      'search_items'       => __( 'Cerca '. $labelPlural ),
      'not_found'          => __( 'There is not '. $labelSingle .' exist' ),
      'not_found_in_trash' => __( 'Nessun '. $labelSingle .' trovato nel cestino' ),
      'parent_item_colon'  => '',
      'menu_name'          => __( $labelPlural ),
    );

    // Arguments (some settings, to learn more see WordPress docs)
    $args = array(
      'labels'        => $labels,
      'description'   => 'Holds our products and product specific data',
      'public'        => true,
      'supports'      => array( 'title', 'author', 'editor', 'thumbnail', 'excerpt', 'comments', 'page-attributes' ),
      'has_archive'   => false,
      'menu_position' => 5,
      'rewrite'       => true, // default
      'menu_icon'     => get_template_directory_uri().'/assets/img/cpt-'.$typeSingle.'.png'
    );

    // Finally, we can registers the post types
    register_post_type( $typeSingle, $args );

  } // end foreach

}
add_action( 'init', 'netgloo_custom_post_types' );



  // ----------------------------------

