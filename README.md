# Enum Tools for Laravel
version 1.0

A lightweight Laravel package that simplifies working with PHP native enums: readable labels, easy select options, and validation rules

---

## Installation

Supports **Laravel 10, 11, 12**

Install via Composer:
```bash
composer require timhale2104/enum-tools
```

## Features
* Add readable labels to PHP enums
* Generate value => label select options
* Validate request input against enum values
* Optional localization support

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
You'll get:
```php
UserStatus::ACTIVE->label(); // "Active"
UserStatus::labels();        // ['Active', 'Inactive', 'Blocked']
UserStatus::values();        // ['active', 'inactive', 'blocked']
UserStatus::casesForSelect();
// [
//   ['label' => 'Active', 'value' => 'active'],
//   ...
// ]
```

### 2. Optional Localization Support
Add translations to lang/en/enums.php:
```php
return [
    'UserStatus.active' => 'Custom Active',
    'UserStatus.inactive' => 'Custom Inactive',
    'UserStatus.blocked' => 'Custom Blocked',
];
```

The label() method will use __('enums.UserStatus.caseName') if available

### 3. Validation Rule
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