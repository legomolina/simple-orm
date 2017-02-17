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
    private static $DB_CHARSET;

    public static function config($config)
    {
        if (!array_key_exists('host', $config) || !array_key_exists('name', $config) || !array_key_exists('pass', $config) || !array_key_exists('user', $config))
            throw new InvalidORMArgument("It's necessary insert required fields");

        self::$DB_HOST = $config['host'];
        self::$DB_NAME = $config['name'];
        self::$DB_PASS = $config['pass'];
        self::$DB_USER = $config['user'];
        self::$DB_CHARSET = (array_key_exists('charset', $config) && $config['charset'] !== "") ? $config['charset'] : 'utf8';
    }

    public static function findId($value)
    {
        self::getConnection();
        if (self::$functions == null)
            self::$functions = new SqlFunctions(self::$connection);

        self::$functions->setTableName(static::getTableName());

        $result = self::all()->execute();

        return $result->find(static::getTableId(), $value);
    }

    protected static function getConnection()
    {
        if (self::$connection == null) {
            $connection = new \mysqli(self::$DB_HOST, self::$DB_USER, self::$DB_PASS, self::$DB_NAME);
            self::$connection = $connection;

            self::$connection->set_charset(self::$DB_CHARSET);
        }
    }

    abstract protected static function getTableName();

    public static function all()
    {
        self::getConnection();
        if (self::$functions == null)
            self::$functions = new SqlFunctions(self::$connection);

        self::$functions->setTableName(static::getTableName());

        self::$functions->select('*');

        return self::$functions;
    }

    protected static function getTableId()
    {
        return 'id';
    }

    public static function getLastValue($field)
    {
        self::getConnection();
        if (self::$functions == null)
            self::$functions = new SqlFunctions(self::$connection);

        self::$functions->setTableName(static::getTableName());

        $result = self::query()->select($field)->order($field, 'DESC')->get(1)->execute();

        return $result->$field;
    }

    public static function query()
    {
        self::getConnection();
        if (self::$functions == null)
            self::$functions = new SqlFunctions(self::$connection);

        self::$functions->setTableName(static::getTableName());

        return self::$functions;
    }
}