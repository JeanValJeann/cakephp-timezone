<?php
namespace Timezone\View\Helper;

use Cake\View\Helper;
use Cake\View\View;
use Cake\I18n\Time;
use DateTimeZone;
use DateTime;

/**
 * This is a CakePHP helper that helps users to deal with timezone.
 * 
 * This helper can:
 *  - Generate timezone with its current GMT offset or an offset depending of datetime.
 *  - Generate timezone identifiers array list grouped by continent and containing current GMT offset or not.
 *  - Generate input text containing datetime in SQL format in special timezone.
 *  - Generate select containing all timezone identifiers and set a special timezone as default value.
 *
 * @author PERRIN Jean-Charles
 * @link https://github.com/JeanValJeann/cakephp-timezone
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @property \Cake\View\Helper\HtmlHelper $Html
 */
class TimezoneHelper extends Helper
{
    /**
     * Timezone to use.
     * @var string
     */
    protected $timezone = 'UTC';

    /**
     * Other helpers to load.
     * @var array
     */
    public $helpers = ['Form', 'Time'];

    /**
     * Default settings.
     * @var array
     */
    protected $_defaultConfig = [
        'timezone' => 'UTC', // Timezone to use.
    ];

    /**
     * Constructor hook method.
     *
     * Implement this method to avoid having to overwrite the constructor and call parent.
     *
     * @param array $config The configuration settings provided to this helper.
     * @return void
     */
    public function initialize(array $config)
    {
        if (in_array($this->getConfig('timezone'), DateTimeZone::listIdentifiers())) {
            $this->timezone = $this->getConfig('timezone');
        }
    }

    /**
     * TimezoneWithGMTOffset method.
     * 
     * It generates timezone identifier name and current GMT offset or GMT offset depending of datetime.
     *
     * @param string|null $timezone - Timezone to use (if null timezone specified in config will be used).
     * @param string $datetime - Datetime to use to get timezone's GMT offset.
     * @return string Complete text containing timezone identifier name and GMT offset (eg 'Europe/Paris (GMT+02:00)').
     */
    public function timezoneWithGMTOffset($timezone = null, $datetime = 'now')
    {
        $timezone = ($timezone ? $timezone : $this->timezone);
        $output = $timezone . ' ' . $this->_gmtOffset($timezone, $datetime);

        return $output;
    }

    /**
     * TimezoneIdentifiersArrayList method.
     * 
     * It generates an array list of timezone identifiers grouped by continent and containing current GMT or not.
     *
     * @param bool $gmtOffset - Add GMT offset after timezone identifier name.
     * @return array Complete array list of timezone identifiers.
     */
    public function timezoneIdentifiersArrayList($gmtOffset = true)
    {
        $listIdentifiers = DateTimeZone::listIdentifiers();

        $array = [];
        foreach ($listIdentifiers as $key => $listIdentifier) {
            $listIdentifierExploded = explode('/', $listIdentifier);
            $primary_region = $listIdentifierExploded[0];
            if ($primary_region !== 'UTC') {
                array_shift($listIdentifierExploded);
                $secondary_region = '';
                foreach ($listIdentifierExploded as $key => $value) $secondary_region .= $value . '/';
                $secondary_region = rtrim($secondary_region, '/');
            } else {
                $secondary_region = 'UTC';
            }
            $array[$primary_region][$listIdentifier] = $secondary_region . ' ' . ($gmtOffset ? $this->_gmtOffset($listIdentifier) : '');
        }

        return $array;
    }

    /**
     * Datetime method.
     * 
     * It generates text input containing datetime in SQL format in special timezone.
     * 
     * @param  string $fieldName - Text input name.
     * @param  array $options - Text input options.
     * @param  string|null $timezone - Timezone to use to display datetime (if null timezone specified in config will be used).
     * @return string Complete datetime input text in sql format.
     */
    public function datetime($fieldName, array $options = [], $timezone = null)
    {
        $timezone = ($timezone ? $timezone : $this->timezone);

        $datetime = ($this->Form->getSourceValue($fieldName) ? $this->Form->getSourceValue($fieldName) : 'now');
        $value = $this->Time->format($datetime, 'yyyy-MM-dd HH:mm:ss', null, $timezone);

        $options = ['type' => 'text', 'value' => $value] + $options;
        $output = $this->Form->control($fieldName, $options);

        return $output;
    }

    /**
     * Select method.
     * 
     * It generates select containing all timezone identifiers and set a special timezone as default value.
     * 
     * @param  string $fieldName - Select name.
     * @param  array $attributes - Select's attributes.
     * @param  string|null $timezone - Timezone to use as default selected value (if null timezone specified in config will be used).
     * @return string Complete timezone select.
     */
    public function select($fieldName, array $attributes = [], $timezone = null)
    {
        $timezone = ($timezone ? $timezone : $this->timezone);

        $attributes = ['value' => $timezone] + $attributes;

        $output = $this->Form->select($fieldName, $this->timezoneIdentifiersArrayList(), $attributes);

        return $output;
    }

    /**
     * Private _gmtOffset method.
     *
     * It generates timezone's current offset or offset depending of datetime in H:i format.
     *
     * @param  string|null $timezone - Timezone to get GMT offset from (if null timezone specified in config will be used).
     * @param  string $datetime - Datetime to use to get timezone GMT offset.
     * @return string Complete GMT offset (eg '(GMT +02:00)').
     */
    private function _gmtOffset($timezone = null, $datetime = 'now')
    {
        $timezone = ($timezone ? $timezone : $this->timezone);

        $tz = new DateTimeZone($timezone);
        $now = new DateTime($datetime, $tz);
        $offsetInSecs = $tz->getOffset($now);
        $Hi = gmdate('H:i', abs($offsetInSecs));
        $offset = stripos($offsetInSecs, '-') === false ? "+{$Hi}" : "-{$Hi}";
        $gmtOffset = '(GMT' . $offset .')';

        return $gmtOffset;
    }
}