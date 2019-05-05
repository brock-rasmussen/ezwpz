# EZWPZ\Settings

## `do_settings_controls(string $page, string $section, string $field)`

Output the EZWPZ controls added to a field.

### Parameters

- **$page**<br>
- **$section**<br>
- **$field**<br>

## `render_page()`

Output markup for a basic settings page.

## `Manager::add_setting(Setting|string $id, array $args = [])`

### Parameters

- **$id**<br>
(Setting | string) (Required) Setting object or ID.
- **$args**<br>
(array) (Optional) Array of properties for the new Setting.
  - **'page'**<br>
  (string) <br>
  Default value: 'general'
  - **'type'**<br>
  (string) <br>
  Default value: 'string'
  - **'description'**<br>
  (string) <br>
  Default value: ''
  - **'sanitize_callback'**<br>
  (callable) <br>
  Default value: null
  - **'show_in_rest'**<br>
  (bool) <br>
  Default value: false
  - **'default'**<br>
  (mixed) <br>
  Default value: null

## `Manager::get_setting(string $id)`

Get the Setting object for a setting added with EZWPZ.

### Parameters

- **$id**<br>
(string) (Required) Setting ID.

### Return

(Setting | false) The Setting object, or false if the setting doesn't exist.

## `Manager::remove_setting(string $id)`

Remove a setting added with EZWPZ.

### Parameters

- **$id**<br>
(string) (Required) Setting ID.

## `Manager::add_section(Section|string $id, array $args = [])`

### Parameters

- **$id**<br>

## `Manager::get_section(string $page, string $id)`

### Parameters

- **$page**<br>
(string) (Required) Page ID.
- **$id**<br>
(string) (Required) Section ID.

### Return

(Section | false) The Section object, or false if the section doesn't exist.

## `Manager::remove_section(string $page, string $id)`

Remove a section added with EZWPZ.

### Parameters

- **$page**<br>
(string) (Required) Page ID.
- **$id**<br>
(string) (Required) Section ID.

## `Manager::add_field(Field|string $id, array $args = [])`

### Parameters

- **$id**<br>

## `Manager::get_field(string $page, string $section, string $id)`

### Parameters

- **$page**<br>
(string) (Required) Page ID.
- **$section**<br>
(string) (Required) Section ID.
- **$id**<br>
(string) (Required) Field ID.

### Return

(Field | false) The Field object, or false if the field doesn't exist.

## `Manager::remove_field(string $page, string $section, string $id)`

Remove a field added with EZWPZ.

### Parameters

- **$page**<br>
(string) (Required) Page ID.
- **$section**<br>
(string) (Required) Section ID.
- **$id**<br>
(string) (Required) Field ID.

## `Manager::add_control(Control|string $id, array $args = [])`

### Parameters



## `Manager::get_control(string $page, string $section, string $field, string $id)`

### Parameters

- **$page**<br>
(string) (Required) Page ID.
- **$section**<br>
(string) (Required) Section ID.
- **$field**<br>
(string) (Required) Field ID.
- **$id**<br>
(string) (Required) Control ID.

### Return

(Control | false) The Control object, or false if the control doesn't exist.

## `Manager::remove_control(string $page, string $section, string $field, string $id)`

Remove a control added with EZWPZ.

### Parameters

- **$page**<br>
(string) (Required) Page ID.
- **$section**<br>
(string) (Required) Section ID.
- **$field**<br>
(string) (Required) Field ID.
- **$id**<br>
(string) (Required) Control ID.
