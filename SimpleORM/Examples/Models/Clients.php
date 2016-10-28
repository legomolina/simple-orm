<?php

class Clients extends \SimpleORM\Model
{
    //Set the name of the model table
    protected static function getTableName() {
        return 'table_name';
    }
    //Optional. Set the column id. Default: 'id'
    protected static function getTableId() {
        return 'table_id';
    }

    //You can create custom methods for this model

    //public static function myCustomMethod(my_params) { }
}