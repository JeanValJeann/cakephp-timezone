<?php
namespace Timezone\Test\TestCase\Middleware;

use Timezone\Middleware\TimezoneMiddleware;
use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\TestSuite\TestCase;
use Timezone\Middleware\GeoPlugin;
use Cake\Core\Configure;
use Cake\Http\Session;

/**
 * TimezoneMiddleware test.
 */
class TimezoneMiddlewareTest extends TestCase
{

    /**
     * setup.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->session = new Session();
        $this->geoPlugin = new GeoPlugin();
        $this->config = [];
        $this->request = new ServerRequest();
        $this->response = new Response();
        $this->next = function ($req, $res) {
            return $res;
        };
    }

    /**
     * test.
     *
     * @return void
     */
    public function test()
    {
        $middleware = new TimezoneMiddleware($this->geoPlugin, $this->config);
        $response = $middleware($this->request, $this->response, $this->next);

        $ip = file_get_contents('http://www.geoplugin.com/ip.php');
        $url = 'http://www.geoplugin.net/php.gp?ip=' . $ip;
        $geopluginresponse = unserialize(file_get_contents($url));

        $timezone = $geopluginresponse['geoplugin_timezone'];

        $this->assertEquals($timezone, Configure::read('App.timezone'));
        $this->assertEquals($timezone, $response->getCookie('timezone')['value']);
    }

    /**
     * testWhenGeoPluginGetClientTimezoneReturnsNull.
     *
     * @return void
     */
    public function testWhenGeoPluginGetClientTimezoneReturnsNull()
    {
        $this->geoPlugin = $this->getMockBuilder('Timezone\Middleware\GeoPlugin')
            ->setMethods(['getClientTimezone'])
            ->getMock();

        $this->geoPlugin->expects($this->once())
            ->method('getClientTimezone')
            ->will($this->returnValue(null));

        $middleware = new TimezoneMiddleware($this->geoPlugin, ['defaultTimezone' => 'Europe/Paris']);
        $response = $middleware($this->request, $this->response, $this->next);

        $this->assertEquals('Europe/Paris', Configure::read('App.timezone'));
        $this->assertEquals('Europe/Paris', $response->getCookie('timezone')['value']);
    }

    /**
     * testWithTimezoneInCookie.
     *
     * @return void
     */
    public function testWithTimezoneInCookie()
    {
        $middleware = new TimezoneMiddleware($this->geoPlugin, $this->config);
        $this->request = new ServerRequest(['cookies' => ['timezone' => 'America/New_York']]);
        $response = $middleware($this->request, $this->response, $this->next);

        $this->assertEquals('America/New_York', Configure::read('App.timezone'));
    }

    /**
     * testWithTimezoneInUserSession.
     *
     * @return void
     */
    public function testWithTimezoneInUserSession()
    {
        $this->session->write('Auth.User.timezone', 'Europe/Amsterdam');
        $this->request = new ServerRequest(['session' => $this->session]);
        $middleware = new TimezoneMiddleware($this->geoPlugin, $this->config);
        $response = $middleware($this->request, $this->response, $this->next);

        $this->assertEquals('Europe/Amsterdam', Configure::read('App.timezone'));
    }

    /**
     * Reset.
     *
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();
    }
}