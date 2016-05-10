<?php

namespace voskobovich\price\components;

use Yii;
use yii\base\Component;


/**
 * Class PriceFormatter
 * @package voskobovich\price\components
 */
class PriceFormatter extends Component
{
    /**
     * Currency code
     * Must be return the 3-letter ISO 4217 currency code indicating the currency to use.
     * If null, [[currencyCode]] will be used.
     * See http://www.yiiframework.com/doc-2.0/yii-i18n-formatter.html#$currencyCode-detail
     * @var null|string|callable
     */
    public $currencyCode = null;

    /**
     * Configuration for the number formatter.
     * This parameter will be merged with [[numberFormatterOptions]].
     * See http://www.yiiframework.com/doc-2.0/yii-i18n-formatter.html#asCurrency()-detail
     * @var array
     */
    public $options = [];

    /**
     * Configuration for the number formatter.
     * This parameter will be merged with [[numberFormatterTextOptions]].
     * See http://www.yiiframework.com/doc-2.0/yii-i18n-formatter.html#asCurrency()-detail
     * @var array
     */
    public $textOptions = [];

    /**
     * Getting currency code
     * @return callable|mixed|null|string
     */
    protected function currencyCode()
    {
        if ($this->currencyCode !== null) {
            if (is_callable($this->currencyCode)) {
                return call_user_func($this->currencyCode, $this);
            } else {
                return $this->currencyCode;
            }
        }

        return Yii::$app->formatter->currencyCode;
    }

    /**
     * Converts 3.99 dollars => 399 cents
     * @param float|string $value
     * @return int
     */
    public function toStore($value)
    {
        $value = str_replace(',', '.', $value);
        $value = preg_replace('/\s+/', '', $value);
        $value = preg_replace('/\x{00a0}/siu', '', $value);

        return (int)round((float)$value * 100);
    }

    /**
     * Converts 3 dollars 99 cents => 399 cents
     * @param integer $integer
     * @param integer $fraction
     * @return integer
     */
    public function toStoreByParts($integer, $fraction)
    {
        return ((int)$integer * 100) + (int)$fraction;
    }

    /**
     * Converts 399 cents => 3.99 dollars
     * @param $value
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function toEdit($value)
    {
        return (int)$value / 100;
    }

    /**
     * Converts 399 cents => 3 dollars, 99 cents
     * @param integer $value
     * @return array
     */
    public function toEditByParts($value)
    {
        $value = $this->toEdit($value);

        $fraction = 0;
        if (($pos = strpos($value, '.')) !== false) {
            $fraction = substr($value, $pos + 1);
        }

        return [floor($value), $fraction];
    }

    /**
     * Converts 399 cents => $3,99
     * @param $value
     * @param array $options
     * @param array $textOptions
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function toView($value, $options = [], $textOptions = [])
    {
        if ($options) {
            $options = $this->options;
        }

        if ($textOptions) {
            $textOptions = $this->textOptions;
        }

        return Yii::$app->formatter->asCurrency(
            $this->toEdit($value),
            $this->currencyCode(),
            $options,
            $textOptions
        );
    }
}