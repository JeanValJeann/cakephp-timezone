# CakePHP Timezone Plugin Documentation

## Before installing

According to me to easily handling timezone and datetime you should better set your application's default timezone to 'UTC'. To do it, open your config/app.php file and write it:

```php
'App' => [
    // ...
    'defaultTimezone' => env('APP_DEFAULT_TIMEZONE', 'UTC'),
    // ...
]
```

## Installation

- [Installation](Install.md)

## Documentation

- [Middleware/Timezone](Middleware/Timezone.md)
- [Behavior/Timezone](Behavior/Timezone.md)
- [Validation/Timezone](Validation/Timezone.md)
- [Helper/Timezone](Helper/Timezone.md)

## Contributing

- See [Contributing](Contributing.md) for details.