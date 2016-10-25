<?php

namespace SimpleORM;

use SimpleORM\Exceptions\InvalidORMArgument;

require 'SqlFunctionsImpl.php';

class Model extends SqlFunctions
{
    protected static $table;
    protected static $idCol = 'id';
    private static $connection = null;
    private static $functions = null;

    private static $DB_USER;
    private static $DB_NAME;
    private static $DB_PASS;
    private static $DB_HOST;

    public static function config($config)
    {
        if(!array_key_exists('host', $config) || !array_key_exists('name', $config) || !array_key_exists('pass', $config) || !array_key_exists('user', $config))
            throw new InvalidORMArgument("It's necessary insert required fields");

        self::$DB_HOST = $config['host'];
        self::$DB_NAME = $config['name'];
        self::$DB_PASS = $config['pass'];
        self::$DB_USER = $config['user'];
    }

    public static function getConnection()
    {
        if(self::$connection == null) {
            $connection = new \mysqli(self::$DB_HOST, self::$DB_USER, self::$DB_PASS, self::$DB_NAME);
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