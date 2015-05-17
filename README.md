# PHP array schema validator
Validates arrays against given schema.

[![Build Status](https://img.shields.io/travis/serkin/volan.svg?style=flat-square)](https://travis-ci.org/serkin/parser)
[![Coverage Status](https://img.shields.io/coveralls/serkin/volan/master.svg?style=flat-square)](https://coveralls.io/r/serkin/volan?branch=master)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/serkin/volan.svg?style=flat-square)](https://scrutinizer-ci.com/g/serkin/volan/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/serkin/volan/v/stable)](https://packagist.org/packages/serkin/volan)
[![Total Downloads](https://poser.pugx.org/serkin/volan/downloads)](https://packagist.org/packages/serkin/volan)
[![Latest Unstable Version](https://poser.pugx.org/serkin/volan/v/unstable)](https://packagist.org/packages/serkin/volan)
[![License](https://poser.pugx.org/serkin/volan/license)](https://packagist.org/packages/serkin/volan)

## Installation
---
via Composer:

```
composer require serkin/volan ~1.1
```

## Usage
---
### Basic usage

```php
include 'vendor/autoload.php';
$schema = [
    'root' => [ // Schema must begins with 'root'
        'title' => [
            '_type' => 'required_string'
        ],
        'price' => [
            '_type' => 'number'
        ],
        'author' => [
            '_type' => 'string'
        ],
        'instock' => [
            '_type' => 'required_boolean'
        ],
        'info' =>  [
            '_type' => 'array',
            'isbn' => [
                '_type' => 'string'
            ],
            'pages' => [
                '_type' => 'number'
            ]
        ],
        'comments' => [
            '_type' => 'nested_array',
            'user_id' => [
                '_type' => 'required_number'
            ],
            'comment' => [
                '_type' => 'required_string'
            ]
        ]
    ]
];

$book = [
    'title' => 'The Idiot', // Cannot be omitted
    'instock' => true, // Cannot be omitted and has to be bool type
    'info' => ['isbn' => '978-0451531520'],
    //  'price' can be omitted but if present has to be numeric type 
    'comments' => [ // Nested array check nested elements
        [
            'user_id' => 1,
            'comment' => 'Good book',
            // 'extra_field' => 'bad field' 
            // By default if key not present in schema validation stops and returns false 
        ],
        [
            'user_id' => 2,
            'comment' => 'I like it'
        ]
    ]
];

$validator = new \Volan\Volan($schema);
$result = $validator->validate($book);

// if $result === false you can get full information about invalid node
var_dump($validator->getErrorInfo());
```
### Predifined validators
#### Strings
* `string`: string
* `required_string`: string that has to be present

#### Arrays
* `array`: array
* `required_array`: array that has to be present
* `nested_array`: array with nested arrays
* `required_nested_array`: array with nested arrays has to be present

#### Bool
* `boolean`: boolean
* `required_boolean`: boolean that has to be present

#### Numbers
* `number`: int or float
* `required_number`: int or float that has to be present

### Custom validators
If you need extra validators you can create them extending `\Volan\Validator\AbstractValidator` class
* Create folder `src/Volan/Validator` in your library
* Add your custom validator `src/Volan/Validator/mongoid_validator.php`. Example for `mongoid` validator:
```php
namespace Volan\Validator;
class mongoid_validator extends AbstractValidator
{
    public function isValid($nodeData)
    {
        return ($nodeData instanceof \MongoId);
    }
```
* Add autoload to composer.json
```json
"autoload": {
        "psr-4": {
            "Volan\\Validator\\": "src/Volan/Validator/"
        }
    }
```


## Tips
If you want allow extra keys in array you can define it in constructor
```php
$validator = new \Volan\Volan($schema, $strictMode = false);
```

In mongoDB when you update just several fields in collection you cannot pass validation cause required fields may be missing.
You can tell validator consider all required validation as unrequired. Example:
```php
$validator = new \Volan\Volan($schema);
$validator->setRequiredMode(false);
```
## Dependencies
* PHP: >= 5.5

## Contribution
* Send a pull request

## Licence
* MIT

## Tests
```
phpunit
```
