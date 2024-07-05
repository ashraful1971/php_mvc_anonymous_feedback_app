<?php

namespace App\Core;

class Validation {

    private static $instance;

    private $isValid = true;
    private $error;
    
    private $data;
    private $options;

    /**
     * Constructor
     *
     * @param array $data
     * @param array $options
     */
    public function __construct(array $data, array $options)
    {
        $this->data = $data;
        $this->options = $options;
    }

    /**
     * Check if the validation has failed
     *
     * @return boolean
     */
    public function failed(): bool
    {
        return !$this->isValid;
    }
    
    /**
     * Make the validation and return the instance
     *
     * @param array $data
     * @param array $options
     * @return Validation
     */
    public static function make(array $data, array $options): self
    {
        self::$instance = new self($data, $options);

        self::$instance->preprocessData();

        self::$instance->validate();

        return self::$instance;
    }

    /**
     * Get the error message
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->error;
    }
    
    /**
     * Get the validated and sanitized data
     *
     * @return array
     */
    public function validatedData(): array
    {
        return $this->data;
    }

    /**
     * Run the valildation process
     *
     * @return boolean
     */
    private function validate(): bool
    {
        foreach($this->options as $key => $validationRules){
            foreach($validationRules as $rule){
                $this->checkValidationRule($key, $rule);

                if($this->failed()){
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Check a specific validation rule
     *
     * @param string $key
     * @param string $type
     * @return void
     */
    private function checkValidationRule(string $key, string $type): void
    {
        switch($type) {
            case 'required':
                $this->isRequired($key);
                break;
            case 'email':
                $this->isEmail($key);
                break;
            case 'password':
                $this->isPassword($key);
                break;
            case 'confirm':
                $this->isConfirmed($key);
                break;
            default:
                break;
        }
    }

    /**
     * Preprocess the data to avoid bugs
     *
     * @return void
     */
    private function preprocessData(): void
    {
        foreach($this->data as $key => $value){
            $this->data[$key] = trim($this->data[$key]);
            $this->data[$key] = htmlspecialchars($this->data[$key]);
        }
    }

    /**
     * Check if the field is required
     *
     * @param string $key
     * @return void
     */
    private function isRequired(string $key): void
    {
        if(empty($this->data[$key])){
            $this->isValid = false;
            $this->error = "$key is required!";
        }
    }

    /**
     * Check if the email is valid
     *
     * @param string $key
     * @return void
     */
    private function isEmail(string $key): void
    {
        if(!filter_var($this->data[$key], FILTER_VALIDATE_EMAIL)){
            $this->isValid = false;
            $this->error = "$key is not a valid email!";
        }
    }
    
    /**
     * Check if the pasword is good
     *
     * @param string $key
     * @return void
     */
    private function isPassword(string $key): void
    {
        $password = $this->data[$key];

        $minLength = 8;
        $hasUppercase = preg_match("#[A-Z]+#", $password);
        $hasLowercase = preg_match("#[a-z]+#", $password);
        $hasNumber = preg_match("#[0-9]+#", $password);
        $hasSpecialChar = preg_match("#\W+#", $password);

        if(!(strlen($password) >= $minLength && $hasUppercase && $hasLowercase && $hasNumber && $hasSpecialChar)){
            $this->isValid = false;
        }

        if(!(strlen($password) >= $minLength)){
            $this->error = "$key should be at least $minLength characters long.";
            return;
        }

        if(!$hasUppercase){
            $this->error = "$key should contain uppercase letter.";
            return;
        }
        
        if(!$hasLowercase){
            $this->error = "$key should contain lowercase letter.";
            return;
        }
        
        if(!$hasNumber){
            $this->error = "$key should contain number.";
            return;
        }
        
        if(!$hasSpecialChar){
            $this->error = "$key should contain special character.";
            return;
        }
    }
    
    /**
     * check if the field is confirmed
     *
     * @param string $key
     * @return void
     */
    private function isConfirmed(string $key): void
    {
        if(empty($this->data[$key])){
            $this->isValid = false;
            $this->error = "$key is required!";
        }
        
        elseif(empty($this->data['confirm_' . $key])){
            $this->isValid = false;
            $this->error = "confirm_$key is required!";
        }

        elseif($this->data[$key] !== $this->data['confirm_' . $key]){
            $this->isValid = false;
            $this->error = "$key doesn't match!";
        }
    }
}