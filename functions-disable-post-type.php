<?php

/*
 * Drop this into your theme or plugin folder to disable the default 'post' content type.
 * Themes: Add the following at the top of your functions.php file.
 * Plugins: Add the following at the top of your main plugin PHP file.
 * include 'functions-disable-post-type.php';
 */

/**
 * De-register the content type
 */
function custom_remove_default_post_type() {
  // remove some default post types
  $remove_types = array('post');
  global $wp_post_types;
  foreach($remove_types as $post_type) {
    if ( isset( $wp_post_types[ $post_type ] ) ) {
      unset( $wp_post_types[ $post_type ] );
    }
  }
}
add_action( 'after_setup_theme', 'custom_remove_default_post_type' );

/**
 * Disable the content-type menu tree for viewing and editing Posts via the admin section of Wordpress
 */
function custom_remove_default_post_type_admin_menu() {
  // remove some default post types' admin menus
  $remove_types = array('post');
  $slug_base = 'edit.php';
  // in admin AJAX requests, the menu list can be empty, causing errors.
  global $menu; if(empty($menu)) return;
  foreach($remove_types as $post_type) {
    $slug = ( $post_type == 'post' ) ? $slug_base : $slug_base . '?post_type=' . $post_type;
    remove_menu_page( $slug );
  }
}
add_action( 'admin_init', 'custom_remove_default_post_type_admin_menu' );

/**
 * Remove the ‘New Post’ link from the admin bar
 */
function custom_remove_default_post_type_new_content_menu() {
  global $wp_admin_bar;
  $wp_admin_bar->remove_menu('new-post');
}
add_action( 'wp_before_admin_bar_render', 'custom_remove_default_post_type_new_content_menu' );

/**
 * If someone clicks on the "New" in the admin bar, default them to the page type.
 */
function custom_redirect_post_editor_to_page_editor() {
  if(empty($_SERVER['REQUEST_URI'])) return;
  if('/wp-admin/post-new.php' !== $_SERVER['REQUEST_URI']) return;
  if(!empty($_GET['post_type'])) return;
  wp_redirect(add_query_arg(['post_type' => 'page']));
  exit;
}
add_action( 'admin_init', 'custom_redirect_post_editor_to_page_editor' );
