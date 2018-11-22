# Helper/Timezone

A CakePHP helper class to display timezone and its offset, get array list of timezone, display datetime text input and timezone select a bit easier.

## What does it do?

The Helper is able to:
 *  - Generate timezone with its current GMT offset or an offset depending of datetime.
 *  - Generate timezone identifiers array list grouped by continent and containing current GMT offset or not.
 *  - Generate input text containing datetime in SQL format in special timezone.
 *  - Generate select containing all timezone identifiers and set a special timezone as default value.

## Set up

In your AppView.php file add Timezone Helper from plugin:

```php
public function initialize()
{
    // ...
	$this->loadHelper('TimezoneDatetime.Timezone', [
		// Config here.
	]);
}
```

## Config

- 'timezone' => 'UTC', // Timezone to use.

## Trick

Use client timezone provided by middleware as timezone config key.

```php
use Cake\Core\Configure;
```

```php
public function initialize()
{
    // ...
	$this->loadHelper('TimezoneDatetime.Timezone', [
		'timezone' => Configure::read('App.timezone')
	]);
}
```

## Usage

### timezoneWithGMTOffset method

It generates timezone identifier name and current GMT offset or GMT offset depending of datetime. To use it:

```php
$this->Timezone->timezoneWithGMTOffset($timezone, $datetime);
```

Parameters:
* string|null $timezone - Timezone to use (if null timezone specified in config will be used).
* string $datetime - Datetime to use to get timezone's GMT offset.

Use case:
```php
echo $this->Timezone->timezoneWithGMTOffset('Europe/Paris'); // Europe/Paris (GMT+02:00)
echo $this->Timezone->timezoneWithGMTOffset('Europe/Paris', '2018-07-15 12:00:00'); // Europe/Paris (GMT+01:00)
```

### timezoneIdentifiersArrayList method

It generates an array list of timezone identifiers grouped by continent and containing current GMT or not. To use it:

```php
$this->Timezone->timezoneIdentifiersArrayList($gmtOffset);
```

Parameters:
* bool $gmtOffset - Add GMT offset after timezone identifier name.

Use case:
```php
$timezoneArrayList = $this->Timezone->timezoneIdentifiersArrayList();
/*
[
	'Africa' => [
    	'Africa/Abidjan' => 'Abidjan (GMT+00:00)',
    	// ...
    ],
    'America' => [
    	'America/Adak' => 'Adak (GMT-10:00)',
    	// ...
    ],
    // ...
    'UTC' => [
    	'UTC' => 'UTC (GMT+00:00)'
    ]
]
*/
$timezones = $this->Timezone->timezoneIdentifiersArrayList(false);
/*
[
	'Africa' => [
    	'Africa/Abidjan' => 'Abidjan)',
    	// ...
    ],
    'America' => [
    	'America/Adak' => 'Adak',
    	// ...
    ],
    // ...
    'UTC' => [
    	'UTC' => 'UTC'
    ]
]
*/
```

### datetime method

It generates text input containing datetime in SQL format in special timezone. To use it:

```php
$this->Timezone->datetime($fieldName, $options, $timezone);
```

Parameters:
* string $fieldName - Text input name.
* array $options - Text input options.
* string|null $timezone - Timezone to use to display datetime (if null timezone specified in config will be used).

Use case:
```php
echo $this->Timezone->datetime('published'); // Text input having no option and containing datetime in SQL format using config timezone
echo $this->Timezone->datetime('published', [], 'Europe/Paris'); // Text input containing datetime in SQL format using 'Europe/Paris' timezone
```

### select method

It generates select containing all timezone identifiers and set a special timezone as default value.

```php
echo $this->Timezone->select($fieldName, $attributes, $timezone);
```

Parameters:
* string $fieldName - Select name.
* array $attributes - Select's attributes.
* string|null $timezone - Timezone to use as default selected value (if null timezone specified in config will be used).

Use case:
```php
echo $this->Timezone->select('published_timezone'); // Timezone select having no attribute and config timezone as default value
echo $this->Timezone->select('published_timezone', [], 'Europe/Paris'); // Timezone select having 'Europe/Paris' timezone as default value
```




