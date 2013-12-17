<?php

/**
 *  PDOStatement2
 *
 * @Version 1.0.0  
 * @Author  CertaiN  
 * @License BSD 2-Clause  
 * @GitHub  http://github.com/Certainist/PDO2
 *
 * @require PDO2.class.php
 */
class PDOStatement2 extends PDOStatement {
    
    protected static $typeMap = array(
        'b' => PDO::PARAM_BOOL,
        'n' => PDO::PARAM_NULL,
        'i' => PDO::PARAM_INT,
        's' => PDO::PARAM_STR,
        'l' => PDO::PARAM_LOB,
        'L' => PDO2::PARAM_LIKE,
    );

    protected function __construct() {}

    public function execute($params = null) {
        parent::execute($params);
        return $this;
    }
    
    public function setFetchMode($mode, $params = null) {
        call_user_func_array(
            array('parent', __FUNCTION__),
            func_get_args()
        );
        return $this;
    }
    
    public function bind($name, $value, $type = PDO::PARAM_STR) {
        if (!is_string($name) || (string)(int)$name === $name) {
            ++$name;
        }
        if ($type === PDO2::PARAM_LIKE) {
            $value = '%' . addcslashes($value, '\\_%') . '%';
            $type = PDO::PARAM_STR;
        }
        $this->bindValue($name, $value, self::getTypeConst($type));
        return $this;
    }
    
    public function bindAll(array $values, $format = PDO::PARAM_STR) {
        if (is_string($format)) {
            $format = self::parseFormat($format);
        } elseif (!is_array($format)) {
            $format = array_fill_keys(array_keys($values), $format);
        }
        foreach ($values as $i => $value) {
            $type = is_array($format) && isset($format[$i]) ? $format[$i] : $format;
            $this->bind($i, $value, $type);
        }
        return $this;
    }
    
    private static function parseFormat($format) {
        $ret = array();
        foreach (explode(',', $format) as $pair) {
            $pair = explode('=', $pair, 2);
            if (isset($pair[1])) {
                $ret[self::pTrim($pair[0])] = self::pTrim($pair[1]);
            } else {
                $ret[] = self::pTrim($pair[0]);
            }
        }
        return $ret;
    }
    
    private static function getTypeConst($type) {
        if (in_array($type, self::$typeMap, true)) {
            return $type;
        }
        if (!is_scalar($type) || !isset(self::$typeMap[$type])) {
            return PDO::PARAM_STR;
        }
        return self::$typeMap[$type];
    }
    
    private static function pTrim($str) {
         return trim($str, " \t\n\r\0\x0b:");
    }
    
}