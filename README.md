# Enum Tools for Laravel
**version 1.4.0**

A lightweight Laravel package that simplifies working with native PHP enums: readable labels, localized API responses, select options, and validation rules.

---

## Features
- Add human-friendly labels to PHP enums
- Generate value/label pairs for select menus
- Use enums in API responses with localization
- Validate request input against enum values
- Support for Laravel 10, 11, 12
- Easy-to-integrate `EnumController` with built-in protection

## Installation

Supports **Laravel 10, 11, 12**

Install via Composer:
```bash
composer require timhale2104/enum-tools
```

## Usage

### 1. Add HasLabel trait to your enum

```php
use EnumTools\Traits\HasLabel;

enum UserStatus: string
{
    use HasLabel;

    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case BLOCKED = 'blocked';
}
```

---

### 2. Available Methods
```php
UserStatus::ACTIVE->label(); // "Active"
UserStatus::labels();        // ['Active', 'Inactive', 'Blocked']
UserStatus::values();        // ['active', 'inactive', 'blocked']
UserStatus::casesForSelect();
// [
//   ['label' => 'Active', 'value' => 'active'],
//   ...
// ]
UserStatus::toArray(); // alias for casesForSelect()
```

---

### 3. Optional Localization
Add translation strings to `lang/en/enums.php`:
```php
return [
    'UserStatus.active' => 'Custom Active',
    'UserStatus.inactive' => 'Custom Inactive',
    'UserStatus.blocked' => 'Custom Blocked',
];
```
`label` will automatically use `__('enums.UserStatus.active')` if it exists

---

### 4. Enum Value Validation
Use the custom validation rule to check if a value is part of a specific enum:
```php
use EnumTools\Rules\EnumValueRule;

$request->validate([
    'status' => ['required', new EnumValueRule(UserStatus::class)],
]);
```
You’ll get a default validation message like:
``The selected status is not valid.
``

You can customize it via lang/en/validation.php:
```php
validation' => [
    'enum' => 'I guess the selected :attribute is not valid',
]
```

---

### 5. Enum Cast for Eloquent

Cast enum values to and from database automatically in Eloquent models.

`!!! Requires your enum to extend BackedEnum and use the HasLabel trait !!!`

Add the cast like this:
```php
use EnumTools\Casts\EnumToolsCast;
use App\Enums\UserStatus;

protected $casts = [
    'status' => EnumToolsCast::for(UserStatus::class),
];
```

You can now work with enums directly:
```php
$user = User::create(['status' => UserStatus::ACTIVE]);

$user->status instanceof UserStatus; // true
$user->status->label(); // "Active" (or translated)
```

---

## API Support
This package includes an API-ready controller to expose enums to your frontend as JSON with localization

**API Response Example**
```http request
GET /api/enums/user-status?lang=uk
```

```json
{
  "success": true,
  "data": [
    { "value": "active", "label": "Активний" },
    { "value": "inactive", "label": "Неактивний" },
    { "value": "blocked", "label": "Заблокований" }
  ]
}
```

---

### Protecting Enums

Only enums listed in `allowed_enums` are accessible via API.
Publish the config:
```bash
php artisan vendor:publish --provider="EnumTools\EnumToolsServiceProvider" --tag=config
```
In `config/enum_tools.php`:
```php
return [
    'namespace' => 'App\\Enums',

    'allowed_enums' => [
        'UserStatus',
    ],

    'enable_locale_middleware' => true,
    'supported_locales' => ['en', 'uk', 'ru'],
];
```

---

### Register API Route
In your `routes/api.php`:
```php
use EnumTools\Http\Controllers\EnumController;

Route::get('enums/{enum}', EnumController::class);
```
---

### API Localization

By default, the API will auto-detect the language from:
- Query string: `?lang=uk`
- Header: `X-Locale: uk`
- Header: `Accept-Language: uk`

To disable auto-localization:
```php
'enable_locale_middleware' => false,
```
---

### Frontend Usage Example (Axios)
```js
const { data } = await axios.get('/api/enums/user-status?lang=uk');

if (data.success) {
  const options = data.data;
  // [{ value: 'active', label: 'Активний' }, ...]
}
```

---

## API Integration with EnumCollectionResource

If you're building a frontend (Vue, React, etc.), you may want to expose enum values and labels via API.
This package provides a ready-to-use Laravel Resource for that


### EnumCollectionResource

Use `EnumCollectionResource::from()` to convert any enum into a JSON-ready format

**Example**

```php
use EnumTools\Resources\EnumCollectionResource;

return response()->json([
    'success' => true,
    'status' => 200,
    'data' => EnumCollectionResource::from(UserStatus::class),
]);
```

**Output**

```php
[
  { "value": "active", "label": "Active" },
  { "value": "inactive", "label": "Inactive" },
  { "value": "blocked", "label": "Blocked" }
]

```

### Methods

`from(string $enumClass): EnumCollectionResource`

Static factory method that accepts a native PHP enum class and wraps it in a resource collection

Internally, it's just a shortcut for `new EnumCollectionResource(UserStatus::cases())`

`toArray($request): array`

This defines how each enum case will be returned in JSON.
By default, it returns:

```php
[
  'label' => $case->label(), // requires HasLabel trait
  'value' => $case->value,
]
```

If the `label()` method is missing, it falls back to `case->name`

### When to use
* You want a **frontend-friendly API format**
* You're using enums in **select fields, dropdowns, filters**
* You need a **Laravel-native approach** that supports localization

---

### Artisan Generator: `make:enum`


You can generate a new enum class preconfigured for Enum Tools:

```bash
php artisan make:enum UserStatus
```

This will create a file like `app/Enums/UserStatus.php`:

```php
use EnumTools\Traits\HasLabel;
use EnumTools\Attributes\Label;
use EnumTools\Attributes\Color;
use EnumTools\Attributes\Icon;

enum UserStatus: string
{
    use HasLabel;

    #[Label('Приклад')]
    #[Color('green')]
    #[Icon('check-circle')]
    case EXAMPLE = 'example';
}
```

**Example:**
```php
UserStatus::EXAMPLE->label(); // "Приклад"
UserStatus::EXAMPLE->color(); // "green"
UserStatus::EXAMPLE->icon();  // "check-circle"
```

Available immediately after installing the package