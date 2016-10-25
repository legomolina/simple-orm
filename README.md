# SimpleORM
SimpleORM is a little object-relational mapping library written in PHP. This ORM fits me perfect but probably is too small for your projects so you can fork it and improve it or send me a pull request so I can merge changes :D

### Installation
SimpleORM is hosted in packagist so you can get it from [Composer](https://getcomposer.org/ "Composer")

```
composer require legomolina/simple-orm
```

### Usage
Require the Composer autoload in your index:

```php
<?php
    require '../vendor/autoload.php';
```

Create your first model:

```php
<?php
    
    use \SimpleORM\Model;
    
    class MyModel extends Model
    {
        //Select the table which the model references
        protected static $table = "clients";
        //OPTIONAL. Select the id field for the table. Default: 'id'
        protected static $ioCol = 'clients_id';
    
        //Custom methods for this model
    
        //public static function myMethod(my_params) { }
    }
```

And you are ready to use SimpleORM!

```php
    $result = MyModel::query()->select('column')->order('column')->execute();
    
    for($i = 0; $i < $result->count(); $i ++) {
        echo $result->get('column');
        
        if(!$result->isLast())
            $result->next();
    }
```

### License
SimpleORM is licensed under the MIT license. See License File for more information.
