<?php

namespace SimpleORM;

interface ResultSetInterface
{
    /**
     * ResultSet constructor. Parses the \mysqli_result into an array made of associative arrays.
     * @param \mysqli_result $queryResult The result obtained from a query
     */
    function __construct(\mysqli_result $queryResult);

    /**
     * Counts the number of registers found.
     * @return int Number of registers found
     */
    function count();

    /**
     * Checks if the field exists in the table.
     * @param string $field The name of the field to check
     * @return bool True if exists, false otherwise
     */
    function fieldExists($field);

    /**
     * Finds a value in a field and get all row.
     * @param string $field The name of the field to find in
     * @param mixed $value The value you are looking for
     * @return mixed An array containing the row of the result, false otherwise
     * @throws \OutOfRangeException If the field does not exist
     */
    function find($field, $value);

    /**
     * Searches in the ResultSet for the value given.
     * @param mixed $value The value you are looking for
     * @return mixed An array containing the row of the result, false otherwise
     */
    function findValue($value);

    /**
     * Sets the internal pointer of the ResultSet in the first element.
     * @return ResultSet
     */
    function first();

    /**
     * asdasd
     * Sets the internal pointer of the ResultSet in the last element.
     * @return ResultSet
     */
    function last();

    /**
     * Sets the internal pointer of the ResultSet in the next element.
     * @return ResultSet
     */
    function next();

    /**
     * Sets the internal pointer of the ResultSet in the previous element.
     * @return mixed
     */
    function prev();

    /**
     * Checks if the actual element is the first of the ResultSet.
     * @return bool True if is the first, false otherwise
     */
    function isFirst();

    /**
     * Checks if the actual element is the last of the ResultSet.
     * @return bool True if is the last, false otherwise
     */
    function isLast();

    /**
     * Sets the internal pointer of the ResultSet in the given result.
     * @param int $register Index of the register you are looking for
     * @return ResultSet
     * @throws \OutOfRangeException If the index does not exist
     */
    function goToRegister($register);

    /**
     * Get the field with the name given for the current register
     * @param string $field The field you want to retrieve
     * @return ResultSet
     * @throws \OutOfRangeException If field provided does not exists
     */
    function get($field);

    /**
     * Gets the fielfs given for the current register of the ResultSet.
     * @param array ...$fieldName The name of the fields you want to get
     * @return array The array containing the fields you selected
     * @throws \OutOfRangeException If the field does not exist
     */
    /*function getFields(...$fieldName);*/

    /**
     * Gets all fields of the current register of the ResultSet.
     * @return array The array containing the row
     */
    function getAll();

    /**
     * Checks if the value exists in the ResultSet.
     * @param mixed $value The value you are looking for
     * @return bool True if exists, false otherwise
     */
    function valueExists($value);
}