Laravel Recommendation system (LaravelCF)
=====
A php package allow you to find the est recommendation of your modules

Installation
------------

Install using composer:

```bash
composer require aldeebhasan/laravelcf
```

Basic Usage
-----------
LarvelCF package allow you to recommend data based on many algorithms including (Cosine,Weighted Cosine, Centered Cosine, SlopeOne).

In general we have two kind of recommender included in this package:

- item-based recommender
- user-based recommender

## Filling the data
The first step to build your recommendation system is to provide the dataset you want to work with.

In LarvelCF we have 4 type of data (PURCHASE, RATE, CART_ACTION, BOOKMARK). the purpose of these types is to enable you to handle different types of
data at the same time.

You can use the following to enter your recommender data:

```php
use \Aldeebhasan\LaravelCF\Facades\Recommender;
  
Recommender::addRating      ('user_1', 'product_1', 5);
Recommender::addCartAddition('user_1', 'product_1', 2); // like the quantity
Recommender::addPurchase    ('user_1', 'product_1', 5); // like the quantity
Recommender::addBookmark    ('user_1', 'product_1', 5);
```
## Instantiate the recommender
After entering your data, you can instantiate your desired recommender using our support facade:

```php
use \Aldeebhasan\LaravelCF\Facades\Recommender;
use \Aldeebhasan\LaravelCF\Enums\RelationType;

/* you cann also use any of RelationType::PURCHASE,RelationType::CART_ACTION,RelationType::BOOKMARK*/
Recommender::getItemBasedRecommender(RelationType::RATE); // to recommend similar products
//OR
Recommender::getUserBasedRecommender(RelationType::RATE);// to recommend similar users
```
## Get recommendations

Finally, to make your recommendations you will run the following code:

```php
use \Aldeebhasan\LaravelCF\Facades\Recommender;
use \Aldeebhasan\LaravelCF\Enums\RelationType;

/* you cann also use any of RelationType::PURCHASE,RelationType::CART_ACTION,RelationType::BOOKMARK*/
Recommender::getItemBasedRecommender(RelationType::RATE)
            ->setSimilarityFunction(Cosine::class)
            ->train()
            ->recommendTo('user_1')
```

For the `setSimilarityFunction`, you can provide the similarity algorithm, the missing value default values, and weather you want to fill the missing
methods or discard them.

Available Similarity algorithm:

- Cosine::class (package default)
- CosineCentered::class
- CosineWeighted::class

Available missing values replacement methods:

- MissingValue::ZERO (package default)
- MissingValue::MEAN
- MissingValue::MEDIAN

```php
use \Aldeebhasan\LaravelCF\Facades\Recommender;
use \Aldeebhasan\LaravelCF\Enums\RelationType;
use \Aldeebhasan\LaravelCF\Enums\MissingValue;
use \Aldeebhasan\LaravelCF\Similarity;

Recommender::getItemBasedRecommender(RelationType::RATE)
            // use Weighted cosine algorithm and replace the missing values with zero 
            ->setSimilarityFunction(CosineWeighted::class,MissingValue::ZERO,true)
             // use SlopeOne algorithm and replace the missing values with the mean 
            ->setSimilarityFunction(SlopeOne::class,MissingValue::MEAN,true)
            
```
## Full Example
```php
use \Aldeebhasan\LaravelCF\Facades\Recommender;
use \Aldeebhasan\LaravelCF\Enums\RelationType;
use \Aldeebhasan\LaravelCF\Enums\MissingValue;
use \Aldeebhasan\LaravelCF\Similarity;

Recommender::addRating(1, 'squid', 1);
Recommender::addRating(2, 'squid', 1);
Recommender::addRating(3, 'squid', 0.2);
Recommender::addRating(1, 'cuttlefish', 0.5);
Recommender::addRating(3, 'cuttlefish', 0.4);
Recommender::addRating(4, 'cuttlefish', 0.9);
Recommender::addRating(1, 'octopus', 0.2);
Recommender::addRating(2, 'octopus', 0.5);
Recommender::addRating(3, 'octopus', 1);
Recommender::addRating(4, 'octopus', 0.4);
Recommender::addRating(1, 'nautilus', 0.2);
Recommender::addRating(3, 'nautilus', 0.4);
Recommender::addRating(4, 'nautilus', 0.5);
$results = Recommender::getItemBasedRecommender(RelationType::RATE)
    ->setSimilarityFunction(CosineWeighted::class, MissingValue::MEAN)
    ->train()
    ->recommendTo('squid');


/**
recommendation results sorted by similarity: 
[ 
  "cuttlefish" => 0.89
  "nautilus" => 0.75
  "octopus" => 0.5
]
**/
```


## License

Laravel Recommendation system package is licensed under [The MIT License (MIT)](LICENSE).

## Security contact information

To report a security vulnerability, contact directly to the developer contact email [Here](mailto:aldeeb.91@gmail.com).
