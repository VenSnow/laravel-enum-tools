# Changelog

All notable changes to this project will be documented in this file.  
This project adheres to [Semantic Versioning](https://semver.org/).

---

## [v1.3.0] - 2025-07-11

### Added
-  **EnumCollectionResource** — Laravel-style `ResourceCollection` for API responses
- Static method `from(string $enumClass)` to easily expose enums in JSON format
- Clean output with `value` and `label` keys
- Supports enums using `HasLabel` (with localization)
-  Documentation updated with usage examples and method reference

---

## [v1.2.0] - 2025-07-09

### Added
- **Built-in locale middleware** to auto-detect language in API requests (`?lang=`, `X-Locale`, or `Accept-Language`)
- Config option `enable_locale_middleware` to enable/disable localization
- Config option `supported_locales` to limit allowed languages
- Improved API responses with consistent JSON structure:
  ```json
  {
    "success": true,
    "data": [...]
  }
  ```
- Cleaner error responses with `status` and `success`
- Updated `EnumController` to manually apply middleware (no need to edit Kernel)

## [v1.1.0] - 2025-07-09

### Added
- `EnumController` to expose enums via `/api/enums/{enum}`
- Config-based `allowed_enums` to protect which enums can be accessed
- `toArray()` method as alias for `casesForSelect()` for frontend usage
- Validation rule `EnumValueRule` for checking enum values in requests
- Improved documentation with examples, validation, and localization support
- Artisan publish support for `config/enum_tools.php` via:
```bash
- php artisan vendor:publish --provider="EnumTools\\EnumToolsServiceProvider" --tag=config
```

## [v1.0.0] - 2025-07-08

### Initial Release

- `HasLabel` trait for enums
- `label()`, `labels()`, `values()`, and `casesForSelect()`
- Localization via `lang/{locale}/enums.php`