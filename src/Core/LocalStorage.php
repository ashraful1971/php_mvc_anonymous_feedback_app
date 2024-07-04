<?php

namespace App\Core;

use App\Core\Contracts\DataStorage;

class LocalStorage implements DataStorage {

    private $data = [];
    private static $instance = null;
    private static $file;

    /**
     * Fetch the data
     */
    public function __construct($db_file)
    {
        self::$file = $db_file;
        $this->fetchData();
    }

    /**
     * Create and return the class instance
     *
     * @return self
     */
    public static function init($db_file = 'db.json'): self
    {
        if(!self::$instance){
            self::$instance = new self($db_file);
        }

        return self::$instance;
    }

    /**
     * Fetch and initialize the data
     *
     * @return void
     */
    public function fetchData(): void
    {
        if(file_exists(self::$file)){
            $this->data = json_decode(file_get_contents(self::$file), true);
        }
    }

    /**
     * Get all the records
     *
     * @param string $name
     * @return array
     */
    public function getAllRecords(string $table_name): array
    {
        return isset($this->data[$table_name]) ? $this->data[$table_name] : [];
    }

    /**
     * Create new record
     *
     * @param string $table_name
     * @param array $data
     * @return self
     */
    public function addNewRecord(string $table_name, array $data): self
    {
        $this->data[$table_name][] = $data;
        return $this;
    }

    /**
     * Save the data to the storage
     *
     * @return boolean
     */
    public function save(): bool
    {
        file_put_contents(self::$file, json_encode($this->data), JSON_PRETTY_PRINT);

        return true;
    }
}