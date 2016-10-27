<?php

namespace SimpleORM;

use SimpleORM\Exceptions\InvalidORMArgument;
use SimpleORM\Interfaces\ResultSetInterface;

require 'Interfaces/ResultSet.php';

class ResultSet implements ResultSetInterface
{
    private $resultSet;

    public function __construct(\mysqli_result $queryResult)
    {
        while ($row = $queryResult->fetch_assoc()) {
            $this->resultSet[] = $row;
        }
    }

    public function count()
    {
        if($this->resultSet == null)
            return false;
        
        return count($this->resultSet);
    }

    public function fieldExists($field)
    {
        for ($i = 0; $i < count($this->resultSet); $i++) {
            if (array_key_exists($field, $this->resultSet[$i]))
                return true;
        }

        return false;
    }

    public function find($field, $value)
    {
        if (!array_key_exists($field, $this->resultSet[0]))
            throw new \OutOfRangeException('Field ' . $field . 'does not exists in table.');

        for ($i = 0; $i < count($this->resultSet); $i++) {
            if ($this->resultSet[$i][$field] == $value)
                return $this->resultSet[$i];
        }

        return false;
    }

    public function findValue($value)
    {
        for ($i = 0; $i < count($this->resultSet); $i++) {
            if (array_search($value, $this->resultSet[$i]))
                return $this->resultSet[$i];
        }

        return false;
    }

    public function first()
    {
        reset($this->resultSet);

        return $this;
    }

    public function hasMore()
    {
        if(next($this->resultSet))
            return true;

        return false;
    }

    public function get($field)
    {
        if(!array_key_exists($field, $this->resultSet[0]))
            throw new InvalidORMArgument('Field ' . $field . ' does not exist in result');

        return $this->resultSet[key($this->resultSet)][$field];
    }

    public function getAll()
    {
        return current($this->resultSet);
    }

    /*public function getFields(...$fieldName)
    {
        $colRegisters = array();

        foreach ($fieldName as $col) {
            if (!array_key_exists($col, $this->resultSet[0]))
                throw new \OutOfRangeException('The column ' . $col . ' does not exists');
        }

        if (count($fieldName) > 1) {
            for ($i = 0; $i < count($fieldName); $i++) {
                for ($j = 0; $j < count($this->resultSet); $j++) {
                    $colRegisters[$fieldName[$i]][] = $this->resultSet[$j][$fieldName[$i]];
                }
            }
        } else {
            for ($i = 0; $i < count($this->resultSet); $i++) {
                $colRegisters[] = $this->resultSet[$i][$fieldName[0]];
            }
        }

        return $colRegisters;
    }*/

    public function goToRegister($register)
    {
        if ($register < 0 || $register >= count($this->resultSet))
            throw new \OutOfRangeException('Index does not exists in the result');

        reset($this->resultSet);

        while (key($this->resultSet) != $register)
            next($this->resultSet);

        return $this;
    }

    public function isFirst()
    {
        if (!prev($this->resultSet))
            return true;

        next($this->resultSet);

        return false;
    }

    public function isLast()
    {
        if (!next($this->resultSet))
            return true;

        prev($this->resultSet);

        return false;
    }

    public function last()
    {
        end($this->resultSet);

        return $this;
    }

    public function next()
    {
        if (!next($this->resultSet))
            throw new \OutOfRangeException('There are not more registers to show');

        return $this;
    }

    public function prev()
    {
        if (!prev($this->resultSet))
            throw new \OutOfRangeException('There are not more registers to show');

        return $this;
    }

    public function valueExists($value)
    {
        for ($i = 0; $i < count($this->resultSet); $i++) {
            if (array_search($value, $this->resultSet[$i]))
                return true;
        }

        return false;
    }
}
