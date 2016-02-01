Yii2 Price Formatter
===

This component converts currency values (prices) between fixed-point representation (int as number of cents) and floating point representation (float as dollars and cents).

[![License](https://poser.pugx.org/voskobovich/yii2-price-formatter/license.svg)](https://packagist.org/packages/voskobovich/yii2-price-formatter)
[![Latest Stable Version](https://poser.pugx.org/voskobovich/yii2-price-formatter/v/stable.svg)](https://packagist.org/packages/voskobovich/yii2-price-formatter)
[![Latest Unstable Version](https://poser.pugx.org/voskobovich/yii2-price-formatter/v/unstable.svg)](https://packagist.org/packages/voskobovich/yii2-price-formatter)
[![Total Downloads](https://poser.pugx.org/voskobovich/yii2-price-formatter/downloads.svg)](https://packagist.org/packages/voskobovich/yii2-price-formatter)


Support
---
[GutHub issues](https://github.com/voskobovich/yii2-price-formatter/issues).


See example
---

The example of the USD.

Converts 3.99 dollars => 399 cents
```
Yii::$app->get('priceFormatter')->toStore(3.99); // input float
Yii::$app->get('priceFormatter')->toStore('3,99'); // input string
// Result: 399
```  
Converts 3 dollars 99 cents => 399 cents
```
Yii::$app->get('priceFormatter')->toStoreByParts(3, 99);
// Result: 399
```  
Converts 399 cents => 3.99 dollars
```
Yii::$app->get('priceFormatter')->toEdit(399);
// Result: 3.99
```  
Converts 399 cents => 3 dollars, 99 cents
```
Yii::$app->get('priceFormatter')->toEditByParts(399);
// Result array:
[
    0 => 3,
    1 => 99
]
```  
Converts 399 cents => $3.99 (with currency symbol )
```
Yii::$app->get('priceFormatter')->toView(399);
// Result: $3,99
```  

Installation
---

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist voskobovich/yii2-price-formatter "~1.0"
```

or add

```
"voskobovich/yii2-price-formatter": "~1.0"
```

to the require section of your `composer.json` file.


Usage
---

Configuration component in your app config file  
```
[
    ...
    'components' => [
        'priceFormatter' => [
            'class' => 'voskobovich\price\components\PriceFormatter',
//            'currencyCode' => 'USD',
            'currencyCode' => function($component) {
                return Yii::$app->user->identity->currency_code;
            }
        ]
    ]
]
``