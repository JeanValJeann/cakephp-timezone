<?php
namespace Timezone\Test\View\Helper;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use Timezone\View\Helper\TimezoneHelper;
use DateTimeZone;
use DateTime;

/*
 * TimezoneHelper Test
 */
class TimezoneHelperTest extends TestCase
{
	/**
	 * @var \Geo\View\Helper\GoogleMapHelper
	 */
	protected $Timezone;

	/**
	 * @var \Cake\View\View
	 */
	
	protected $View;
	/**
	 * @return void
	 */
	public function setUp() {
		parent::setUp();
		$this->View = new View(null);
		$this->Timezone = new TimezoneHelper($this->View, [
			'timezone' => 'Europe/Paris'
		]);
	}

	/**
	 * Test Config.
	 */
	public function testConfig()
	{
		$this->Timezone = new TimezoneHelper($this->View);
		$result = $this->Timezone->getConfig();
		$expected = [
			'timezone' => 'UTC'
		];
		$this->assertEquals($expected, $result);

		$this->Timezone = new TimezoneHelper($this->View, [
			'timezone' => 'Europe/Paris'
		]);
		$result = $this->Timezone->getConfig();
		$expected = [
			'timezone' => 'Europe/Paris'
		];
		$this->assertEquals($expected, $result);
	}

	/**
	 * Test TimezoneWithGMTOffset.
	 */
	public function testTimezoneWithGMTOffset()
	{
		$result = $this->Timezone->timezoneWithGMTOffset(null, '2018-11-21 12:00:00');
		$expected = 'Europe/Paris (GMT+01:00)';

		$this->assertEquals($expected, $result);

		$result = $this->Timezone->timezoneWithGMTOffset(null, '2018-07-21 12:00:00');
		$expected = 'Europe/Paris (GMT+02:00)';

		$this->assertEquals($expected, $result);
	}

	/**
	 * Test TimezoneIdentifiersArrayList.
	 */
	public function testTimezoneIdentifiersArrayList()
	{
		$result = $this->Timezone->timezoneIdentifiersArrayList();
		$this->assertTrue(true);
	}

	/**
	 * Test Datetime.
	 */
	public function testDatetime()
	{
		$result = $this->Timezone->datetime('published');
		$this->assertTrue(true);
	}

	/**
	 * Test Select.
	 */
	public function testSelect()
	{
		$result = $this->Timezone->select('published');
		$this->assertTrue(true);
	}
}