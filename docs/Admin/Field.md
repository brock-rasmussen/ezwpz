# Fields
Fields contain your Controls.

## add_field()
### Parameters
__$id__ (_EZWPZ\Admin\Field_|_string_) (Required) Field object, or ID.

__$args__ (_array_) Array of properties for the new Field object.
- __$title__ (_string_) Formatted title of the field. Shown as the label for the field during output.
- __$page__ (_string_) The settings page id/slug on which to show the field.
- __$section__ (_string_) The id of the section on which to show the field.
- __$class__ (_string_) The CSS class to be added to the `<tr>` element when the field is output.
- __$priority__ (_int_) The position on the page this field should appear. Default 10.

### Return
Returns the EZWPZ\Admin\Field object created.

```php
$admin->add_field('field_id', [
    'page' => 'page_id',
    'section' => 'section_id',
    'title' => __('Field Title', 'my_plugin_textdomain'),
]);
```

## get_field()
### Parameters
__$id__ (_string_) (Required) Field ID.

### Return
Returns the EZWPZ\Admin\Field object or false if it doesn't exist.

```php
$admin->get_field('field_id');
```

## remove_field()
### Parameters
__$id__ (_string_) (Required) Field ID.

### Return
Returns true if the field was removed. False if it didn't exist.

```php
$admin->remove_field('field_id');
```
