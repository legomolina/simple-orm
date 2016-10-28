<?php

namespace SimpleORM\Interfaces;

interface SqlFunctionsInterface
{
    /**
     * SqlFunctionsInterface constructor. Gets the table and the mysql connection.
     * @param \mysqli $connection The connection using in the queries
     */
    function __construct(\mysqli $connection);

    /**
     * Constructs a SELECT query with the fields given.
     * @param array ...$fields The fields used in the SELECT
     * @return SqlFunctions
     * @throws InvalidORMMethod If try to join SELECT with other query
     */
    function select(...$fields);

    /**
     * Constructs an UPDATE query with the info given.
     * @param array $values An array with the info to update the table
     * @return SqlFunctions
     * @throws InvalidORMArgument If $values is empty
     * @throws InvalidORMMethod If try to join INSERT with other query
     */
    function update($values);

    /**
     * Constructs an INSERT query with the info given.
     * @param array $fields An array with the info to insert into the table
     * @return SqlFunctions
     * @throws InvalidORMArgument If $values is empty
     * @throws InvalidORMArgument If $values is not a string, integer or double
     * @throws InvalidORMMethod If try to join INSERT with other query
     */
    function insert($fields);

    /**
     * Constructs an DELETE query.
     * @return SqlFunctions
     * @throws InvalidORMMethod If try to join DELETE with other query
     */
    function delete();

    /**
     * Adds WHERE clause to a query.
     * @param string $field The field you are asking for
     * @param string $operator The operator used to compare ['=', '<', '>', '<=', '>=']
     * @param mixed $value The value you want to compare
     * @return SqlFunctions
     * @throws InvalidORMMethod If try to join WHERE with any query
     * @throws InvalidORMMethod If try to join WHERE with INSERT query
     * @throws InvalidORMArgument If $operator is not allowed
     * @throws InvalidORMArgument If $values is not a string, integer or double
     */
    function where($field, $operator = '=', $value);

    /**
     * Adds the ORDER clause to a query.
     * @param string|array $field The field you want to order by.
     * @param string|array $order OPTIONAL The order to retrieve the results ['ASC', 'DESC']
     * @return SqlFunctions
     * @throws InvalidORMMethod If try to join ORDER with any query
     * @throws InvalidORMMethod If try to join ORDER with INSERT, UPDATE or DELETE query
     * @throws InvalidORMArgument If $order is not 'ASC' or 'DESC'
     */
    function order($field, $order = 'ASC');

    /**
     * Executes the query.
     * @return ResultSet|bool ResultSet if SELECT query, boolean otherwise
     */
    function execute();

    /**
     * Adds the LIMIT clause to a query
     * @param int $count Number of registers you want to get
     * @param int $offset OPTIONAL The register from which you want to start fetching
     * @return SqlFunctions
     * @throws InvalidORMMethod If try to join LIMIT with any query
     * @throws InvalidORMMethod If try to join LIMIT with INSERT, UPDATE or DELETE query
     */
    function get($count, $offset = 0);
}