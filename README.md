# SimpleORM
SimpleORM is a little object-relational mapping library written in PHP. This ORM fits me perfect but probably is too small for your projects so you can fork it and improve it or send me a pull request so I can merge changes :D

### Installation
SimpleORM is hosted in packagist so you can get it from [Composer](https://getcomposer.org/ "Composer")

```
composer require legomolina/simple-orm
```

### Configure
Require the Composer autoload in your index:

```php
require '../vendor/autoload.php';
```

Create your first model:
```php    
use \SimpleORM\Model;

class MyModel extends Model
{
    //Select the table which the model references
    protected static function getTableName()
    {
        return 'my_table';
    }
    //OPTIONAL. Select the id field for the table. Default: 'id'
    protected static function getTableId()
    {
        return 'my_table_id';
    }

    //Custom methods for this model

    //public static function myMethod(my_params) { }
}
```

Call config method from Model to pass mysqli connection params
```php
\SimpleORM\Model::config(array(
    'name' => 'my_database_name',
    'user' => 'my_user',
    'pass' => '*******',
    'host' => 'my_host',
    'charset' => 'charset'
));
```

And you are ready to use SimpleORM!

### Usage

#### <a name="q_access"></a>Quick access methods
SimpleORM has quick select methods to agilize common queries.
If you want to select all from your table you don't need to type
```php
$result = MyModel::query()->select('*')->execute();
```

Just use ```::all()``` method from ```\SimpleORM\Model```:
```php
$result = MyModel::all()->execute();
```

Also you can find the last value of any field of your table simply calling ```::getLastValue($field)``` from ```\SimpleORM\Model``` :
```php
$result = MyModel::getLastValue('my_field');
```
This is useful when you need the last id of your table to insert a new register when not using autoincrement.

Finally you can retrieve the register with _n_ id with ```::findId($id)``` from ```\SimpleORM\Model```:
```php
$result = MyModel::findId(12);
```


#### Select queries ([See quick access](#q_access))

If you want to select all data from your table.

```php
$result = MyModel::query()->select('*')->execute();
```

If you want to add conditions.
```php
$result = MyModel::all()->where('field', '=', 'value')->execute();
```

If you don't want to select all fields.
```php
$result = MyModel::query()->select('field_1', 'field_2')->where('field', '=', 'value')->execute();
```

If you want to order results by any field.
```php
$result = MyModel::all()->order('field', 'ASC')->execute();
$result = MyModel::all()->order(['field_1', 'field_2'], ['ASC', 'DESC'])->execute();
```

If you want to limit the results returned.
```php
$result = MyModel::all()->get(1)->execute(); //get 1 without offset
$result = MyModel::all()->get(1, 2)->execute(); //get 1 with offset 2
```

#### Data manipulation
If you want to insert values.

```php
$insert = array('field' => 'value', 'field' => 'value');
$result = MyModel::query()->insert($insert)->execute();

//$result => true if insertion is correct, false otherwise
```

If you want to delete items.
```php
$result = MyModel::query()->delete()->where('field', '=', 'value')->execute(); //important use where() with delete()

//$result => true if delete is correct, false otherwise
```

If you want to update items.
```php
$update = array('field' => 'value', 'field' => 'value');
$result = MyModel::query()->update($update)->where('field', '=', 'value')->execute(); //important use where() with update()

//$result => true if update is correct, false otherwise
```

### Working with ResultSet
ResultSet is a handler class for Select queries. It allows you to loop through results, find value or checks if exists some field.

#### Getting values from result
Easiest way doing this is with ResultSet->loop() method inside while loop.
```php
while($result->loop()) {
    $field_1 = $result->table_field_1;
    $field_2 = $result->table_field_2;
    
    ...
    
    $field_n = $result->table_field_n;
    
    //do something with the values
}
```
ResultSet->loop() loops through all registers in the ResultSet and each iteration it loads next register values.

You can also go to _n_ register executing
```php
$result->goToRegister(n);

$result->table_field_1;
$result->table_field_2;
```

Or you can loop manually with
```php
$result->first(); //loads first register
$result->next();  //loads next register if exists, otherwise it will return false
$result->prev();  //loads previous register if exists, otherwise it will return false
$result->last();  //loads last register
```

You can check manually if the current register is the first or the last.
```php
$result->isFirst(); //true | false
$result->isLast();  //true | false
```

#### Search for a value
With ResultSet you can search for a specific value in all results from database and return the register it belongs to.
```php
$result->find('table_field', 'find_this_value'); //returns false if doesn't find anything
```

Also you can know if a field exists.
```php
$result->fieldExists('table_field'); //true if exists, false otherwise
```

And finally you can search a value from ALL registers. It will return the first register that founds with this value
```php
$result->findValue('find_this_value');
```

### License
SimpleORM is licensed under the MIT license. See License File for more information.
