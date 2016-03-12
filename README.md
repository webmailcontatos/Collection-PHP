[![Latest Stable Version](https://poser.pugx.org/faguima/collection/v/stable)](https://packagist.org/packages/faguima/collection)
[![Total Downloads](https://poser.pugx.org/faguima/collection/downloads)](https://packagist.org/packages/faguima/collection)
[![License](https://poser.pugx.org/faguima/collection/license)](https://packagist.org/packages/faguima/collection)
[![Build Status](https://travis-ci.org/webmailcontatos/Collection-PHP.svg?branch=master)](https://travis-ci.org/webmailcontatos/Collection-PHP)
# Collection-PHP
 Excellent tool to make your experience with php array much cleaner and comfortable

## Installation

If you're using Composer to manage dependencies, you can include the following in your composer.json file:
```json
"require": {
    "faguima/collection": "1.0.0"
}
```

or

```sh
composer require faguima/collection
```

Then, after running composer update or php composer.phar update, you can load the class using Composer's autoloading:

```php
require 'vendor/autoload.php';
```

## A Simple Example

``` php
require 'vendor/autoload.php';

use Collection\Collection as Collection;

$collection = Collection::make([1, 2, 3]);

echo $collection->sum(); // 6
echo $collection->first(); // 1
echo $collection->last(); // 3

$filter = $collection->filter(function ($item) {
    return $item > 1;
});
echo $filter->sum(); // 5;

```
## Method Listing

#### all()
The all method simply returns the underlying array represented by the collection:
``` php
collect([1, 2, 3])->all();

// [1, 2, 3]
```
#### avg()
The avg method returns the average of all items in the collection:
``` php
collect([1, 2, 3, 4, 5])->avg();

// 3
```
If the collection contains nested arrays or objects, you should pass a key to use for determining which values to calculate the average:

``` php
$collection = collect([
    ['name' => 'JavaScript: The Good Parts', 'pages' => 176],
    ['name' => 'JavaScript: The Definitive Guide', 'pages' => 1096],
]);
$collection->avg('pages');
// 636
```
#### chunk()
The chunk method breaks the collection into multiple, smaller collections of a given size:
``` php
$collection = collect([1, 2, 3, 4, 5, 6, 7]);
$chunks = $collection->chunk(4);
$chunks->toArray();
// [[1, 2, 3, 4], [5, 6, 7]]
```
#### collapse()
The collapse method collapses a collection of arrays into a flat collection:
``` php
$collection = collect([[1, 2, 3], [4, 5, 6], [7, 8, 9]]);
$collapsed = $collection->collapse();
$collapsed->all();
// [1, 2, 3, 4, 5, 6, 7, 8, 9]
```
#### contains()
The contains method determines whether the collection contains a given item:
``` php
$collection = collect(['name' => 'Desk', 'price' => 100]);
$collection->contains('Desk');
// true
$collection->contains('New York');
// false
```
You may also pass a key / value pair to the contains method, which will determine if the given pair exists in the collection:
``` php
$collection = collect([
    ['product' => 'Desk', 'price' => 200],
    ['product' => 'Chair', 'price' => 100],
]);
$collection->contains('product', 'Bookcase');
// false
```
Finally, you may also pass a callback to the contains method to perform your own truth test:
``` php
$collection = collect([1, 2, 3, 4, 5]);
$collection->contains(function ($key, $value) {
    return $value > 5;
});
// false
```
#### count()
The count method returns the total number of items in the collection:
``` php
$collection = collect([1, 2, 3, 4]);
$collection->count();
// 4
```
#### diff()
The diff method compares the collection against another collection or a plain PHP array:
``` php
$collection = collect([1, 2, 3, 4, 5]);
$diff = $collection->diff([2, 4, 6, 8]);
$diff->all();
// [1, 3, 5]
```
#### each()
The each method iterates over the items in the collection and passes each item to a given callback:
``` php
$collection = $collection->each(function ($item, $key) {
    //
});
```
Return false from your callback to break out of the loop:
``` php
$collection = $collection->each(function ($item, $key) {
    if (/* some condition */) {
        return false;
    }
});
```
#### every()
The every method creates a new collection consisting of every n-th element:
``` php
$collection = collect(['a', 'b', 'c', 'd', 'e', 'f']);
$collection->every(4);
// ['a', 'e']
```
You may optionally pass offset as the second argument:
``` php
$collection->every(4, 1);
// ['b', 'f']
```
#### except()
The except method returns all items in the collection except for those with the specified keys:
``` php
$collection = collect(['product_id' => 1, 'name' => 'Desk', 'price' => 100, 'discount' => false]);
$filtered = $collection->except(['price', 'discount']);
$filtered->all();
// ['product_id' => 1, 'name' => 'Desk']
```
#### filter()
The filter method filters the collection by a given callback, keeping only those items that pass a given truth test:
``` php
$collection = collect([1, 2, 3, 4]);
$filtered = $collection->filter(function ($item) {
    return $item > 2;
});
$filtered->all();
// [3, 4]
```
#### first()
The first method returns the first element in the collection that passes a given truth test:
``` php
collect([1, 2, 3, 4])->first(function ($key, $value) {
    return $value > 2;
});
// 3
```
You may also call the first method with no arguments to get the first element in the collection. If the collection is empty, null is returned:
``` php
collect([1, 2, 3, 4])->first();

// 1
```
#### flatten()
The flatten method flattens a multi-dimensional collection into a single dimension:
``` php
$collection = collect(['name' => 'taylor', 'languages' => ['php', 'javascript']]);
$flattened = $collection->flatten();
$flattened->all();
// ['taylor', 'php', 'javascript'];
```
#### flip()
The flip method swaps the collection's keys with their corresponding values:
``` php
$collection = collect(['name' => 'taylor', 'framework' => 'laravel']);
$flipped = $collection->flip();
$flipped->all();
// ['taylor' => 'name', 'laravel' => 'framework']
```
#### forget()
The forget method removes an item from the collection by its key:
``` php
$collection = collect(['name' => 'taylor', 'framework' => 'laravel']);
$collection->forget('name');
$collection->all();
// [framework' => 'laravel']
```
#### forPage()
The forPage method returns a new collection containing the items that would be present on a given page number:
``` php
 $collection = collect([1, 2, 3, 4, 5, 6, 7, 8, 9]);
 $chunk = $collection->forPage(2, 3);
 $chunk->all();
// [4, 5, 6]
```
The method requires the page number and the number of items to show per page, respectively.

#### get()
The get method returns the item at a given key. If the key does not exist, null is returned:
``` php
$collection = collect(['name' => 'taylor', 'framework' => 'laravel']);
$value = $collection->get('name');
// taylor
```
You may optionally pass a default value as the second argument:
``` php
$collection = collect(['name' => 'taylor', 'framework' => 'laravel']);
$value = $collection->get('foo', 'default-value');
// default-value
```
You may even pass a callback as the default value. The result of the callback will be returned if the specified key does not exist:
``` php
$collection->get('email', function () {
    return 'default-value';
});
// default-value
```
#### groupBy()
The groupBy method groups the collection's items by a given key:
``` php
$collection = collect([
    ['account_id' => 'account-x10', 'product' => 'Chair'],
    ['account_id' => 'account-x10', 'product' => 'Bookcase'],
    ['account_id' => 'account-x11', 'product' => 'Desk'],
]);
$grouped = $collection->groupBy('account_id');
$grouped->toArray();
/*
    [
        'account-x10' => [
            ['account_id' => 'account-x10', 'product' => 'Chair'],
            ['account_id' => 'account-x10', 'product' => 'Bookcase'],
        ],
        'account-x11' => [
            ['account_id' => 'account-x11', 'product' => 'Desk'],
        ],
    ]
*/
```
In addition to passing a string key, you may also pass a callback. The callback should return the value you wish to key the group by:
``` php
$grouped = $collection->groupBy(function ($item, $key) {
    return substr($item['account_id'], -3);
});
$grouped->toArray();
/*
    [
        'x10' => [
            ['account_id' => 'account-x10', 'product' => 'Chair'],
            ['account_id' => 'account-x10', 'product' => 'Bookcase'],
        ],
        'x11' => [
            ['account_id' => 'account-x11', 'product' => 'Desk'],
        ],
    ]
*/