<?php

namespace SimpleORM;

use SimpleORM\Exceptions as Exc;
use SimpleORM\Interfaces\SqlFunctionsInterface;

require 'Exceptions/ORMException.php';
require 'Interfaces/SqlFunctions.php';
require 'ResultSetImpl.php';

class SqlFunctions implements SqlFunctionsInterface
{
    private $table;
    private $connection;

    private $statement;
    private $statementType = null;
    private $bindParams = array(
        'types' => '',
        'vars' => array()
    );

    const WHERE_AND = 'AND';
    const WHERE_OR  = 'OR';

    const ORDER_ASC = 'ASC';
    const ORDER_DESC = 'DESC';

    const STMT_SELECT = 'select';
    const STMT_INSERT = 'insert';
    const STMT_UPDATE = 'update';
    const STMT_DELETE = 'delete';
    const STMT_NULL = null;

    public function __construct(\mysqli $connection)
    {
        $this->connection = $connection;
        return $this;
    }

    protected function setTableName($table)
    {
        $this->table = $table;
    }

    public function select(...$fields)
    {
        if($this->statementType != self::STMT_NULL)
            throw new Exc\InvalidORMMethod('Can not join select if you are making another query');

        $this->statementType = self::STMT_SELECT;
        $this->statement = "SELECT ";

        for($i = 0; $i < count($fields); $i ++) {
            $this->statement .= $fields[$i] . ",";
        }

        $this->statement = rtrim($this->statement, ',') . " FROM " . $this->table;

        return $this;
    }

    public function insert($values)
    {
        if(!is_array($values) || empty($values))
            throw new Exc\InvalidORMArgument('Values must be filled');

        if($this->statementType != self::STMT_NULL)
            throw new Exc\InvalidORMMethod('Can not join insert if you are making another query');

        $this->statementType = self::STMT_INSERT;

        $insertFields = "";
        $insertValues = "";

        foreach($values as $field => $value) {

            if(gettype($value) == 'string') {
                $this->bindParams['types'] .= 's';
            } elseif (gettype($value) == 'double') {
                $this->bindParams['types'] .= 'd';
            } elseif (gettype($value) == 'integer') {
                $this->bindParams['types'] .= 'i';
            } else {
                throw new Exc\InvalidORMArgument('Value must be an integer, double or string');
            }

            $this->bindParams['vars'][] = $value;

            $insertFields .= "$field,";
            $insertValues .= "?,";
        }

        $this->statement = "INSERT INTO " . $this->table . "(" . rtrim($insertFields, ',') . ") VALUES (" . rtrim($insertValues, ',') . ")";

        return $this;
    }

    public function update($values)
    {
        if(!is_array($values) || empty($values))
            throw new Exc\InvalidORMArgument('Values must be filled');

        if($this->statementType != self::STMT_NULL)
            throw new Exc\InvalidORMMethod('Can not join update if you are making another query');

        $this->statementType = self::STMT_UPDATE;
        $this->statement = "UPDATE " . $this->table . " SET ";

        foreach($values as $field => $value) {
            if(gettype($value) == 'string')
                $value = "'$value'";

            $this->statement .= "$field = $value,";
        }

        $this->statement = rtrim($this->statement, ',');

        return $this;
    }

    public function delete()
    {
        if($this->statementType != self::STMT_NULL)
            throw new Exc\InvalidORMMethod('Can not join delete if you are making another query');

        $this->statementType = self::STMT_DELETE;

        $this->statement = "DELETE FROM " . $this->table;

        return $this;
    }



    public function where()
    {
        if($this->statementType == self::STMT_NULL)
            throw new Exc\InvalidORMMethod('Can not join WHERE clause if you are not making another query');

        if($this->statementType == self::STMT_INSERT)
            throw new Exc\InvalidORMMethod('WHERE clause can not be applied with INSERT');

        $this->bindParams['vars'] = [];
        $this->bindParams['types'] = '';

        $this->statement .= " WHERE ";
        return $this;
    }

    public function and()
    {
        $this->statement .= " ".self::WHERE_AND." ";
        return $this;
    }

    public function or()
    {
        $this->statement .= " ".self::WHERE_OR." ";
        return $this;
    }

