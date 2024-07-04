<?php

namespace App\Core;

use App\Core\Contracts\DataStorage;

class LocalStorage implements DataStorage {

    private static $instance = null;
    private static $file;

    /**
     * Set the db file path and name
     */
    public function __construct($db_file)
    {
        self::$file = $db_file;
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
     * Fetch data
     *
     * @return array
     */
    public function fetchData(): array
    {
        $data = [];

        if(file_exists(self::$file)){
            $data = json_decode(file_get_contents(self::$file), true);
        }

        return $data;
    }

    /**
     * Get all the records
     *
     * @param string $table_name
     * @return array
     */
    public function getAllRecords(string $table_name): array
    {
        $data = $this->fetchData();
        return isset($data[$table_name]) ? $data[$table_name] : [];
    }

    /**
     * Create new record
     *
     * @param string $table_name
     * @param array $data
     * @return bool
     */
    public function addNewRecord(string $table_name, array $data): bool
    {
        $oldData = $this->fetchData();
        $oldData[$table_name][] = $data;
        
        return $this->save($oldData);
    }

    /**
     * Save the data to the storage
     *
     * @param array $data
     * @return boolean
     */
    private function save($data): bool
    {
        file_put_contents(self::$file, json_encode($data), JSON_PRETTY_PRINT);

        return true;
    }
}