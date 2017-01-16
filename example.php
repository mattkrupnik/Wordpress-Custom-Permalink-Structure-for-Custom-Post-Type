<?php

// Based on: http://stackoverflow.com/questions/41494883/show-only-specific-categories-in-permalinks-for-custom-post-type-in-wordpress/#answer-41654620

add_action( 'init', 'codex_recipes_init' );
/**
 * Register a recipes post type.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_post_type
 */
function codex_recipes_init() {
    $labels = array(
        'name'               => _x( 'Recipes', 'post type general name', 'your-plugin-textdomain' ),
        'singular_name'      => _x( 'Recipe', 'post type singular name', 'your-plugin-textdomain' ),
        'menu_name'          => _x( 'Recipes', 'admin menu', 'your-plugin-textdomain' ),
        'name_admin_bar'     => _x( 'Recipe', 'add new on admin bar', 'your-plugin-textdomain' ),
        'add_new'            => _x( 'Add New', 'recipe', 'your-plugin-textdomain' ),
        'add_new_item'       => __( 'Add New Recipe', 'your-plugin-textdomain' ),
        'new_item'           => __( 'New Recipe', 'your-plugin-textdomain' ),
        'edit_item'          => __( 'Edit Recipe', 'your-plugin-textdomain' ),
        'view_item'          => __( 'View Recipe', 'your-plugin-textdomain' ),
        'all_items'          => __( 'All Recipes', 'your-plugin-textdomain' ),
        'search_items'       => __( 'Search Recipes', 'your-plugin-textdomain' ),
        'parent_item_colon'  => __( 'Parent Recipes:', 'your-plugin-textdomain' ),
        'not_found'          => __( 'No recipes found.', 'your-plugin-textdomain' ),
        'not_found_in_trash' => __( 'No recipes found in Trash.', 'your-plugin-textdomain' )
    );

    $args = array(
        'labels'             => $labels,
        'description'        => __( 'Description.', 'your-plugin-textdomain' ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'recipes/%type%' ),
        'capability_type'    => 'post',
        'has_archive'        => 'recipes',
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
    );

    register_post_type( 'recipes', $args );
}



// hook into the init action and call create_recipes_taxonomies when it fires
add_action( 'init', 'create_recipes_taxonomies', 0 );

function create_recipes_taxonomies() {

    // Add new taxonomy, NOT hierarchical (like tags)
    $labels = array(
        'name'                       => _x( 'Type', 'taxonomy general name', 'textdomain' ),
        'singular_name'              => _x( 'Type', 'taxonomy singular name', 'textdomain' ),
        'search_items'               => __( 'Search Types', 'textdomain' ),
        'popular_items'              => __( 'Popular Types', 'textdomain' ),
        'all_items'                  => __( 'All Types', 'textdomain' ),
        'parent_item'                => null,
        'parent_item_colon'          => null,
        'edit_item'                  => __( 'Edit Type', 'textdomain' ),
        'update_item'                => __( 'Update Type', 'textdomain' ),
        'add_new_item'               => __( 'Add New Type', 'textdomain' ),
        'new_item_name'              => __( 'New Type Name', 'textdomain' ),
        'separate_items_with_commas' => __( 'Separate types with commas', 'textdomain' ),
        'add_or_remove_items'        => __( 'Add or remove types', 'textdomain' ),
        'choose_from_most_used'      => __( 'Choose from the most used types', 'textdomain' ),
        'not_found'                  => __( 'No types found.', 'textdomain' ),
        'menu_name'                  => __( 'Types', 'textdomain' ),
    );

    $args = array(
        'hierarchical'          => true,
        'labels'                => $labels,
        'show_ui'               => true,
        'show_admin_column'     => true,
        'update_count_callback' => '_update_post_term_count',
        'query_var'             => true,
        'rewrite'               => array( 'slug' => 'type' ),
    );

    register_taxonomy( 'type', 'recipes', $args );
}



function recipes_post_link( $post_link, $id = 0 ){
    $post = get_post( $id );  
    if ( is_object( $post ) ){
        $terms = wp_get_object_terms( $post->ID, 'type' );
        if( $terms ){
            return str_replace( '%type%' , $terms[0]->slug , $post_link );
        }
    }
    return $post_link;  
}
add_filter( 'post_type_link', 'recipes_post_link', 1, 3 );

?>
