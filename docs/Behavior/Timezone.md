# Behavior/Timezone

A CakePHP behavior class to convert datetime from one timezone to another one a bit easier.

## What does it do?

The Behavior converts datetime field from one timezone to another one (both specified in config 'fromTimezone' key and config 'toTimezone' key). In case there is a field into set of data named field to be converted's name + suffix (specified in config 'suffix' key) and representing a valid timezone identifier, then this is the timezone identifier that'll be used to convert the datetime from. that's a way to overwrite config 'fromTimezone' key and deal with physical futur datetime.
  
Note that datetime that has to be convert must be in one of the three format supported by CakePHP Time class wich are the same of native PHP DateTime class and are:
- Y-m-d H:i:s (SQL format)
- m/d/y H:i:s (American format)
- d-m-y H:i:s (European format)

## Set up

In your Table file add Timezone Behavior from plugin:

```php
public function initialize(array $config)
{
    // ...
	$this->addBehavior('Timezone.Timezone', [
		// Config here.
	]);
}
```

## Config

- 'fromTimezone' => 'UTC', // Timezone to convert datetime from.
- 'toTimezone' => 'UTC', // Timezone to convert datetime to.
- 'fields' => [], // Unindexed array of datetime field names to be converted.
- 'suffix' => '_timezone' // Allow to overwrite config 'fromTimezone' key and deal with physical futur datetime.

## Trick

Use client timezone provided by middleware as fromTimezone config key.

```php
use Cake\Core\Configure;
```

```php
$this->addBehavior('Timezone.Timezone', [
	'fromTimezone' => Configure::read('App.timezone'),
	// ...
]);
```