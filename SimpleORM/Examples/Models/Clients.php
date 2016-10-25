<?php

class Clients extends \SimpleORM\Model
{
    //Set the name of the model table
    protected static $table = 'clients';
    //Optional. Set the column id. Default: 'id'
    protected static $idCol = 'client_id';

    //You can create custom methods for this model

    //public static function myCustomMethod(my_params) { }
}