<?php

namespace SimpleORM\Interfaces;

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
     * @throws \NoRegistersORMException If the resultset is empty
     */
    function fieldExists($field);

    /**
     * Finds a value in a field and get all row.
     * @param string $field The name of the field to find in
     * @param mixed $value The value you are looking for
     * @return mixed An array containing the row of the result, false otherwise
     * @throws \OutOfRangeException If the field does not exist
     * @throws \NoRegistersORMException If the resultset is empty
     */
    function find($field, $value);

    /**
     * Searches in the ResultSet for the value given.
     * @param mixed $value The value you are looking for
     * @return mixed An array containing the row of the result, false otherwise
     * @throws \NoRegistersORMException If the resultset is empty
     * @throws \NoRegistersORMException If the resultset is empty
     */
    function findValue($value);

    /**
     * Custom method for while-looping through all registers.
     * @return mixed ResultSet instance, false if there's no more registers.
     */
    function loop();

    /**
     * Sets the internal pointer of the ResultSet in the first element.
     * @return ResultSet
     */
    function first();

    /**
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
     * Gets all fields of the current register of the ResultSet.
     * @return array The array containing the row
     */
    function getAll();
}