<?php

namespace SimpleORM;

require 'SqlFunctionsImpl.php';

class Model extends SqlFunctions
{
    protected static $table;
    protected static $idCol = 'id';
    private static $connection = null;
    private static $functions = null;

    public static function getConnection()
    {
        if(self::$connection == null) {
            global $connection;
            self::$connection = $connection;
        }
    }

    public static function query()
    {
        self::getConnection();
        if(self::$functions == null)
            self::$functions = new SqlFunctions(static::$table, self::$connection);

        return self::$functions;
    }

    public static function all()
    {
        self::getConnection();
        if(self::$functions == null)
            self::$functions = new SqlFunctions(static::$table, self::$connection);

        self::$functions->select('*');

        return self::$functions;
    }

    public static function findId($value)
    {
        self::getConnection();
        if(self::$functions == null)
            self::$functions = new SqlFunctions(static::$table, self::$connection);

        $result = self::all()->execute();

        return $result->find(static::$idCol, $value);
    }
}