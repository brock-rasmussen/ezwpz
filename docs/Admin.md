# Admin API
The EZWPZ Admin API builds off the [Settings API](https://developer.wordpress.org/plugins/settings/settings-api/) and the [Administration Menus API](https://developer.wordpress.org/plugins/administration-menus/) to make it extremely easy to quickly add an admin settings page - complete with settings, controls, and help tabs.

## Getting Started
Use the `ezwpz_admin` hook to access the Admin class.

```php
add_action('ezwpz_admin', 'my_plugin_admin');
function my_plugin_admin($admin) {
    $admin->add_page();
    $admin->get_page();
    $admin->remove_page();

    $admin->add_section();
    $admin->get_section();
    $admin->remove_section();

    $admin->add_field();
    $admin->get_field();
    $admin->remove_field();

    $admin->add_control();
    $admin->get_control();
    $admin->remove_control();

    $admin->add_help_tab();
    $admin->get_help_tab();
    $admin->remove_help_tab();
}
```
