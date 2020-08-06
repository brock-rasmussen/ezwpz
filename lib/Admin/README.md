# Admin API
The EZWPZ Admin API builds off the [Settings API](https://developer.wordpress.org/plugins/settings/settings-api/) and the [Administration Menus API](https://developer.wordpress.org/plugins/administration-menus/) to make it easy to quickly add an admin settings page - complete with settings, controls, and help tabs.

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

## Pages
Pages display your sections, fields, controls, and help tabs. It can be used to add a top-level admin page, or a child page. There are several parameters when adding a new page:

### register_page_type()
Register a custom page type for templates you plan on reusing. If it's a one-off page template, you can instantiate the page with just the class instead. See the add_page function for examples.

#### Parameters
__$type__ (_string_) (Required) The page type that will use the class.
__$class__ (_Class_) (Required) The class that extends EZWPZ\Admin\Page.

```php
class MyPage extends EZWPZ\Admin\Page {
    public function render() {}
}
$admin->register_page_type('my-page', MyPage::class);
```

### add_page()
#### Parameters
__$id__ (_EZWPZ\Admin\Page_|_string_) (Required) Page object, or ID.

__$args__ (_array_) Array of properties for the new Page object.
- __$type__ (_string_) The Page type that corresponds to the appropriate Page class. If not provided, or not found, it will use EZWPZ\Admin\Page.
- __$page_title__ (_string_) The text to be displayed in the title tags when the page is selected. Also displayed at the top of the page.
- __$menu_title__ (_string_) The text to be used for the menu.
- __$capability__ (_string_) The capability required for this menu to be displayed to the user. Default 'manage_options'.
- __$icon_url__ (_string_) The URL to the icon to be used for this menu. Can be a base64-encoded SVG using a data URI, the name of a Dashicons class, or 'none' to leave it empty. Not used for child pages.
- __$position__ (_int_) The position in the menu order this item should appear.
- __$parent_slug__ (_string_) The slug for the parent page. Only use this if the page shouldn't be top-level.
- __$submenu_title__ (_string_) The submenu title to be used for top-level pages that have sub-pages. Not used for child pages.

#### Return
Returns the EZWPZ\Admin\Page object created.

```php
$admin->add_page('page_id', [
    'page_title' => __('Page Title', 'my_plugin_textdomain'),
    'menu_title' => __('Menu Title', 'my_plugin_textdomain'),
    'submenu_title' => __('Settings', 'my_plugin_textdomain'),
    'icon_url' => 'dashicons-search',
]);

// Child page.
$admin->add_page('child_page_id', [
    'page_title' => __('Child Page Title', 'my_plugin_textdomain'),
    'menu_title' => __('Child Page', 'my_plugin_textdomain'),
    'parent_slug' => 'page_id',
]);

// Page initialization with class.
$admin->add_page(new EZWPZ\Admin\Page('class_page_id', [
    'page_title' => __('Class Page Title', 'my_plugin_textdomain'),
    'menu_title' => __('Class Page', 'my_plugin_textdomain'),
    'parent_slug' => 'page_id',
]));

// Custom page type.
$admin->add_page('my_page_id', [
    'type' => 'my-page',
    'page_title' => __('Post Table Page Title', 'my_plugin_textdomain'),
    'menu_title' => __('Post Table Page', 'my_plugin_textdomain'),
    'parent_slug' => 'page_id',
]);
```

### get_page()
#### Parameters
__$id__ (_string_) (Required) Page ID.

#### Return
Returns the EZWPZ\Admin\Page object or false if it doesn't exist.

```php
$admin->get_page('page_id');
```

### remove_page()
#### Parameters
__$id__ (_string_) (Required) Page ID.

#### Return
Returns true if the page was removed. False if it didn't exist.

```php
$admin->remove_page('page_id');
```

### Hooks
Instead of trying to find a plugin page's hook suffix, EZWPZ creates a couple different actions you can tie into that use the Page's ID instead.

#### ezwpz_admin_page_load-{$page_id}
This will fire on the `load-{$hook_suffix}` action.

#### ezwpz_admin_page_enqueue-{$page_id}
This will fire on the `admin_enqueue_scripts` action, but only on the specified page. Use this hook to enqueue scripts and styles.

## Sections
Sections help group your Fields and Controls, and provide an opportunity to describe the purpose of the Fields and Controls they contain.

### register_section_type()
Register a custom section type for templates you plan on reusing. If it's a one-off section template, you can instantiate the section with just the class instead. See the add_section function for examples.

#### Parameters
__$type__ (_string_) (Required) The section type that will use the class.
__$class__ (_Class_) (Required) The class that extends EZWPZ\Admin\Section.

```php
class MySection extends EZWPZ\Admin\Section {
    public function render() {}
}
$admin->register_page_type('my-section', MySection::class);
```

### add_section()
#### Parameters
__$id__ (_EZWPZ\Admin\Section_|_string_) (Required) Section object, or ID.

__$args__ (_array_) Array of properties for the new Section object.
- __$type__ (_string_) The Section type that corresponds to the appropriate Section class. If not provided, or not found, it will use EZWPZ\Admin\Section.
- __$title__ (_string_) Formatted title of the section. Shown as the heading for the section.
- __$page__ (_string_) The setting page id/slug on which to show the section.
- __$priority__ (_int_) The position on the page this section should appear. Default 10.
- __$description__ (_string_) The section description that appears below the section title.

#### Return
Returns the EZWPZ\Admin\Section object created.

```php
$admin->add_section('section_id', [
    'page' => 'page_id',
    'title' => __('Section Title', 'my_plugin_textdomain'),
    'description' => __('The section description can provide a quick intro to what the user will be filling out here.', 'my_plugin_textdomain'),
]);

// Section initialization with class.
$admin->add_section(new EZWPZ\Admin\Section('class_section_id', [
    'page' => 'page_id',
    'title' => __('Class Section Title', 'my_plugin_textdomain'),
    'description' => __('The section description can provide a quick intro to what the user will be filling out here.', 'my_plugin_textdomain'),
]));

// Custom section type.
$admin->add_section('my_section_id', [
    'type' => 'my-section',
    'page' => 'page_id',
    'title' => __('Custom Section Title', 'my_plugin_textdomain'),
    'description' => __('The section description can provide a quick intro to what the user will be filling out here.', 'my_plugin_textdomain'),
]);
```

### get_section()
#### Parameters
__$id__ (_string_) (Required) Section ID.

#### Return
Returns the EZWPZ\Admin\Section object or false if it doesn't exist.

```php
$admin->get_section('section_id');
```

### remove_section()
#### Parameters
__$id__ (_string_) (Required) Section ID.

#### Return
Returns true if the section was removed. False if it didn't exist.

```php
$admin->remove_section('section_id');
```

## Fields
Fields contain your Controls.

### register_field_type()
Register a custom field type for templates you plan on reusing. If it's a one-off field template, you can instantiate the field with just the class instead. See the add_field function for examples.

#### Parameters
__$type__ (_string_) (Required) The field type that will use the class.
__$class__ (_Class_) (Required) The class that extends EZWPZ\Admin\Field.

```php
class MyField extends EZWPZ\Admin\Field {
    public function render() {}
}
$admin->register_page_type('my-field', MyField::class);
```

### add_field()
#### Parameters
__$id__ (_EZWPZ\Admin\Field_|_string_) (Required) Field object, or ID.

__$args__ (_array_) Array of properties for the new Field object.
- __$type__ (_string_) The Field type that corresponds to the appropriate Field class. If not provided, or not found, it will use EZWPZ\Admin\Field.
- __$title__ (_string_) Formatted title of the field. Shown as the label for the field during output.
- __$page__ (_string_) The settings page id/slug on which to show the field.
- __$section__ (_string_) The id of the section on which to show the field.
- __$class__ (_string_) The CSS class to be added to the `<tr>` element when the field is output.
- __$priority__ (_int_) The position on the page this field should appear. Default 10.

#### Return
Returns the EZWPZ\Admin\Field object created.

```php
$admin->add_field('field_id', [
    'page' => 'page_id',
    'section' => 'section_id',
    'title' => __('Field Title', 'my_plugin_textdomain'),
]);

// Field initialization with class.
$admin->add_field(new EZWPZ\Admin\Section('class_field_id', [
    'page' => 'page_id',
    'section' => 'section_id',
    'title' => __('Class Field Title', 'my_plugin_textdomain'),
]));

// Custom field type.
$admin->add_field('my_field_id', [
    'type' => 'my-field',
    'page' => 'page_id',
    'section' => 'section_id',
    'title' => __('Custom Field Title', 'my_plugin_textdomain'),
]);
```

### get_field()
#### Parameters
__$id__ (_string_) (Required) Field ID.

#### Return
Returns the EZWPZ\Admin\Field object or false if it doesn't exist.

```php
$admin->get_field('field_id');
```

### remove_field()
#### Parameters
__$id__ (_string_) (Required) Field ID.

#### Return
Returns true if the field was removed. False if it didn't exist.

```php
$admin->remove_field('field_id');
```

## Control

### register_control_type()
Register a custom control type for control templates you plan on reusing. If it's a one-off control template, you can instantiate the control with just the class instead. See the add_control function for examples.

There are already four custom Control types registered:

- color
- posts
- terms
- users

#### Parameters
__$type__ (_string_) (Required) The control type that will use the class.
__$class__ (_Class_) (Required) The class that extends EZWPZ\Admin\Control.

```php
class MyControl extends EZWPZ\Admin\Control {
    public function render() {}
}
$admin->register_page_type('my-control', MyControl::class);
```

### add_control()
#### Parameters
__$id__ (_EZWPZ\Admin\Control_|_string_) (Required) Control object, or ID.

__$args__ (_array_) Array of properties for the new Control object.
- __$type__ (_string_) The Control type that corresponds to the appropriate Control class. If not provided, or not found, it will use EZWPZ\Admin\Control.
- __$page__ (_string_) The settings page id/slug on which to show the control.
- __$section__ (_string_) The id of the section on which to show the control.
- __$field__ (_string_) The id of the field on which to show the control.
- __$description__ (_string_) Help text displayed with the control.
- __$sanitize_callback__ (_string_) Function to sanitize the control value. Default depends on field `$type`:
    * _email_ - sanitize_email
    * _number_ - intval
    * _richtext_ - wp_filter_post_kses
    * _textarea_ - sanitize_textarea_field
    * _url_ - esc_url_raw
    * Default - sanitize_text_field
- __$show_in_rest__ (_bool_) Whether data associated with this control should be included in the REST API. Default false.
- __$default__ (_string_) Default value when calling `get_option()`.
- __$type__ (_string_) The control type. Supports 'checkbox', 'checkboxes', 'radio', 'richtext', 'select', and 'textarea'. Defaults to 'text', and will by default support any HTML input type.
- __$label__ (_string_) Label for the control.
- __$choices__ (_array_) List of choices for 'checkboxes', 'radio', 'select', and any HTMl input type that supports a datalist. Attributes names are the keys and values are the values. Datalists will ignore the keys.
- __$multiple__ (_bool_) Whether to allow multiple selections. Default false. Forced to true for 'checkboxes' type inputs.
- __$input_attrs__ (_array_) List of custom input attributes for control output, where attribute names are the keys and values are the values.
- __$priority__ (_int_) The position in the field this control should appear. Default 10.

#### Return
Returns the EZWPZ\Admin\Control object created.

#### Included Custom Controls

##### Posts, Terms, Users
Controls to create a checkbox list, radio list, or a select element containing posts, terms, or users. If the type is not explicitly set, it will default to either 'checkboxes', 'radio', or 'select' depending on the amount of options retrieved from the query and the multiple argument.

Additional/adjusted $args:
- __$type__ (_string_) Can only be either 'checkboxes', 'radio', or 'select'. If not set, it will be automatically determined based on the $multiple argument and the amount of choices.
- __$query__ (_array_) A query object to be passed to get_posts, get_terms, or get_users depending on the control type.

```php
$admin->add_control('control_id', [
    'page' => 'page_id',
    'section' => 'section_id',
    'field' => 'field_id',
    'label' => __('Control Label', 'my_plugin_textdomain'),
]);

// Color control.
$admin->add_control('color_control_id', [
    'type' => 'color',
    'page' => 'page_id',
    'section' => 'section_id',
    'field' => 'field_id',
    'label' => __('Control Label', 'my_plugin_textdomain'),
]);

// Posts control.
$admin->add_control('posts_control_id', [
    'type' => 'posts',
    'page' => 'page_id',
    'section' => 'section_id',
    'field' => 'field_id',
    'label' => __('Posts Control Label', 'my_plugin_textdomain'),
    'query' => [
        'post_type' => 'page',
    ],
]);

// Terms control.
$admin->add_control('terms_control_id', [
    'type' => 'terms',
    'page' => 'page_id',
    'section' => 'section_id',
    'field' => 'field_id',
    'label' => __('Terms Control Label', 'my_plugin_textdomain'),
    'query' => [
        'taxonomy' => 'post_tag',
    ],
]);

// Users control.
$admin->add_control('users_control_id', [
    'type' => 'users',
    'page' => 'page_id',
    'section' => 'section_id',
    'field' => 'field_id',
    'label' => __('Users Control Label', 'my_plugin_textdomain'),
    'multiple' => true,
    'query' => [
        'role__in' => ['administrator'],
    ],
]);

// Control initialization with class.
$admin->add_control(new EZWPZ\Admin\Control('class_control_id', [
    'page' => 'page_id',
    'section' => 'section_id',
    'field' => 'field_id',
    'label' => __('Class Control Label', 'my_plugin_textdomain'),
]));

// Custom control type.
$admin->add_control('my_control_id', [
    'type' => 'my-control',
    'page' => 'page_id',
    'section' => 'section_id',
    'field' => 'field_id',
    'label' => __('Custom Control Label', 'my_plugin_textdomain'),
]);
```

### get_control()
#### Parameters
__$id__ (_string_) (Required) Control ID.

#### Return
Returns the EZWPZ\Admin\Control object or false if it doesn't exist.

```php
$admin->get_control('control_id');
```

### remove_control()
#### Parameters
__$id__ (_string_) (Required) Control ID.

#### Return
Returns true if the control was removed. False if it didn't exist.

```php
$admin->remove_control('control_id');
```

## Help Tabs
Help tabs can provide additional instructions, tips, links, etc.

### add_help_tab()
#### Parameters
__$id__ (_EZWPZ\Admin\HelpTab_|_string_) (Required) Help tab object, or ID.

__$args__ (_array_) Array of properties for the new Help tab object.
- __$page__ (_string_) The setting page id/slug on which to show the help tab.
- __$title__ (_string_) Title for the tab.
- __$content__ (_string_) Help tab content in plain text.
- __$callback__ (_string_) A callback to generate the tab content.
- __$priority__ (_int_) The position this help tab should appear. Default 10.

#### Return
Returns the EZWPZ\Admin\HelpTab object created.

```php
$admin->add_help_tab('help_tab_id', [
    'page' => 'page_id',
    'title' => __('Help tab Title', 'my_plugin_textdomain'),
    'content' => __('The help tab content can provide links, tips, help information, etc. for the page.', 'my_plugin_textdomain'),
]);
```

### get_help_tab()
#### Parameters
__$id__ (_string_) (Required) Help tab ID.

#### Return
Returns the EZWPZ\Admin\HelpTab object or false if it doesn't exist.

```php
$admin->get_help_tab('help_tab_id');
```

### remove_help_tab()
#### Parameters
__$id__ (_string_) (Required) Help tab ID.

#### Return
Returns true if the help tab was removed. False if it didn't exist.

```php
$admin->remove_help_tab('help_tab_id');
```

