<?php

namespace Timezone\Middleware;

use Cake\Core\InstanceConfigTrait;
use Cake\Http\ServerRequest;
use Psr\Http\Message\ResponseInterface;
use Timezone\Middleware\GeoPlugin;
use Cake\Core\Configure;
use DateTimeZone;

/**
 * A timezone middleware for CakePHP to easily deal with timezone.
 *
 * It takes client timezone using GeoPlugin class (in case it failed middleware use timezone specified in config 'defaultTimezone' key), then this client timezone is stored into cookie for time specified into config 'cookieTime' key. If one cookie named timezone is already present in the request of course middleware do not call GeoPlugin Api and store timezone again.
 * 
 * If there is on user preference's timezone. Basically, if there is in session Auth.User.timezone key and that's not null, then this is this timezone that'll be used.
 *
 * Middleware write in Configure timezone used into App.timezone key.
 *
 * @author PERRIN Jean-Charles
 * @license MIT
 * @link https://github.com/JeanValJeann/cakephp-timezone
 */
class TimezoneMiddleware
{
    use InstanceConfigTrait;

    /**
     * Default timezone to use in case API call fails.
     * @var string
     */
    private $defaultTimezone = 'UTC';

    /**
     * Cookie storing time in hour.
     * @var int
     */
    private $cookieTime = 12;

    /**
     * Instance of GeoPlugin class.
     * @var \Timezone\Middleware\GeoPlugin
     */
    private $geoPlugin;

    /**
     * Default settings.
     *
     * - `defaultTimezone`: defaults to 'UTC' - Default timezone to use in case API call fails.
     * - `cookieTime`: defaults to 12 - Cookie storing time in hour.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'defaultTimezone' => 'UTC', // Default timezone to use in case API call fails.
        'cookieTime' => 12 // Cookie storing time in hour.
    ];

    /**
     * Constructor.
     *
     * @param \Timezone\Middleware\GeoPlugin $geoPlugin - Instance of GeoPlugin class.
     * @param array $config Settings for the filter.
     */
    public function __construct(GeoPlugin $geoPlugin, $config = [])
    {
        $this->setConfig($config);

        if (in_array($this->getConfig('defaultTimezone'), DateTimeZone::listIdentifiers())) {
            $this->timezone = $this->getConfig('defaultTimezone');
        }
        $this->cookieTime = '+' . $this->cookieTime . ' hours';
        if (is_int($this->getConfig('cookieTime'))) {
            $this->cookieTime = '+' . $this->getConfig('cookieTime') . ' hours';
        }
        $this->geoPlugin = $geoPlugin;
    }

    /**
     * __invoke method.
     * 
     * It takes client timezone using GeoPlugin class (in case it failed middleware use timezone specified in config 'defaultTimezone' key), then this client timezone is stored into cookie for time specified into config 'cookieTime' key. If one cookie named timezone is already present in the request of course middleware do not call GeoPlugin Api and store timezone again.
     * 
     * If there is on user preference's timezone. Basically, if there is in session Auth.User.timezone key and that's not null, then this is this timezone that'll be used.
     *
     * Middleware write in Configure timezone used into App.timezone key.
     *
     * @param \Cake\Http\ServerRequest $request The request.
     * @param \Psr\Http\Message\ResponseInterface $response The response.
     * @param callable $next Callback to invoke the next middleware.
     *
     * @return \Psr\Http\Message\ResponseInterface A response
     */
    public function __invoke(ServerRequest $request, ResponseInterface $response, $next)
    {
        $timezone = $this->timezone;
        if (!$request->getCookie('timezone')) {

            if ($clientTimezone = $this->geoPlugin->getClientTimezone()) {
                $timezone = $clientTimezone;
            }
            $response = $response->withCookie('timezone', [
                'value' => $timezone,
                'expire' => strtotime($this->cookieTime, time())
            ]);
        } else {
            $timezone = $request->getCookie('timezone');
        }

        if ($request->getSession()->read('Auth.User.timezone')) {
            $timezone = $request->getSession()->read('Auth.User.timezone');
        }

        Configure::write('App.timezone', $timezone);

        return $next($request, $response);
    }
}