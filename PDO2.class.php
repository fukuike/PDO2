<?php

/**
 *  PDO2
 *
 * @Version 1.0.0  
 * @Author  CertaiN  
 * @License BSD 2-Clause  
 * @GitHub  http://github.com/Certainist/PDO2
 *
 * @require PDO2Statement.class.php
 */
class PDO2 extends PDO {
    
    const PARAM_LIKE = -1;
    
    public function __construct(
        $dsn,
        $username = null,
        $password = null,
        array $driver_options = array()
    ) {
        parent::__construct($dsn, $username, $password, $driver_options);
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $this->setAttribute(PDO::ATTR_STATEMENT_CLASS, array('PDOStatement2', array()));
    }
    
}