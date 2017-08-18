<?php

class MinC_Db_Adapter_Pdo_Pgsql extends Zend_Db_Adapter_Pdo_Pgsql
{
    public function query($sql, $bind = array())
    {
        if (empty($bind) && $sql instanceof Zend_Db_Select) {
            $bind = $sql->getBind();
        }

        if (is_array($bind)) {
            foreach ($bind as $name => $value) {
                if (!is_numeric($name) && !preg_match('/^:/', $name)) {
                    $newName = ":$name";
                    unset($bind[$name]);
                    $bind[$newName] = $value;
                }
            }
        }

        try {
            $this->_connect();
            if ($sql instanceof Zend_Db_Select) {
                if (empty($bind)) {
                    $bind = $sql->getBind();
                }
                $sql = $sql->assemble();
            }

            if (!is_array($bind)) {
                $bind = array($bind);
            }

            $sql = str_ireplace('.dbo', '', $sql);

            $stmt = $this->prepare($sql);
            $stmt->execute($bind);
            $stmt->setFetchMode($this->_fetchMode);

            return $stmt;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function treatWhereConditionsDoubleQuotes($condition)
    {
        $colunaLimpa = trim($condition);
        $separator = '=';

        if ($colunaLimpa && strpos($colunaLimpa, $separator) !== false) {
            $arrayColumn = explode($separator, $condition);
            if (strpos($arrayColumn[0], '"') === false) {
                $conditionOne = $this->addDoubleQuote(trim($arrayColumn[0]));
                $conditionTwo = trim($arrayColumn[1]);
//                $conditionTwo = $this->addSimpleQuote($arrayColumn[1]);
                $condition = "{$conditionOne} {$separator} {$conditionTwo}";
            }

        } elseif ($colunaLimpa && strpos($colunaLimpa, 'in') !== false) {

            $separator = 'in';
            $arrayColumn = explode($separator, $condition);
            if (substr(trim($arrayColumn[1]), 0, 1) == '(' && strpos($arrayColumn[0], '"') === false) {
                $conditionOne = $this->addDoubleQuote(trim($arrayColumn[0]));
                $conditionTwo = trim($arrayColumn[1]);
                $condition = "{$conditionOne} {$separator} {$conditionTwo}";
            }
        }

        return $condition;
    }

    public function treatJoinConditionDoubleQuotes($condition)
    {
        $arrayConditions = explode('AND', $condition);
        if (count($arrayConditions) > 1) {
            $condition = '';
            foreach ($arrayConditions as $arrayCondition) {
                if (!empty($condition)) {
                    $condition .= ' AND ';
                }
                $condition .= $this->treatJoinConditionDoubleQuotes($arrayCondition);
            }
        } else {
            $cleanCondition = trim($condition);
            $separator = '=';
            if ($cleanCondition && strpos($cleanCondition, $separator) !== false) {
                $arrayColunas = explode($separator, $condition);
                $coluna1 = $this->addDoubleQuote(trim($arrayColunas[0]));
                $coluna2 = $this->addDoubleQuote(trim($arrayColunas[1]));
                $condition = "{$coluna1} {$separator} {$coluna2}";
            }

        }
        return $condition;
    }

    protected function addDoubleQuote($field)
    {
        if (substr($field, 0, 1) != '"') {
            $hasDot = strpos($field, '.');
            if ($hasDot !== false) {
                $fieldPieces = explode('.', $field);
                $field = '';
                foreach ($fieldPieces as $fieldPiece) {
                    if (!empty($field)) {
                        $field .= '.';
                    }
                    $field .= $this->addDoubleQuote($fieldPiece);
                }
            } else {
                if (!is_numeric($field) && strpos($field, "'") === false) {
                    $field = '"' . $field . '"';
                }
            }
        }
        return $field;
    }

    protected function addSimpleQuote($field)
    {
        $field = trim($field);
        if (
            strpos($field, "'") === false
            && strpos($field, '"') === false
            && strpos($field, '(') === false
            && strpos($field, ')') === false
            && strpos($field, ')') === false
        ) {

            $field = "'{$field}'";
        }

        return $field;
    }

    public function treatColumnsDoubleQuotes($arrayColumns)
    {
        foreach ($arrayColumns as &$column) {
            $columnParts = explode(' as ', $column);
            if (count($columnParts) > 1) {
                $columnFirstPart = $this->addDoubleQuote($columnParts[0]);
                $columnSecondPart = " as {$columnParts[1]}";
                $column = $columnFirstPart . $columnSecondPart;
            } else {
                $column = $this->addDoubleQuote($column);
            }
        }
    }
}