<?php

namespace SimpleORM;

use SimpleORM\Interfaces\ResultSetInterface;

require 'Interfaces/ResultSet.php';

class ResultSet implements ResultSetInterface
{
    private $resultSet;
    private $firstCall;

    public function __construct(\mysqli_result $queryResult)
    {
        while ($row = $queryResult->fetch_object()) {
            $this->resultSet[] = $row;
        }

        if ($this->count())
            $this->fillFieldVariables($this->resultSet[0]);

        $this->firstCall = true;
    }

    public function count()
    {
        if ($this->resultSet == null)
            return false;

        return count($this->resultSet);
    }

    private function fillFieldVariables($register)
    {
        foreach ($register as $key => $value)
            $this->{$key} = $value;
    }

    public function find($field, $value)
    {
        if (!$this->count())
            throw new \NoRegistersORMException('There\'s no registers available');

        if (!$this->fieldExists($field))
            return false;

        for ($i = 0; $i < count($this->resultSet); $i++) {
            if ($this->resultSet[$i]->$field === $value)
                return $this->resultSet[$i];
        }

        return false;
    }

    public function fieldExists($field)
    {
        if (!$this->count())
            throw new \NoRegistersORMException('There\'s no registers available');

        for ($i = 0; $i < count($this->resultSet); $i++) {
            if ($this->resultSet[$i]->$field !== null)
                return true;
        }

        return false;
    }

    public function loop()
    {
        if(!$this->count())
            return false;
        
        if (!$this->firstCall) {
            $value = next($this->resultSet);
            if (!$value) {
                $this->firstCall = true;
                return false;
            }

            $this->fillFieldVariables($value);
        }

        $this->firstCall = false;

        return $this;

    }

    public function findValue($value)
    {
        if (!$this->count())
            throw new \NoRegistersORMException('There\'s no registers available');

        if ($this->count()) {
            for ($i = 0; $i < count($this->resultSet); $i++) {
                foreach (get_object_vars($this->resultSet[$i]) as $property)
                    if ($property === $value)
                        return $this->resultSet[$i];
            }
        }

        return false;
    }

    public function first()
    {
        $value = reset($this->resultSet);
        $this->fillFieldVariables($value);

        return $this;
    }

    public function getAll()
    {
        return current($this->resultSet);
    }

    public function goToRegister($register)
    {
        if ($register < 0 || $register >= count($this->resultSet))
            throw new \OutOfRangeException('Index does not exists in the result');

        $this->first();

        while (key($this->resultSet) != $register)
            $this->next();

        return $this;
    }

    public function isFirst()
    {
        if (!$this->prev()) {
            $this->first();
            return true;
        }

        $this->next();

        return false;
    }

    public function prev()
    {
        $value = prev($this->resultSet);
        if (!$value)
            return false;

        $this->fillFieldVariables($value);

        return $this;
    }

    public function next()
    {
        $value = next($this->resultSet);
        if (!$value)
            return false;

        $this->fillFieldVariables($value);

        return $this;
    }

    public function isLast()
    {
        if (!$this->next()) {
            $this->last();
            return true;
        }

        $this->prev();

        return false;
    }

    public function last()
    {
        $value = end($this->resultSet);
        $this->fillFieldVariables($value);

        return $this;
    }
}
