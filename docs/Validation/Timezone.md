# Validation/Timezone

A CakePHP validation class to validate timezone field a bit easier.

## What does it do?

The Timezone Validation class helps to validate a timezone field.

## Set up

Timezone validation class can be used for validating timezone model field.

```php
namespace App\Model\Table;

use Timezone\Model\Validation\TimezoneValidation;
use Cake\Validation\Validator;

class EventsTable extends Table
{
    public function validationDefault(Validator $validator)
    {
        $validator->provider('timezone', TimezoneValidation::class);
        $validator->add('timezoneField', 'myCustomRuleNameForTimezone', [
            'rule' => 'valid',
            'provider' => 'timezone'
        ]);
    }
}
```

## Validation method

- valid() to check if valid timezone identifier