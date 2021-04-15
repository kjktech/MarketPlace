<?php

namespace RandomStringGenerator;

class RandomStringGenerator
{
    /** @var array */
    private static $stringPatterns = [
        'l' => SupportedCharacter::LOWER_ALPHA_CHARS,
        'L' => SupportedCharacter::UPPER_ALPHA_CHARS,
        'd' => SupportedCharacter::DIGITS,
        'p' => SupportedCharacter::PUNCTUATION,
    ];

    /**
     * @param string $stringPattern
     *
     * @return string
     */
    public function generate(string $stringPattern) : string
    {
        $generatedString    = '';
        $scapeNextCharacter = false;

        foreach (str_split($stringPattern) as $character) {

            $character          = $scapeNextCharacter ? $character : $this->getCharacterByStringPattern($character);
            $scapeNextCharacter = $character == '';

            $generatedString .= $character;
        }

        return $generatedString;
    }

    /**
     * @param string $pattern
     *
     * @return string
     */
    private function getCharacterByStringPattern(string $pattern) : string
    {
        if ($pattern === '\\') {
            return '';
        }

        if (array_key_exists($pattern, self::$stringPatterns)) {

            return $this->getRandomCharacterFromArray(self::$stringPatterns[$pattern]);
        }

        return $pattern;
    }

    /**
     * @param array $characters
     *
     * @return string
     */
    private function getRandomCharacterFromArray(array $characters) : string
    {
        $randomPos = array_rand($characters, 1);

        return $characters[$randomPos];
    }

}
