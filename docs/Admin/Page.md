# Pages
Pages display your sections, fields, controls, and help tabs. It can be used to add a top-level admin page, or a child page. There are several parameters when adding a new page:

## add_page()
### Parameters
__$id__ (_EZWPZ\Admin\Page_|_string_) (Required) Page object, or ID.

__$args__ (_array_) Array of properties for the new Page object.
- __$page_title__ (_string_) The text to be displayed in the title tags when the page is selected. Also displayed at the top of the page.
- __$menu_title__ (_string_) The text to be used for the menu.
- __$capability__ (_string_) The capability required for this menu to be displayed to the user. Default 'manage_options'.
- __$icon_url__ (_string_) The URL to the icon to be used for this menu. Can be a base64-encoded SVG using a data URI, the name of a Dashicons class, or 'none' to leave it empty. Not used for child pages.
- __$position__ (_int_) The position in the menu order this item should appear.
- __$parent_slug__ (_string_) The slug for the parent page. Only use this is the page shouldn't be top-level.
- __$submenu_title__ (_string_) The submenu title to be used for top-level pages that have sub-pages. Not used for child pages.

### Return
Returns the EZWPZ\Admin\Page object created.

```php
$admin->add_page('page_id', [
    'page_title' => __('Page Title', 'my_plugin_textdomain'),
    'menu_title' => __('Menu Title', 'my_plugin_textdomain'),
    'submenu_title' => __('Settings', 'my_plugin_textdomain'),
    'icon_url' => 'dashicons-search',
]);
$admin->add_page('child_page_id', [
    'page_title' => __('Child Page Title', 'my_plugin_textdomain'),
    'menu_title' => __('Child Page', 'my_plugin_textdomain'),
    'parent_slug' => 'page_id',
]);
```

## get_page()
### Parameters
__$id__ (_string_) (Required) Page ID.

### Return
Returns the EZWPZ\Admin\Page object or false if it doesn't exist.

```php
$admin->get_page('page_id');
```

## remove_page()
### Parameters
__$id__ (_string_) (Required) Page ID.

### Return
Returns true if the page was removed. False if it didn't exist.

```php
$admin->remove_page('page_id');
```

## Hooks
Instead of trying to find a plugin page's hook suffix, EZWPZ creates a couple different actions you can tie into that use the Page's ID instead.

### ezwpz_admin_page-{$page_id}
This will fire on the `load-{$hook_suffix}` action.

### ezwpz_admin_page_enqueue-{$page_id}
This will fire on the `admin_enqueue_scripts` action, but only on the specified page. Use this hook to enqueue scripts and styles.
