<?php
namespace Timezone\Test\Model\Validation;

use Timezone\Model\Validation\TimezoneValidation;
use Cake\TestSuite\TestCase;

/**
 * TimezoneValidation Test
 */
class TimezoneValidationTest extends TestCase
{
    /**
     * testValid
     */
    public function testValid()
    {
        $this->assertTrue(TimezoneValidation::valid('Europe/Paris'));
        $this->assertFalse(TimezoneValidation::valid('Fake/Timezone'));
    }
}