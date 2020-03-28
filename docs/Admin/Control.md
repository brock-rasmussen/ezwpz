# Control

## add_control()
### Parameters
__$id__ (_EZWPZ\Admin\Control_|_string_) (Required) Control object, or ID.

__$args__ (_array_) Array of properties for the new Control object.
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

### Return
Returns the EZWPZ\Admin\Control object created.

```php
$admin->add_control('control_id', [
    'page' => 'page_id',
    'section' => 'section_id',
    'field' => 'field_id',
    'label' => __('Control Label', 'my_plugin_textdomain'),
]);
```

## get_control()
### Parameters
__$id__ (_string_) (Required) Control ID.

### Return
Returns the EZWPZ\Admin\Control object or false if it doesn't exist.

```php
$admin->get_control('control_id');
```

## remove_control()
### Parameters
__$id__ (_string_) (Required) Control ID.

### Return
Returns true if the control was removed. False if it didn't exist.

```php
$admin->remove_control('control_id');
```

## Custom Controls
There are four custom control types.

### Color
A color picker control. It has not additional $args to be aware of.

```php
$admin->add_control(EZWPZ\Admin\Control\Color('control_id', [
    'page' => 'page_id',
    'section' => 'section_id',
    'field' => 'field_id',
    'label' => __('Control Label', 'my_plugin_textdomain'),
]));
```

### SelectPosts, SelectTerms, SelectUsers
Controls to create a checkbox list, radio list, or a select element containing posts, terms, or users.

#### $args Parameters Adjustments
__$query__ (_array_) A query object to be passed to get_posts, get_terms, or get_users depending on the control type.

__$type__ (_string_) Can only be either 'checkboxes', 'radio', or 'select'. If not set, it will be automatically determined based on the $multiple argument and the amount of choices.

```php
$admin->add_control(EZWPZ\Admin\Control\SelectPosts('posts_control_id', [
    'page' => 'page_id',
    'section' => 'section_id',
    'field' => 'field_id',
    'label' => __('Posts Control Label', 'my_plugin_textdomain'),
    'query' => [
        'post_type' => 'page',
    ],
]));
$admin->add_control(EZWPZ\Admin\Control\SelectTerms('posts_control_id', [
    'page' => 'page_id',
    'section' => 'section_id',
    'field' => 'field_id',
    'label' => __('Terms Control Label', 'my_plugin_textdomain'),
    'query' => [
        'taxonomy' => 'post_tag',
    ],
]));
$admin->add_control(EZWPZ\Admin\Control\SelectUsers('posts_control_id', [
    'page' => 'page_id',
    'section' => 'section_id',
    'field' => 'field_id',
    'label' => __('Users Control Label', 'my_plugin_textdomain'),
    'multiple' => true,
    'query' => [
        'role__in' => ['administrator'],
    ],
]));
```
