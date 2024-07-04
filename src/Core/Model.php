<?php

namespace App\Core;

use App\Core\Contracts\DataStorage;
use App\Core\LocalStorage;

class Model {
    protected $table_name;
    protected $columns;
    protected $attributes = [];
    protected DataStorage $storage;

    public function __construct(array $data = [])
    {
        $this->storage = LocalStorage::init(DB_PATH);
        $this->attributes = array_merge($this->attributes, $data);
    }

    public function __get($name)
    {
        if(isset($this->attributes[$name])){
            return $this->attributes[$name];
        }

        return null;
    }

    public function __set($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    public static function all()
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
    
    public static function find($column_name, $value)
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
    
    public static function findAll($column_name, $value)
    {
        $records = self::all();

        if(!$records){
            return null;
        }

        $filteredRecords = [];

        foreach($records as $record){
            if($record->$column_name == $value){
                $filteredRecords[] = $record;
            }
        }

        return $filteredRecords;

    }
    
    public static function create(array $data)
    {
        $instance = new static($data);
        $instance->save();

        return $instance;
    }
    
    private function save()
    {
        $data = $this->getStoreableData();
        return $this->storage->addNewRecord($this->table_name, $data);
    }

    private function getStoreableData()
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