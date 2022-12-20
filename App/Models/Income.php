<?php

namespace App\Models;

use PDO;

/**
 * Incomes model
 */
class Income extends \Core\Model
{
    /**
     * Error messages
     * 
     * @var array
     */
    public $errors = [];

    /**
     * Class constructor
     * 
     * @param array $data Initial property values
     * 
     * @return void
     */
    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
    }

    /**
     * Save the validate data from the AddIncome form to database
     * 
     * @return boolean true if was success, false otherwise
     */
    public function save()
    {
        return true;
    }
}
