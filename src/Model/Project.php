<?php

namespace App\Model;

//implement JsonSerializable and use same style as Task class
class Project
{
    //instead of data, name all the table columns as class property, it will make model more understandable and also will help when you will use PDO::FETCH_CLASS mode
    /**
     * @var array
     */
    public $_data;
    
    public function __construct($data)
    {
        $this->_data = $data;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return (int) $this->_data['id'];
    }

    //use jsonSerialize instead
    /**
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->_data);
    }
}
