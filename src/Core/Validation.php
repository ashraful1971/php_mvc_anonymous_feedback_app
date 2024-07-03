<?php

namespace App\Core;

class Validation {

    private static $instance;

    private $isValid = true;
    private $error;
    
    private $data;
    private $options;

    public function __construct(array $data, array $options)
    {
        $this->data = $data;
        $this->options = $options;
    }

    public function failed()
    {
        return !$this->isValid;
    }
    
    public static function make(array $data, array $options)
    {
        self::$instance = new self($data, $options);

        self::$instance->preprocessData();

        self::$instance->validate();

        return self::$instance;
    }
    public function getMessage()
    {
        return $this->error;
    }

    private function validate()
    {
        foreach($this->options as $key => $validationRules){
            $value = $this->data[$key];

            foreach($validationRules as $rule){
                $this->checkValidationRule($key, $rule);

                if($this->failed()){
                    return $this;
                }
            }
        }
    }

    private function checkValidationRule(string $key, string $type)
    {
        switch($type) {
            case 'required':
                $this->isRequired($key);
                break;
            case 'email':
                $this->isEmail($key);
                break;
            default:
                break;
        }
    }

    private function preprocessData()
    {
        foreach($this->data as $key => $value){
            $this->data[$key] = trim($this->data[$key]);
            $this->data[$key] = htmlspecialchars($this->data[$key]);
        }
    }

    private function isRequired(string $key)
    {
        if(empty($this->data[$key])){
            $this->isValid = false;
            $this->error = "$key is required!";
        }
    }

    private function isEmail(string $key)
    {
        if(!filter_var($this->data[$key], FILTER_VALIDATE_EMAIL)){
            $this->isValid = false;
            $this->error = "$key is not a valid email!";
        }
    }
}