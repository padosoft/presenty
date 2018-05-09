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

        $this->str = (string) $str;

        $this->encoding = $encoding ?: \mb_internal_encoding();
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
     * @param string $currency
     * @return Presenty
     */
    public function money(int $decimal = 2, $currency = '&euro;') : self
	{
	    if(isNullOrEmpty($this->str)) {
            $this->str = 0;
        }

		$this->number($decimal);

		if(!$this->str) {
			return $this;
        }

        $this->str = $currency.' '.$this->str;

        return $this;
    }

    /**
     * Format number
     *
     * @param int $decimal
     * @param string $dec_point
     * @param string $thousands_sep
     * @return Presenty
     */
    public function number(int $decimal = 0, $dec_point=',', $thousands_sep='.') : self
	{
        if(isNullOrEmpty($this->str)) {
            $this->str = 0;
        }

		if($decimal < 0) {
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
    public function boolean($keyYes = 'si', $keyNo = 'no') : self
	{
        if(isNullOrEmpty($this->str)) {
            return $this;
        }
		if ($this->str == 1){
            $this->str = $keyYes;
            return $this;
        }

        $tmp = strtolower($this->str);
        if($tmp === 'yes' || $tmp  === 'si' || $tmp  === 'y' || $tmp === 's') {
            $this->str = $keyYes;
            return $this;
        }

		$this->str = $keyNo;
        return $this;
    }

    /**
     * Format url
     *
     * @param integer $len
     * @return Presenty
     */
    public function url(int $len = 50) : self
    {
        if(isNullOrEmpty($this->str)) {
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
    public function description(int $len = 50) : self
	{
        if(isNullOrEmpty($this->str)) {
			return $this;
		}
		$this->str = str_limit($this->str, $len);
		return $this;
    }

    /**
     * Format anchor
     *
     * @param string $url
     * @param array $arrAnchorAttributes
     * @return Presenty
     */
    public function anchor(string $url, array $arrAnchorAttributes = []) : self
	{
        if(isNullOrEmpty($url)) {
            $this->str = '';
            return $this;
		}
		if(!\is_array($arrAnchorAttributes)){
			$arrAnchorAttributes = [];
		}
		if(!array_key_exists('target', $arrAnchorAttributes)){
			$arrAnchorAttributes['target'] = '_blank';
		}
		$attrib='';
		foreach($arrAnchorAttributes as $key => $val){
			$attrib.=attre($key).'="'.attre($val).'" ';
		}
		$this->str = '<a '.$attrib.' href="'.$url.'">'.$this->str.'</a>';

        return $this;
    }

    /**
     * Format mailto
     * @param array $arrAnchorAttributes
     * @param string $label
     * @return Presenty
     */
    public function mailto(?array $arrAnchorAttributes = [], string $label = '') : self
	{
		if(!\is_array($arrAnchorAttributes)){
			$arrAnchorAttributes = [];
		}
		$attrib='';
		foreach($arrAnchorAttributes as $key => $value){
			$attrib.=attre($key).'="'.attre($value).'" ';
        }
        if(isNullOrEmpty($label)) {
            $label = $this->str;
        }
		$this->str = '<a '.$attrib.' href="mailto:'.attre($this->str).'">'.$label.'</a>';

        return $this;
    }

    /**
     * @param $value
     * @param string $classPositiveNumber
     * @param string $classNegativeNumber
     * @return Presenty
     */
    public function bkgPositiveOrNegative($value, string $classPositiveNumber = 'label label-success', string $classNegativeNumber = 'label label-danger') : self
    {
        $class = $classNegativeNumber;
        if($value >= 0 && isDouble($value, '', true)){
            $class = $classPositiveNumber;
        }
        $this->str = '<span class="'.$class.'">'. $this->str.'</span>';
        return $this;
    }

    /**
     * Format date ita
     *
     * @return Presenty
     */
    public function dateIta() : self
	{
        $tmp = new \DateTime($this->str);
        $this->str = $tmp->format('d/m/Y');
        return $this;
    }
}