    protected function whereFilter($filterType, $fields)
    {
        if($this->statementType == self::STMT_NULL)
            throw new Exc\InvalidORMMethod('Can not join WHERE clause if you are not making another query');

        if($this->statementType == self::STMT_INSERT)
            throw new Exc\InvalidORMMethod('WHERE clause can not be applied with INSERT');

        $validOperators = ['=', '<', '>', '<=', '>=', '!=', 'LIKE'];

        if(!count($fields))
            throw new Exc\InvalidORMMethod('There must be conditions for WHERE');

        $conditions = [];

        foreach ($fields as $condRow) {
            if(count($condRow) !== 3)
                throw new Exc\InvalidORMMethod('Each condition must have three parts - operand1, operator, operand2' . print_r($fields,1));

            $field = $condRow[0];
            $operator = $condRow[1];
            $value = $condRow[2];

            if(!in_array($operator, $validOperators))
                throw new Exc\InvalidORMArgument('Operator must be one of SQL allowed. ' . $operator . ' was given');

            if(gettype($value) == 'string') {
                $value = "$value";
                $this->bindParams['types'] .= 's';
            } elseif (gettype($value) == 'double') {
                $this->bindParams['types'] .= 'd';
            } elseif (gettype($value) == 'integer') {
                $this->bindParams['types'] .= 'i';
            } else {
                throw new Exc\InvalidORMArgument('Value must be an integer, double or string');
            }

            $this->bindParams['vars'][] = &$value;

            $conditions[] = "$field $operator ?";
        }

        $this->statement .= '( ' . implode($filterType, $conditions) . ' )';

        return $this;
    }

    public function andFilter($field, $operator = '=', $value)
    {
        if (!is_array($field)) {
            $fields = [[$field, $operator, $value]];
        } else {
            $fields = $field;
        }

        return $this->whereFilter(self::WHERE_AND, $fields);
    }

    public function orFilter($field, $operator = '=', $value)
    {
        if (!is_array($field)) {
            $fields = [[$field, $operator, $value]];
        } else {
            $fields = $field;
        }

        return $this->whereFilter(self::WHERE_OR, $fields);
    }

    public function order($field, $order = 'ASC')
    {
        if($this->statementType == self::STMT_NULL)
            throw new Exc\InvalidORMMethod('Can not join ORDER clause if you are not making another query');

        if($this->statementType != self::STMT_SELECT)
            throw new Exc\InvalidORMMethod('ORDER can be used only in SELECT queries');

        $this->statement .= " ORDER BY";

        if (is_array($field)) { //varios campos

            for ($i = 0; $i < count($field); $i++) {

                if(is_array($order)) {
                    $orderField = $order[$i];
                } else {
                    $orderField = $order;
                }

                if (strtoupper($orderField) !== self::ORDER_DESC && strtoupper($orderField) !== self::ORDER_ASC) {
                    throw new Exc\InvalidORMArgument('Order must be ASC or DESC, not: ' . $orderField);
                }

                $this->statement .= " $field[$i] $orderField ,";

            }

            $this->statement = rtrim($this->statement, ',');

        } elseif (gettype($field) == 'string') {
            if (strtoupper($order) !== self::ORDER_DESC && strtoupper($order) !== self::ORDER_ASC) {
                throw new Exc\InvalidORMArgument('Order must be ASC or DESC, not: ' . $order);
            }

            $this->statement .= " $field $order";
        }

        return $this;
    }

    public function show()
    {
        echo $this->statement;
    }

    public function getBindParams()
    {
        var_dump($this->bindParams);
    }

    public function execute()
    {
        if($this->statementType == self::STMT_NULL)
            throw new Exc\InvalidORMMethod('There is not any query to execute');

        $query = $this->connection->prepare($this->statement);
        $params = array();
        $params[] = &$this->bindParams['types'];

        for($i = 0; $i < count($this->bindParams['vars']); $i ++) {
            $params[] = &$this->bindParams['vars'][$i];
        }

        if($this->bindParams['types'] !== '')
            call_user_func_array(array($query, "bind_param"), $params);

        $this->bindParams['types'] = "";
        $this->bindParams['vars'] = array();

        $execute = $query->execute();

        if(!$execute)
            return $query->error;

        if($this->statementType == self::STMT_SELECT) {
            $this->statementType = self::STMT_NULL;

            $result = $query->get_result();
            $query->close();

            return new ResultSet($result);
        }
        $this->statementType = self::STMT_NULL;

        $query->close();
        return $execute;
    }

    public function get($count, $offset = 0)
    {
        if($this->statementType == self::STMT_NULL)
            throw new Exc\InvalidORMMethod('Can not join LIMIT clause if you are not making another query');

        if($this->statementType != self::STMT_SELECT)
            throw new Exc\InvalidORMMethod('LIMIT can be used only in SELECT queries');

        $this->statement .= " LIMIT $offset, $count";

        return $this;
    }
}
