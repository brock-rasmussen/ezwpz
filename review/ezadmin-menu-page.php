<?php
/**
 * Helper to easily add a menu page or submenu page.
 *
 * @param string $id
 * @param callable $function
 * @param array $args {
 *    An array of optional arguments to override the defaults.
 *
 *    @type string $page_title Passed to add_menu_page() or add_submenu_page().
 *    @type string $menu_title Passed to add_menu_page() or add_submenu_page().
 *    @type string $capability Passed to add_menu_page() or add_submenu_page().
 *    @type string $icon_url   Passed to add_menu_page().
 *    @type int    $position   Passed to add_menu_page().
 * }
 * @param bool|string $parent_slug
 */
if ( ! function_exists( 'ezadmin_menu_page' ) ) {
  function ezadmin_menu_page($id, $function, $args = [], $parent_slug = false) {
    $args = wp_parse_args($args, [
      'page_title' => '',
      'menu_title' => '',
      'capability' => 'manage_options',
      'icon_url' => '',
      'position' => null
    ]);

    if ($parent_slug) {
      $wp_slugs = [
        'dashboard' => 'index.php',
        'posts' => 'edit.php',
        'media' => 'upload.php',
        'pages' => 'edit.php?post_type=page',
        'comments' => 'edit-comments.php',
        'appearance' => 'themes.php',
        'plugins' => 'plugins.php',
        'users' => current_user_can('edit_users') ? 'users.php' : 'profile.php',
        'tools' => 'tools.php',
        'settings' => 'options-general.php'
      ];

      if (isset($wp_slugs[$parent_slug])) {
        $parent_slug = $wp_slugs[$parent_slug];
      }

      if (post_type_exists($parent_slug)) {
        $parent_slug = 'edit.php?post_type=' . $parent_slug;
      }

      add_submenu_page($parent_slug, $args['page_title'], $args['menu_title'], $args['capability'], $id, $function);
      return;
    }

    add_menu_page($args['page_title'], $args['menu_title'], $args['capability'], $id, $function, $args['icon_url'], $args['position']);
  }
}
