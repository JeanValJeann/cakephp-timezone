<?php
namespace Timezone\Model\Validation;

use DateTimeZone;

/**
 * Timezone Validation class. Handles timezone validation.
 *
 */
class TimezoneValidation
{
    /**
     * Checks if a timezone is valid.
     *
     * @param string $check The value to check.
     * @return bool Success.
     */
    public static function valid($check)
    {
    	if (in_array($check, DateTimeZone::listIdentifiers())) {
    		return true;
    	}

        return false;
    }
}