[![Latest Stable Version](https://poser.pugx.org/faguima/collection/v/stable)](https://packagist.org/packages/faguima/collection)
[![Total Downloads](https://poser.pugx.org/faguima/collection/downloads)](https://packagist.org/packages/faguima/collection)
[![License](https://poser.pugx.org/faguima/collection/license)](https://packagist.org/packages/faguima/collection)
[![Build Status](https://travis-ci.org/webmailcontatos/Collection-PHP.svg?branch=master)](https://travis-ci.org/webmailcontatos/Collection-PHP)
# Collection-PHP
 Excellent tool to make your experience with php array much cleaner and comfortable
 Small demonstration :
``` php
$collection = new Collection([1,2,3]);
$collection->sum() == 6
$collection->toJson() == {1,2,3}
$filter = $collection->filter(function($item)){
                            return $item > 1;
                         }
$filter->sum() = 5;

```