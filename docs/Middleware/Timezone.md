# Middleware/Timezone

A CakePHP middleware class to handle timezone a bit easier.

## What does it do?

The Middleware checks client timezone using geoplugin.net API and store it into cookie for a time mentionned into config cookieTime key. In case of API call failed, timezone mentionned into config defaultTimezone key will be stored.

The Middleware checks timezone stored in cookie and write it into config 'App.timezone' key. But, if there is a user preference ('Auth.User.timezone' key in session) this is the timezone that'll be write into config.

The middleware provide to us client's timezone just by reading Configure class:

```php
Configure::read('App.timezone');
```

## Set up

In your Application.php file add GeoPlugin and TimezoneMiddleware class from plugin:

```php
use Timezone\Middleware\GeoPlugin;
use Timezone\Middleware\TimezoneMiddleware;
```

then add the middleware into middleware method:

```php
public function middleware($middlewareQueue)
{
    $middlewareQueue
    	// Other middleware ...
    	->add(new TimezoneMiddleware(new GeoPlugin(), [
    		// Config here.
    	]));
}
```

## Config

- 'defaultTimezone' => 'UTC' // Default timezone to use in case API call fails.
- 'cookieTime' => 12 // Cookie storing time in hour.

## Trick

Having the client timezone into app config you can even now in your AppController:
- Define one protected variable containing client timezone, then you can have access to client timezone in all your controller.
- Set this protected variable into beforeRender method, then you can have access to client timezone in all you view.

```php
//...
use Cake\Core\Configure;

class AppController extends Controller
{
    /**
     * Client timezone.
     * 
     * @var string
     */
    protected $timezone;

    /**
     * initialize
     * @return void
     */
    public function initialize()
    {
        // ...
        $this->timezone = Configure::read('App.timezone');
    }

    /**
     * beforeRender
     * @param \Cake\Event\Event $event An Event instance
     * @return void
     */
    public function beforeRender(Event $event)
    {
        $timezone = $this->timezone;
        $this->set(compact('timezone'));
    }
}
```