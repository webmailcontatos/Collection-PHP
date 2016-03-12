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
```
#### has()
The has method determines if a given key exists in the collection:
``` php
$collection = collect(['account_id' => 1, 'product' => 'Desk']);
$collection->has('email');
// false
```
#### implode()
The implode method joins the items in a collection. Its arguments depend on the type of items in the collection.

If the collection contains arrays or objects, you should pass the key of the attributes you wish to join, and the "glue" string you wish to place between the values:
``` php
$collection = collect([
    ['account_id' => 1, 'product' => 'Desk'],
    ['account_id' => 2, 'product' => 'Chair'],
]);
$collection->implode('product', ', ');
// Desk, Chair
```
If the collection contains simple strings or numeric values, simply pass the "glue" as the only argument to the method:
``` php
collect([1, 2, 3, 4, 5])->implode('-');
// '1-2-3-4-5'
```
#### intersect()
The intersect method removes any values that are not present in the given array or collection:
``` php
$collection = collect(['Desk', 'Sofa', 'Chair']);
$intersect = $collection->intersect(['Desk', 'Chair', 'Bookcase']);
$intersect->all();
// [0 => 'Desk', 2 => 'Chair']
```
As you can see, the resulting collection will preserve the original collection's keys.
#### isEmpty()
The isEmpty method returns true if the collection is empty; otherwise, false is returned:
``` php
collect([])->isEmpty();
// true
```
#### keyBy()
Keys the collection by the given key:
``` php
$collection = collect([
    ['product_id' => 'prod-100', 'name' => 'desk'],
    ['product_id' => 'prod-200', 'name' => 'chair'],
]);
$keyed = $collection->keyBy('product_id');
$keyed->all();
/*
    [
        'prod-100' => ['product_id' => 'prod-100', 'name' => 'Desk'],
        'prod-200' => ['product_id' => 'prod-200', 'name' => 'Chair'],
    ]
*/
```
If multiple items have the same key, only the last one will appear in the new collection.
You may also pass your own callback, which should return the value to key the collection by:
``` php
$keyed = $collection->keyBy(function ($item) {
    return strtoupper($item['product_id']);
});
$keyed->all();
/*
    [
        'PROD-100' => ['product_id' => 'prod-100', 'name' => 'Desk'],
        'PROD-200' => ['product_id' => 'prod-200', 'name' => 'Chair'],
    ]
*/
```
#### keys()
The keys method returns all of the collection's keys:
``` php
$collection = collect([
    'prod-100' => ['product_id' => 'prod-100', 'name' => 'Desk'],
    'prod-200' => ['product_id' => 'prod-200', 'name' => 'Chair'],
]);
$keys = $collection->keys();
$keys->all();
// ['prod-100', 'prod-200']
```
#### last()
The last method returns the last element in the collection that passes a given truth test:
``` php
collect([1, 2, 3, 4])->last(function ($key, $value) {
    return $value < 3;
});
// 2
```
You may also call the last method with no arguments to get the last element in the collection. If the collection is empty, null is returned:
``` php
collect([1, 2, 3, 4])->last();
// 4
```
#### map()
The map method iterates through the collection and passes each value to the given callback. The callback is free to modify the item and return it, thus forming a new collection of modified items:
``` php
$collection = collect([1, 2, 3, 4, 5]);
$multiplied = $collection->map(function ($item, $key) {
    return $item * 2;
});
$multiplied->all();
// [2, 4, 6, 8, 10]
```
#### max()
The max method return the maximum value of a given key:
``` php
$max = collect([['foo' => 10], ['foo' => 20]])->max('foo');
// 20
$max = collect([1, 2, 3, 4, 5])->max();
// 5
```
#### merge()
The merge method merges the given array into the collection. Any string key in the array matching a string key in the collection will overwrite the value in the collection:
``` php
$collection = collect(['product_id' => 1, 'name' => 'Desk']);
$merged = $collection->merge(['price' => 100, 'discount' => false]);
$merged->all();
// ['product_id' => 1, 'name' => 'Desk', 'price' => 100, 'discount' => false]
```
If the given array's keys are numeric, the values will be appended to the end of the collection:

``` php
$collection = collect(['Desk', 'Chair']);
$merged = $collection->merge(['Bookcase', 'Door']);
$merged->all();
// ['Desk', 'Chair', 'Bookcase', 'Door']
```
#### min()
The min method return the minimum value of a given key:

``` php 
$min = collect([['foo' => 10], ['foo' => 20]])->min('foo');
// 10
$min = collect([1, 2, 3, 4, 5])->min();
// 1
```
#### only()
The only method returns the items in the collection with the specified keys:
``` php
$collection = collect(['product_id' => 1, 'name' => 'Desk', 'price' => 100, 'discount' => false]);
$filtered = $collection->only(['product_id', 'name']);
$filtered->all();
// ['product_id' => 1, 'name' => 'Desk']
```
For the inverse of only, see the except method.
#### pluck()
The pluck method retrieves all of the collection values for a given key:
``` php
$collection = collect([
    ['product_id' => 'prod-100', 'name' => 'Desk'],
    ['product_id' => 'prod-200', 'name' => 'Chair'],
]);
$plucked = $collection->pluck('name');
$plucked->all();
// ['Desk', 'Chair']
```
You may also specify how you wish the resulting collection to be keyed:
``` php
$plucked = $collection->pluck('name', 'product_id');
$plucked->all();
// ['prod-100' => 'Desk', 'prod-200' => 'Chair']
```
#### pop()
The pop method removes and returns the last item from the collection:
``` php
$collection = collect([1, 2, 3, 4, 5]);
$collection->pop();
// 5
$collection->all();
// [1, 2, 3, 4]
```
#### prepend()
The prepend method adds an item to the beginning of the collection:
``` php
$collection = collect([1, 2, 3, 4, 5]);
$collection->prepend(0);
$collection->all();
// [0, 1, 2, 3, 4, 5]
```
You can optionally pass a second argument to set the key of the prepended item:
``` php
$collection = collect(['one' => 1, 'two', => 2]);
$collection->prepend(0, 'zero');
$collection->all();
// ['zero' => 0, 'one' => 1, 'two', => 2]
```
#### pull()
The pull method removes and returns an item from the collection by its key:
``` php
$collection = collect(['product_id' => 'prod-100', 'name' => 'Desk']);
$collection->pull('name');
// 'Desk'
$collection->all();
// ['product_id' => 'prod-100']
```
#### push()
The push method appends an item to the end of the collection:
``` php
$collection = collect([1, 2, 3, 4]);
$collection->push(5);
$collection->all();
// [1, 2, 3, 4, 5]
```
#### put()
The put method sets the given key and value in the collection:
``` php
$collection = collect(['product_id' => 1, 'name' => 'Desk']);
$collection->put('price', 100);
$collection->all();
// ['product_id' => 1, 'name' => 'Desk', 'price' => 100]
```
#### random()
The random method returns a random item from the collection:
``` php
$collection = collect([1, 2, 3, 4, 5]);
$collection->random();
// 4 - (retrieved randomly)
```
You may optionally pass an integer to random. If that integer is more than 1, a collection of items is returned:
``` php
$random = $collection->random(3);
$random->all();
// [2, 4, 5] - (retrieved randomly)
```
#### reduce()
The reduce method reduces the collection to a single value, passing the result of each iteration into the subsequent iteration:
``` php
$collection = collect([1, 2, 3]);
$total = $collection->reduce(function ($carry, $item) {
    return $carry + $item;
});
// 6
```
The value for $carry on the first iteration is null; however, you may specify its initial value by passing a second argument to reduce:
``` php
$collection->reduce(function ($carry, $item) {
    return $carry + $item;
}, 4);
// 10
```
#### reject()
The reject method filters the collection using the given callback. The callback should return true for any items it wishes to remove from the resulting collection:
``` php
$collection = collect([1, 2, 3, 4]);
$filtered = $collection->reject(function ($item) {
    return $item > 2;
});
$filtered->all();
// [1, 2]
```
For the inverse of the reject method, see the filter method.
#### reverse()
The reverse method reverses the order of the collection's items:
``` php
$collection = collect([1, 2, 3, 4, 5]);
$reversed = $collection->reverse();
$reversed->all();
// [5, 4, 3, 2, 1]
```
#### search()
The search method searches the collection for the given value and returns its key if found. If the item is not found, false is returned.
``` php
$collection = collect([2, 4, 6, 8]);
$collection->search(4);
// 1
```
The search is done using a "loose" comparison. To use strict comparison, pass true as the second argument to the method:
``` php
$collection->search('4', true);
// false
```
Alternatively, you may pass in your own callback to search for the first item that passes your truth test:
```php
$collection->search(function ($item, $key) {
    return $item > 5;
});
// 2
```
#### shift()
The shift method removes and returns the first item from the collection:
``` php
$collection = collect([1, 2, 3, 4, 5]);
$collection->shift();
// 1
$collection->all();
// [2, 3, 4, 5]
```
#### shuffle()
The shuffle method randomly shuffles the items in the collection:
``` php
$collection = collect([1, 2, 3, 4, 5]);
$shuffled = $collection->shuffle();
$shuffled->all();
// [3, 2, 5, 1, 4] // (generated randomly)
```
#### slice()
The slice method returns a slice of the collection starting at the given index:
``` php
$collection = collect([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
$slice = $collection->slice(4);
$slice->all();
// [5, 6, 7, 8, 9, 10]
```
If you would like to limit the size of the returned slice, pass the desired size as the second argument to the method:
``` php
$slice = $collection->slice(4, 2);
$slice->all();
// [5, 6]
```
The returned slice will have new, numerically indexed keys. If you wish to preserve the original keys, pass true as the third argument to the method.
#### sort()
The sort method sorts the collection:
``` php
$collection = collect([5, 3, 1, 2, 4]);
$sorted = $collection->sort();
$sorted->values()->all();
// [1, 2, 3, 4, 5]
```
The sorted collection keeps the original array keys. In this example we used the values method to reset the keys to consecutively numbered indexes.

For sorting a collection of nested arrays or objects, see the sortBy and sortByDesc methods.

If your sorting needs are more advanced, you may pass a callback to sort with your own algorithm. Refer to the PHP documentation on usort, which is what the collection's sort method calls under the hood.
#### sortBy()
The sortBy method sorts the collection by the given key:
``` php
$collection = collect([
    ['name' => 'Desk', 'price' => 200],
    ['name' => 'Chair', 'price' => 100],
    ['name' => 'Bookcase', 'price' => 150],
]);
$sorted = $collection->sortBy('price');
$sorted->values()->all();
/*
    [
        ['name' => 'Chair', 'price' => 100],
        ['name' => 'Bookcase', 'price' => 150],
        ['name' => 'Desk', 'price' => 200],
    ]
*/
```
The sorted collection keeps the original array keys. In this example we used the values method to reset the keys to consecutively numbered indexes.

You can also pass your own callback to determine how to sort the collection values:
``` php
$collection = collect([
    ['name' => 'Desk', 'colors' => ['Black', 'Mahogany']],
    ['name' => 'Chair', 'colors' => ['Black']],
    ['name' => 'Bookcase', 'colors' => ['Red', 'Beige', 'Brown']],
]);
$sorted = $collection->sortBy(function ($product, $key) {
    return count($product['colors']);
});
$sorted->values()->all();
/*
    [
        ['name' => 'Chair', 'colors' => ['Black']],
        ['name' => 'Desk', 'colors' => ['Black', 'Mahogany']],
        ['name' => 'Bookcase', 'colors' => ['Red', 'Beige', 'Brown']],
    ]
*/
```
#### sortByDesc()
This method has the same signature as the sortBy method, but will sort the collection in the opposite order.
#### splice()
The splice method removes and returns a slice of items starting at the specified index:
``` php
$collection = collect([1, 2, 3, 4, 5]);
$chunk = $collection->splice(2);
$chunk->all();
// [3, 4, 5]
$collection->all();
// [1, 2]
```
You may pass a second argument to limit the size of the resulting chunk:
``` php
$collection = collect([1, 2, 3, 4, 5]);
$chunk = $collection->splice(2, 1);
$chunk->all();
// [3]
$collection->all();
// [1, 2, 4, 5]
```
In addition, you can pass a third argument containing the new items to replace the items removed from the collection:
``` php
$collection = collect([1, 2, 3, 4, 5]);
$chunk = $collection->splice(2, 1, [10, 11]);
$chunk->all();
// [3]
$collection->all();
// [1, 2, 10, 11, 4, 5]
```
#### sum()
The sum method returns the sum of all items in the collection:
```php
collect([1, 2, 3, 4, 5])->sum();
// 15
```
If the collection contains nested arrays or objects, you should pass a key to use for determining which values to sum:
``` php
$collection = collect([
    ['name' => 'JavaScript: The Good Parts', 'pages' => 176],
    ['name' => 'JavaScript: The Definitive Guide', 'pages' => 1096],
]);
$collection->sum('pages');
// 1272
```
In addition, you may pass your own callback to determine which values of the collection to sum:
``` php
$collection = collect([
    ['name' => 'Chair', 'colors' => ['Black']],
    ['name' => 'Desk', 'colors' => ['Black', 'Mahogany']],
    ['name' => 'Bookcase', 'colors' => ['Red', 'Beige', 'Brown']],
]);
$collection->sum(function ($product) {
    return count($product['colors']);
});
// 6
```
#### take()
The take method returns a new collection with the specified number of items:
``` php
$collection = collect([0, 1, 2, 3, 4, 5]);
$chunk = $collection->take(3);
$chunk->all();
// [0, 1, 2]
```
You may also pass a negative integer to take the specified amount of items from the end of the collection:
``` php
$collection = collect([0, 1, 2, 3, 4, 5]);
$chunk = $collection->take(-2);
$chunk->all();
// [4, 5]
```
#### toArray()
The toArray method converts the collection into a plain PHP array.
``` php
$collection = collect(['name' => 'Desk', 'price' => 200]);
$collection->toArray();
/*
    [
        ['name' => 'Desk', 'price' => 200],
    ]
*/
```
#### toJson()
The toJson method converts the collection into JSON:
``` php
$collection = collect(['name' => 'Desk', 'price' => 200]);
$collection->toJson();
// '{"name":"Desk","price":200}'
```
#### transform()
The transform method iterates over the collection and calls the given callback with each item in the collection. The items in the collection will be replaced by the values returned by the callback:
``` php
$collection = collect([1, 2, 3, 4, 5]);
$collection->transform(function ($item, $key) {
    return $item * 2;
});
$collection->all();
// [2, 4, 6, 8, 10]
```
#### unique()
The unique method returns all of the unique items in the collection:
``` php
$collection = collect([1, 1, 2, 2, 3, 4, 2]);
$unique = $collection->unique();
$unique->values()->all();
// [1, 2, 3, 4]
```
The returned collection keeps the original array keys. In this example we used the values method to reset the keys to consecutively numbered indexes.

When dealing with nested arrays or objects, you may specify the key used to determine uniqueness:
``` php
$collection = collect([
    ['name' => 'iPhone 6', 'brand' => 'Apple', 'type' => 'phone'],
    ['name' => 'iPhone 5', 'brand' => 'Apple', 'type' => 'phone'],
    ['name' => 'Apple Watch', 'brand' => 'Apple', 'type' => 'watch'],
    ['name' => 'Galaxy S6', 'brand' => 'Samsung', 'type' => 'phone'],
    ['name' => 'Galaxy Gear', 'brand' => 'Samsung', 'type' => 'watch'],
]);
$unique = $collection->unique('brand');
$unique->values()->all();
/*
    [
        ['name' => 'iPhone 6', 'brand' => 'Apple', 'type' => 'phone'],
        ['name' => 'Galaxy S6', 'brand' => 'Samsung', 'type' => 'phone'],
    ]
*/
```
You may also pass your own callback to determine item uniqueness:

``` php
$unique = $collection->unique(function ($item) {
    return $item['brand'].$item['type'];
});
$unique->values()->all();
/*
    [
        ['name' => 'iPhone 6', 'brand' => 'Apple', 'type' => 'phone'],
        ['name' => 'Apple Watch', 'brand' => 'Apple', 'type' => 'watch'],
        ['name' => 'Galaxy S6', 'brand' => 'Samsung', 'type' => 'phone'],
        ['name' => 'Galaxy Gear', 'brand' => 'Samsung', 'type' => 'watch'],
    ]
*/
```
#### values()
The values method returns a new collection with the keys reset to consecutive integers:
``` php
$collection = collect([
    10 => ['product' => 'Desk', 'price' => 200],
    11 => ['product' => 'Desk', 'price' => 200]
]);
$values = $collection->values();
$values->all();
/*
    [
        0 => ['product' => 'Desk', 'price' => 200],
        1 => ['product' => 'Desk', 'price' => 200],
    ]
*/
```
#### where()
The where method filters the collection by a given key / value pair:
``` php
$collection = collect([
    ['product' => 'Desk', 'price' => 200],
    ['product' => 'Chair', 'price' => 100],
    ['product' => 'Bookcase', 'price' => 150],
    ['product' => 'Door', 'price' => 100],
]);

$filtered = $collection->where('price', 100);

$filtered->all();

/*
[
    ['product' => 'Chair', 'price' => 100],
    ['product' => 'Door', 'price' => 100],
]
*/
```
The where method uses strict comparisons when checking item values. Use the whereLoose method to filter using "loose" comparisons.
#### whereLoose()
This method has the same signature as the where method; however, all values are compared using "loose" comparisons.
#### zip()
The zip method merges together the values of the given array with the values of the collection at the corresponding index:
``` php
$collection = collect(['Chair', 'Desk']);
$zipped = $collection->zip([100, 200]);
$zipped->all();
// [['Chair', 100], ['Desk', 200]]
```