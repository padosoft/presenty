<?php
namespace Padosoft\Presenty;

use InvalidArgumentException;

/**
 * Class Presenty
 * @package Padosoft\Presenty
 */
class Presenty
{
    /**
     * An instance's string.
     *
     * @var string
     */
    protected $str;

    /**
     * The string's encoding, which should be one of the mbstring module's
     * supported encodings.
     *
     * @var string
     */
    protected $encoding;

    /**
     * Initializes a Stringy object and assigns both str and encoding properties
     * the supplied values. $str is cast to a string prior to assignment, and if
     * $encoding is not specified, it defaults to mb_internal_encoding(). Throws
     * an InvalidArgumentException if the first argument is an array or object
     * without a __toString method.
     *
     * @param  mixed  $str      Value to modify, after being cast to string
     * @param  string $encoding The character encoding
     * @throws \InvalidArgumentException if an array or object without a
     *         __toString method is passed as the first argument
     */
    public function __construct($str = '', $encoding = null)
    {
        $this->validateArgument($str);

        $this->str = (string) $str;

        $this->encoding = $encoding ?: \mb_internal_encoding();
    }

    /**
     * @param $str
     *
     * @throws \InvalidArgumentException if an array or object without a
     *         __toString method is passed as the first argument
     */
    public function validateArgument($str): void
    {
        if (\is_array($str)) {
            throw new InvalidArgumentException(
                'Passed value cannot be an array'
            );
        }

        if (\is_object($str) && !method_exists($str, '__toString')) {
            throw new InvalidArgumentException(
                'Passed object must have a __toString method'
            );
        }
    }

    /**
     * Creates a Stringy object and assigns both str and encoding properties
     * the supplied values. $str is cast to a string prior to assignment, and if
     * $encoding is not specified, it defaults to mb_internal_encoding(). It
     * then returns the initialized object. Throws an InvalidArgumentException
     * if the first argument is an array or object without a __toString method.
     *
     * @param  mixed  $str Value to modify, after being cast to string
     * @param  string $encoding The character encoding
     * @return static A Stringy object
     * @throws \InvalidArgumentException if an array or object without a __toString method is passed as the first argument
     */
    public static function create($str = '', $encoding = null)
    {
        return new self($str, $encoding);
    }

    /**
     * Returns the value in $str.
     *
     * @return string The current value of the $str property
     */
    public function __toString()
    {
        return $this->str;
    }

    /**
     * Format money
     *
     * @param int $decimal
     * @param $currency
     * @return Presenty
     */
    public function money(int $decimal = 2, $currency = '&euro;'): self
    {
        if (isNullOrEmpty($this->str)) {
            $this->str = 0;
        }

        $this->number($decimal);

        if (!$this->str) {
            return $this;
        }

        $this->str = $currency . ' ' . $this->str;

        return $this;
    }

    /**
     * Format number
     *
     * @param int $decimal
     * @param $dec_point
     * @param $thousands_sep
     * @return Presenty
     */
    public function number(int $decimal = 0, $dec_point = ',', $thousands_sep = '.'): self
    {
        if (isNullOrEmpty($this->str)) {
            $this->str = 0;
        }

        if ($decimal < 0) {
            $decimal = 0;
        }

        $this->str = number_format($this->str, $decimal, $dec_point, $thousands_sep);

        return $this;
    }

    /**
     * Format boolean representation of string to translated yes/no string
     *
     * @param string $keyYes trans for yes
     * @param string $keyNo trans for no
     * @return Presenty
     */
    public function boolean($keyYes = 'si', $keyNo = 'no'): self
    {
        if (isNullOrEmpty($this->str)) {
            return $this;
        }

        if ($this->str == 1) {
            $this->str = $keyYes;
            return $this;
        }

        if ($this->isBooleanYes()) {
            $this->str = $keyYes;
            return $this;
        }

        $this->str = $keyNo;
        return $this;
    }

    /**
     * @return bool
     */
    public function isBooleanYes(): bool
    {
        $tmp = strtolower($this->str);
        return ($tmp === 'yes' || $tmp === 'si' || $tmp === 'y' || $tmp === 's');
    }

    /**
     * Format url
     *
     * @param integer $len
     * @return Presenty
     */
    public function url(int $len = 50): self
    {
        if (isNullOrEmpty($this->str)) {
            return $this;
        }
        $this->str = str_limit($this->str, $len);
        return $this;
    }

    /**
     * Format text
     *
     * @param integer $len
     * @return Presenty
     */
    public function description(int $len = 50): self
    {
        if (isNullOrEmpty($this->str)) {
            return $this;
        }
        $this->str = str_limit($this->str, $len);
        return $this;
    }

    /**
     * Format html anchor
     *
     * @param array $arrAnchorAttributes
     * @return Presenty
     */
    public function anchor(array $arrAnchorAttributes = []): self
    {
        if (isNullOrEmpty($this->str)) {
            return $this;
        }

        $attrib = '';
        foreach ($arrAnchorAttributes as $key => $val) {
            $attrib .= attre($key) . '="' . attre($val) . '" ';
        }
        $attrib .= ' ';

        $this->str = '<a ' . $attrib . 'href="' . $this->str . '">' . $this->str . '</a>';

        return $this;
    }

    /**
     * Format mailto
     * @param array $arrAnchorAttributes
     * @param string $label
     * @return Presenty
     */
    public function mailto(?array $arrAnchorAttributes = [], string $label = ''): self
    {
        if (!\is_array($arrAnchorAttributes)) {
            $arrAnchorAttributes = [];
        }
        $attrib = '';
        foreach ($arrAnchorAttributes as $key => $value) {
            $attrib .= attre($key) . '="' . attre($value) . '" ';
        }
        if (isNullOrEmpty($label)) {
            $label = $this->str;
        }
        $this->str = '<a ' . $attrib . ' href="mailto:' . attre($this->str) . '">' . $label . '</a>';

        return $this;
    }

    /**
     * @param string $classPositiveNumber
     * @param string $classNegativeNumber
     * @return Presenty
     */
    public function bkgPositiveOrNegative(string $classPositiveNumber = 'label label-success', string $classNegativeNumber = 'label label-danger'): self
    {
        if (isNullOrEmpty($this->str)) {
            return $this;
        }

        $class = $classNegativeNumber;
        if ($this->str >= 0 && isDouble($this->str, '', true)) {
            $class = $classPositiveNumber;
        }

        $this->str = '<span class="' . $class . '">' . $this->str . '</span>';

        return $this;
    }

    /**
     * Format date ita
     *
     * @return Presenty
     */
    public function dateIta(): self
    {
        $tmp = new \DateTime($this->str);
        $this->str = $tmp->format('d/m/Y');
        return $this;
    }

    /**
     * Formats an array of strings or numbers to a single chained string.
     * Automatically trims spaces from letters.
     * If $excludeZeroNumber is set to TRUE it recognizes 0 as a falsy value and it will be ignored.
     * @param array $array
     * @param string $implodeString
     * @param boolean $excludeZeroNumber
     * @return Presenty
     */
    public function implode(
        array $array,
        $implodeString = " ",
        $excludeZeroNumber = true
    ) {
        $trimmedArray = array_map('trim', $array);
        $cleanedArray = [];

        if ($excludeZeroNumber) {
            $cleanedArray = array_values(array_filter($trimmedArray));
        } else {
            $cleanedArray = array_values(
                array_filter(
                    $trimmedArray,
                    function ($item) {
                        return ($item || is_numeric($item));
                    })
            );
        }

        $this->str = implode($implodeString, $cleanedArray);

        return $this;
    }
}
