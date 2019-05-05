# EZWPZ\Menu

## `Manager::add_page(Page|string $id, array $args = [])`

Add an EZWPZ Page to the WordPress admin area.

### Parameters

- **$id**<br>
(Page | string) (Required) Page object or ID.
- **$args**<br>
(array) (Optional) Array of properties for the new Page.
  - **'page_title'**<br>
  (string) Text to be displayed in the title tags.<br>
  Default value: ''
  - **'menu_title'**<br>
  (string) The text to be used for the menu.<br>
  Default value: ''
  - **'submenu_title'**<br>
  (string) The text to be used for the duplicate submenu item if a top-level menu item.<br>
  Default value: ''
  - **'capability'**<br>
  (string) The capability required for this menu to be displayed to the user.<br>
  Default value: 'manage_options'
  - **'icon_url'**<br>
  (string) The URL to the icon to be used for this menu.<br>
  Default value: ''
    - Pass a base64-encoded SVG using a data URI, which will be colored to match the color scheme. This should begin with 'data:image/svg+xml;base64,'.
    - Pass the name of a Dashicons heper class to use a font icon.
    - Pass 'none' to leave the icon div empty so an icon can be added via CSS.
  - **'callback'**<br>
  (callable) The function to be called to output the content for this page.<br>
  Default value: [$this, 'render']
  - **'position'**<br>
  (int) The position in the menu order this one should appear.<br>
  Default value: null
  - **'parent_slug'**<br>
  (string) The slug name for the parent menu (or the file name of a standard WordPress admin page).<br>
  Default value: ''

```php
add_action('ezpwz_menu', 'my_plugin_settings_page');
function my_plugin_settings_page($menu) {
    $menu->add_page('my_plugin', [
        'page_title' => 'My Plugin',
        'menu_title' => 'My Plugin',
        'parent_slug' => 'options-general.php'
    ]);
}
```

## `Manager::get_page(string $id)`

Get the Page object for an admin page added with EZWPZ.

### Parameters

- **$id**<br>
(string) (Required) Page ID.

### Return

(Page | false) The Page object, or false if the page doesn't exist.

```php
add_action('ezpwz_menu', 'my_plugin_settings_page');
function my_plugin_settings_page($menu) {
    $menu->get_page('my_plugin');
}
```

## `Manager::remove_page(string $id)`

Remove an admin page added with EZWPZ.

### Parameters

- **$id**<br>
(string) (Required) Page ID.

```php
add_action('ezpwz_menu', 'my_plugin_settings_page');
function my_plugin_settings_page($menu) {
    $menu->remove_page('my_plugin');
}
```
