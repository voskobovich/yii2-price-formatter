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
     * @param $value
     * @return float
     */
    public function toStore($value)
    {
        $value = str_replace(',', '.', $value);
        $value = preg_replace('/\s+/', '', $value);
        $value = preg_replace('/\x{00a0}/siu', '', $value);

        return round($value * 100, 0);
    }

    /**
     * @param $value
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function toEdit($value)
    {
        return $value / 100;
    }

    /**
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

        return Yii::$app->formatter->asCurrency($this->toEdit($value), $this->currencyCode(), $options, $textOptions);
    }
}