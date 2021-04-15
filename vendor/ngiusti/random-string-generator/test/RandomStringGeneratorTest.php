<?php

namespace RandomStringGenerator\Test;

use PHPUnit\Framework\TestCase;
use RandomStringGenerator\RandomStringGenerator;

class RandomStringGeneratorTest extends TestCase
{
    /** @var RandomStringGenerator */
    private $generator;

    public function setUp()
    {
        $this->generator = new RandomStringGenerator();
    }

    public function testGenerateShouldGenerateStringWithSameSize()
    {
        $stringPattern = 'lll';

        $this->assertEquals(strlen($stringPattern), strlen($this->generator->generate($stringPattern)));
    }

    public function testGenerateShouldGenerateLowerCaseString()
    {
        $stringPattern = 'l';

        $this->assertRegExp('/[a-z]/', $this->generator->generate($stringPattern));
    }

    public function testGenerateShouldGenerateUpperCaseString()
    {
        $stringPattern = 'L';

        $this->assertRegExp('/[A-Z]/', $this->generator->generate($stringPattern));
    }

    public function testGenerateShouldGenerateDigit()
    {
        $stringPattern = 'd';

        $this->assertRegExp('/[0-9]/', $this->generator->generate($stringPattern));
    }

    public function testGenerateShouldGeneratePunctuation()
    {
        $stringPattern = 'p';

        $this->assertRegExp('/[\p{P}\p{S}]/', $this->generator->generate($stringPattern));
    }

    public function testGenerateShouldAcceptAnyDelimiter()
    {
        $stringPattern = 'd-';

        $this->assertRegExp('/[0-9]-/', $this->generator->generate($stringPattern));
    }

    public function testGenerateShouldAcceptAUtf8()
    {
        $stringPattern = 'ááàã';

        $this->assertEquals($stringPattern, $this->generator->generate($stringPattern));
    }

    public function testGenerateShouldScapeCharacter()
    {
        $stringPattern = '\\d-';

        $this->assertEquals('d-', $this->generator->generate($stringPattern));
    }

    public function testGenerateShouldGenerateComplexPatter()
    {
        $stringPattern = 'lLdp-\\d';

        $this->assertRegExp('/[a-z][A-Z][0-9][\p{P}\p{S}]-d/', $this->generator->generate($stringPattern));
    }
}
