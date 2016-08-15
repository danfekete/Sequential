# Sequential

This library is the successor of the SeqDB engine. The whole purpose of this library is to enable the generation of truly sequential IDs into buckets even when running parallel.

There are times when you need to make sure that when sequentially generating the IDs there are no gaps or repetitions.

## Installation

```
$ composer require danfekete/sequential
```

## Usage

### Framework agnostic

You don't need an underlying framework to use this library. 

```php
$data = new SQLite();
$seq = new Sequential($data);
$bucket = new Bucket($userID);
$nextID = $seq->generate($bucket);
```

### With Laravel 5.2+

After installing with composer run

```
$ php artisan vendor:publish --provider=danfekete\Sequential\SequentialServiceProvider
```

After this, you should see the `sequential.php` in your `config` directory. Make the neccessary adjustments (however it also works out of the box).

In your `config/app.php` add the following:

```php
'providers' => [
  // ...
  danfekete\Sequential\SequentialServiceProvider::class,
  // ...
];

'aliases' => [
  // ...
  'Sequential' =>  danfekete\Sequential\SequentialFacade::class,
  // ...
];
```

To generate an ID:

```php
$bucket = new Bucket($userID);
$nextID = Sequential::generate($bucket);
```

### Configuration

When using Laravel, the configuration is done in the `sequential.php` file, otherwise it is done in the constructor.

- `dataProvider` *(in constructor)* - the data object that implements the `DataProdvider` interface, more on data providers later
- `data_provider_class` *(in laravel config)* - the fully qualified name of the class that implements the `DataProvider` interface
- `sharedMutex`  - if `true`, then the mutex is shared with every bucket, meaning that only a single ID generation takes place at each given moment regardless of the bucket. Otherwise every bucket can run ID generation simultaneously which is the default behaviour.
- `incrementBy` - the amount to increment by when generating a new ID

### Data providers

The data providers implement the `DataProvider` interface. The interface has three methods that must be implemented:

- `getLastID(Bucket $bucket)` - returns the last ID generated for the given bucket
- `store(Bucket $bucket, $value)` - store the value for the bucket
- `reset(Bucket $bucket)` - reset the bucket value to `0`

You can use the default SQLite data provider or you can roll your own with any kind of advanced of logic. Buckets can contain any amount of data that can be used to query the last ID.



## Distributed use

One of the hardest part of sequential ID generation is distributed use. To enable this, there is a specialized version of the main class called `DistributedSequential` which uses the RedLock DLM algorithm from Redis. You'll need one more Redis servers to use and also the Predis library.

```php
$c1 = new Predis\Client('tcp://10.0.0.1:6379');
$c2 = new Predis\Client('tcp://10.0.0.2:6379');
$data = new SQLite();
$seq = new DistributedSequential([$c1, $c2], $data);
$bucket = new Bucket($userID);
$nextID = $seq->generate($bucket);
```

