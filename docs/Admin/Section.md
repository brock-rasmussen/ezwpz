# Sections
Sections help group your Fields and Controls, and also provide an opportunity to describe the purpose of the Fields and Controls the contain.

## add_section()
### Parameters
__$id__ (_EZWPZ\Admin\Section_|_string_) (Required) Section object, or ID.

__$args__ (_array_) Array of properties for the new Section object.
- __$title__ (_string_) Formatted title of the section. Shown as the heading for the section.
- __$page__ (_string_) The setting page id/slug on which to show the section.
- __$priority__ (_int_) The position on the page this section should appear. Default 10.
- __$description__ (_string_) The section description that appears below the section title.

### Return
Returns the EZWPZ\Admin\Section object created.

```php
$admin->add_section('section_id', [
    'page' => 'page_id',
    'title' => __('Section Title', 'my_plugin_textdomain'),
    'description' => __('The section description can provide a quick intro to what the user will be filling out here.', 'my_plugin_textdomain'),
]);
```

## get_section()
### Parameters
__$id__ (_string_) (Required) Section ID.

### Return
Returns the EZWPZ\Admin\Section object or false if it doesn't exist.

```php
$admin->get_section('section_id');
```

## remove_section()
### Parameters
__$id__ (_string_) (Required) Section ID.

### Return
Returns true if the section was removed. False if it didn't exist.

```php
$admin->remove_section('section_id');
```
