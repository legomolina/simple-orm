<?php

namespace SimpleORM;

use SimpleORM\Exceptions\InvalidORMArgument;

require 'SqlFunctionsImpl.php';

abstract class Model extends SqlFunctions
{
    protected static $connection = null;
    protected static $functions = null;

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

    abstract protected static function getTableName();
    protected static function getTableId()
    {
        return 'id';
    }

    protected static function getConnection()
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
            self::$functions = new SqlFunctions(self::$connection);

        self::$functions->setTableName(static::getTableName());

        return self::$functions;
    }

    public static function all()
    {
        self::getConnection();
        if(self::$functions == null)
            self::$functions = new SqlFunctions(self::$connection);

        self::$functions->setTableName(static::getTableName());

        self::$functions->select('*');

        return self::$functions;
    }

    public static function findId($value)
    {
        self::getConnection();
        if(self::$functions == null)
            self::$functions = new SqlFunctions(self::$connection);

        self::$functions->setTableName(static::getTableName());

        $result = self::all()->execute();

        return $result->find(self::getTableId(), $value);
    }
}