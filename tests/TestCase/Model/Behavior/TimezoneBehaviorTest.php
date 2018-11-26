<?php
namespace Timezone\Test\Model\Behavior;

use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
use Timezone\Model\Behavior\TimezoneBehavior;
use Cake\TestSuite\TestCase;

/**
 * TimezoneBehavior Test
 */
class TimezoneBehaviorTest extends TestCase
{
	/**
	 * @var array
	 */
	public $fixtures = [
		'plugin.Timezone.Articles'
	];

	/**
	 * @var \Cake\ORM\Table;
	 */
	public $Articles;

	/**
	 * setUp
	 *
	 * @return void
	 */
	public function setUp() {
		parent::setUp();
		$table = TableRegistry::get('Articles');
		$table->addBehavior('Timezone.Timezone', [
			'fromTimezone' => 'Europe/Paris',
			'fields' => ['published']
		]);

		$this->Table = $table;
		$this->Behavior = $this->Table->behaviors()->Timezone;
	}

	/**
	 * teardown
	 *
	 * @return void
	 */
	public function tearDown() {
		parent::tearDown();
		unset($this->Articles);
		TableRegistry::clear();
	}

	/**
	 * testConfig
	 */
	public function testConfig()
	{
		$this->Table->removeBehavior('Timezone');
		$this->Table->addBehavior('Timezone.Timezone', []);
		$this->Behavior = $this->Table->behaviors()->Timezone;
		$result = $this->Behavior->getConfig();
		$expected = [
			'fromTimezone' => 'UTC',
        	'toTimezone' => 'UTC',
        	'fields' => [],
        	'suffix' => '_timezone'
		];
		$this->assertEquals($expected, $result);

		$this->Table->removeBehavior('Timezone');
		$this->Table->addBehavior('Timezone.Timezone', [
			'fromTimezone' => 'Europe/Paris',
        	'toTimezone' => 'America/New_York',
        	'fields' => ['published'],
        	'suffix' => '_mySuffix'
		]);
		$this->Behavior = $this->Table->behaviors()->Timezone;
		$result = $this->Behavior->getConfig();
		$expected = [
			'fromTimezone' => 'Europe/Paris',
        	'toTimezone' => 'America/New_York',
        	'fields' => ['published'],
        	'suffix' => '_mySuffix'
		];
		$this->assertEquals($expected, $result);
	}

	/**
	 * testMarshallingField
	 */
	public function testMarshallingField()
	{
		$data = [
			'title' => 'My first article',
			'published' => '2018-11-20 12:00:00',
		];
		$article = $this->Table->newEntity($data);
		$result = $article->published->format('Y-m-d H:i:s');
		$expected = '2018-11-20 11:00:00';

		$this->assertEquals($expected, $result);
	}

	/**
	 * testMarshallingFieldWhenOtherFieldHavingSuffixAndRepresentingTimezoneIsPresent
	 */
	public function testMarshallingFieldWhenOtherFieldHavingSuffixAndRepresentingTimezoneIsPresent()
	{
		$data = [
			'title' => 'My first article',
			'published' => '2018-11-20 12:00:00',
			'published_timezone' => 'America/New_York'
		];
		$article = $this->Table->newEntity($data);
		$result = $article->published->format('Y-m-d H:i:s');
		$expected = '2018-11-20 17:00:00';

		$this->assertEquals($expected, $result);
	}
}
