<?php
namespace Timezone\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\I18n\Time;
use Cake\Event\Event;
use ArrayObject;
use DateTimeZone;

/**
 * A timezone behavior for CakePHP to easily deal with timezone.
 *
 * It converts datetime field from one timezone to another one (both specified in config 'fromTimezone' key and config 'toTimezone' key). In case there is a field into set of data named field to be converted's name + suffix (specified in config 'suffix' key) and representing a valid timezone identifier, then this is the timezone identifier that'll be used to convert the datetime from. that's a way to overwrite config 'fromTimezone' key and deal with physical futur datetime.
 * 
 * Note that datetime that has to be convert must be in one of the three format supported by CakePHP Time class wich are the same of native PHP DateTime class:
 *  - Y-m-d H:i:s (SQL format)
 *  - m/d/y H:i:s (American format)
 *  - d-m-y H:i:s (European format)
 *
 * @author PERRIN Jean-Charles
 * @license MIT
 * @link https://github.com/JeanValJeann/cakephp-timezone
 */
class TimezoneBehavior extends Behavior
{
    /**
     * Timezone to convert from.
     * @var string
     */
    private $fromTimezone = 'UTC';

    /**
     * Timezone to convert to.
     * @var string
     */
    private $toTimezone = 'UTC';

    /**
     * Default settings.
     *
     * - `fromTimezone`: defaults to 'UTC' - Timezone to convert datetime from.
     * - `toTimezone`: defaults to 'UTC' - Timezone to convert datetime to.
     * - `fields`: defaults to [] - Unindexed array of datetime field names to be converted.
     * - `suffix`: defaults to '_timezone' - Allow to overwrite config 'fromTimezone' key and deal with physical futur datetime.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'fromTimezone' => 'UTC', // Timezone to convert datetime from.
        'toTimezone' => 'UTC', // Timezone to convert datetime to.
        'fields' => [], // Unindexed array of datetime field names to be converted.
        'suffix' => '_timezone' // Allow to overwrite config 'fromTimezone' key and deal with physical futur datetime.
    ];

    /**
     * Constructor hook method.
     *
     * Implement this method to avoid having to overwrite
     * the constructor and call parent.
     *
     * @param array $config The configuration settings provided to this behavior.
     * @return void
     */
    public function initialize(array $config)
    {
        if (in_array($this->getConfig('fromTimezone'), DateTimeZone::listIdentifiers())) {
            $this->fromTimezone = $this->getConfig('fromTimezone');
        }
        if (in_array($this->getConfig('toTimezone'), DateTimeZone::listIdentifiers())) {
            $this->toTimezone = $this->getConfig('toTimezone');
        }
    }

    /**
     * BeforeMarshal method.
     * 
     * It's executed before data is converted to entity
     *
     * It converts datetime field from one timezone to another one (both specified in config 'fromTimezone' key and config 'toTimezone' key). In case there is a field into set of data named field to be converted's name + suffix (specified in config 'suffix' key) and representing a valid timezone identifier, then this is the timezone identifier that'll be used to convert the datetime from. that's a way to overwrite config 'fromTimezone' key and deal with physical futur datetime.
     * 
     * Note that datetime that has to be convert must be in one of the three format supported by CakePHP Time class wich are the same of native PHP DateTime class:
     *  - Y-m-d H:i:s (SQL format)
     *  - m/d/y H:i:s (American format)
     *  - d-m-y H:i:s (European format)
     *
     * @param \Cake\Event\Event $event
     * @param ArrayObject $data
     * @param ArrayObject $options
     * @return void
     */
    public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options)
    {
        foreach ($this->getConfig('fields') as $key => $field) {
            if (isset($data[$field])) {
                $fromTimezone = $this->fromTimezone;
                if (
                    isset($data[$field . $this->getConfig('suffix')]) &&
                    in_array($data[$field . $this->getConfig('suffix')], DateTimeZone::listIdentifiers())
                ) {
                    $fromTimezone = $data[$field . $this->getConfig('suffix')];
                }
                $time = new Time($data[$field], $fromTimezone);
                $data[$field] = $time->setTimezone($this->toTimezone)->format('Y-m-d H:i:s');
            }
        }
    }
}