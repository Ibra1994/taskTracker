<?php

namespace App\Model;

class Task implements \JsonSerializable
{
    //instead of data, name all the table columns as class property, it will make model more understandable and also will help when you will use PDO::FETCH_CLASS mode
    /**
     * @var array
     */
    private $_data;
    
    public function __construct($data)
    {
        $this->_data = $data;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->_data;
    }
}
