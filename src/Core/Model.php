<?php

namespace App\Core;

use App\Core\Contracts\DataStorage;
use App\Core\LocalStorage;

class Model {
    protected $table_name;
    protected $columns;
    protected $attributes = [];
    protected DataStorage $storage;

    /**
     * Constructor to init the props
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->storage = LocalStorage::init(DB_PATH);
        $this->attributes = array_merge($this->attributes, $data);
    }

    /**
     * Get the virtual property value
     *
     * @param string $name
     * @return mixed
     */
    public function __get(string $name): mixed
    {
        if(isset($this->attributes[$name])){
            return $this->attributes[$name];
        }

        return null;
    }

    /**
     * Set the virtual property value
     *
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function __set(string $name, mixed $value): void
    {
        $this->attributes[$name] = $value;
    }

    /**
     * Get all the records
     *
     * @return array
     */
    public static function all(): array
    {
        $instance = new static();
        $records = $instance->storage->getAllRecords($instance->table_name);

        $collection = [];
        if($records){
            foreach($records as $record){
                $collection[] = new static($record);
            }
        }

        return $collection;
    }
    
    /**
     * Find the first value by column name and value
     *
     * @param string $column_name
     * @param mixed $value
     * @return Model|null
     */
    public static function find(string $column_name, mixed $value): Model|null
    {
        $records = self::all();

        if(!$records){
            return null;
        }

        foreach($records as $record){
            if($record->$column_name == $value){
                return $record;
            }
        }

        return null;

    }
    
    /**
     * Find all the records by column name and value
     *
     * @param string $column_name
     * @param mixed $value
     * @return array
     */
    public static function findAll(string $column_name, mixed $value): array
    {
        $records = self::all();

        if(!$records){
            return [];
        }

        $filteredRecords = [];

        foreach($records as $record){
            if($record->$column_name == $value){
                $filteredRecords[] = $record;
            }
        }

        return $filteredRecords;

    }
    
    /**
     * Create a new record
     *
     * @param array $data
     * @return Model
     */
    public static function create(array $data): Model
    {
        $instance = new static($data);
        $instance->save();

        return $instance;
    }
    
    /**
     * Save the model to the storage
     *
     * @return boolean
     */
    private function save(): bool
    {
        $data = $this->getStoreableData();
        return $this->storage->addNewRecord($this->table_name, $data);
    }

    /**
     * Get the data that matched the table schema
     *
     * @return array
     */
    private function getStoreableData(): array
    {
        $data = [];
        $data['id'] = generateUniqueId();

        foreach($this->columns as $column){
            $data[$column] = $this->attributes[$column] ?? '';
        }

        $data['created_at'] = date(DATETIME_FORMAT);

        return $data;
    }
}