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
