<?php
namespace App\core;

use Exception;
use App\repositories\DatabaseRepository;

/**
 * The base Model class that handle some functionalities such as validating data by specific rules defined in the model.
 *
 * @author Ali Tavafi <ali.tavafii@gmail.com>
 */
class Model extends DatabaseRepository
{
    /**
     * @var string Table name.
     */
    protected $table;

    /**
     * Default validation rules.
     * 
     * @return array default validation rules.
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * Validate given data based on defined the rules in the called class.
     * 
     * @return bool data is valid.
     * 
     * @throws Exception if data is not valid.
     */
    public function validate($data): bool
    {
        foreach (static::rules() as $validation => $attributes) {
            foreach ($attributes as $key => $attribute) {
                $this->validateAttribute($validation, $attribute, $data[$attribute]);
            }
        }

        return true;
    }

    /**
     * Validate each attributes value based on the given rule.
     * 
     * @param string $rule Validation rule.
     * @param mixed $attribute Attribute name.
     * @param mixed $value Attribute value to validate.
     * 
     * @return bool attributes value is valid.
     * 
     * @throws Exception if attributes value is not valid, or validation rule is not defined.
     */
    private function validateAttribute(string $rule, $attribute, $value): bool
    {
        switch ($rule) {
            case 'string':
                return $this->validateString($attribute, $value);
                break;
            case 'integer':
                return $this->validateInteger($attribute, $value);
                break;
            case 'required':
                return $this->validateRequired($attribute, $value);
                break;
            case 'email':
                return $this->validateEmail($attribute, $value);
                break;
            case 'unique':
                return $this->validateUnique($attribute, $value);
                break;
            case 'text':
                return $this->validateText($attribute, $value);
                break;
            
            default:
                throw new Exception("Validation rule is not defined!");
                break;
        }
    }

    /**
     * Check and validate if value data type is string.
     * Check and validate if value length is less than or equal to 255 characters.
     * 
     * @param mixed $attribute Attribute name.
     * @param mixed $value Attribute value.
     * 
     * @return bool if attribute value is string and less than or equal 255 characters.
     * 
     * @throws Exception if attribute value is not string, or is more than 255 characters.
     */
    public function validateString($attribute, $value): bool
    {
        if (!is_string($value)) {
            throw new Exception("{$attribute} must be string!");
        }

        if (strlen($value) > 255) {
            throw new Exception("{$attribute} must be maximum 255 characters!");
        }

        return true;
    }

    /**
     * Check and validate if value data type is string.
     * Check and validate if value length is less than or equal to 65535.
     * 
     * @param mixed $attribute Attribute name.
     * @param mixed $value Attribute value.
     * 
     * @return bool if attribute value is string and less than or equal 65535 characters.
     * 
     * @throws Exception if attribute value is not string, or is more than 65535 characters.
     */
    public function validateText($attribute, $value): bool
    {
        $value = trim($value);
        
        if (!is_string($value)) {
            throw new Exception("{$attribute} must be string!");
        }

        if (strlen($value) > 65535) {
            throw new Exception("{$attribute} must be maximum 65,535 characters!");
        }

        return true;
    }

    /**
     * Check and validate if value data type is integer and unsigned.
     * 
     * @param mixed $attribute Attribute name.
     * @param mixed $value Attribute value.
     * 
     * @return bool if attribute value is integer and unsigned.
     * 
     * @throws Exception if attribute value is not integer, or is not unsigned.
     */
    public function validateInteger($attribute, $value): bool
    {
        if (!filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]])) {
            throw new Exception("{$attribute} must be integer! {$value} defined!");
        }

        return true;
    }

    /**
     * Check and validate if value is not null or empty.
     * 
     * @param mixed $attribute Attribute name.
     * @param mixed $value Attribute value.
     * 
     * @return bool if attribute value is not null or empty.
     * 
     * @throws Exception if attribute value is null or empty.
     */
    public function validateRequired($attribute, $value): bool
    {
        if (!$value) {
            throw new Exception("{$attribute} must be defined!");
        }

        return true;
    }

    /**
     * Check and validate if value is a correct email.
     * 
     * @param mixed $attribute Attribute name.
     * @param mixed $value Attribute value.
     * 
     * @return bool if attribute value is a valid email.
     * 
     * @throws Exception if attribute value is not a valid email.
     */
    public function validateEmail($attribute, $value): bool
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("{$attribute} is not correct! {$value} defined!");
        }

        return true;
    }

    /**
     * Check and validate if value is unique in the called class.
     * 
     * @param mixed $attribute Attribute name.
     * @param mixed $value Attribute value.
     * 
     * @return bool if attribute value is unique.
     * 
     * @throws Exception if attribute value is not unique.
     */
    public function validateUnique($attribute, $value): bool
    {
        $record = $this->where($attribute, '=', $value)
            ->select()
            ->one();

        if ($record) {
            throw new Exception("{$attribute} must be unique!");
        }

        return true;
    }

    /**
     * Generate password hash by BCRYPT algorithm.
     * 
     * @param string $password The password to be encrypted.
     * 
     * @return string password hash.
     */
    public function generatePasswordHash(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }
    
    /**
     * Compare and validate if password and confirm password matches.
     * Check if the password is strong enough. It means it is at least 8 characters, it has at least one upercase letter, number or some spechial characters.
     * 
     * @param string $password The password to be checked and stored.
     * @param string $confirmPassword The confirme password to be checked with the password.
     * 
     * @return bool if the password and confirm password matches.
     * 
     * @throws Exception if the password is not strong enough, or passwords don't match.
     */
    public function validatePassword(string $password, string $confirmPassword): bool
    {
        if ($password != $confirmPassword) {
            throw new Exception("Passwords do not match!");
        }

        if (!preg_match('/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/', $password)) {
            throw new Exception("Your password is not strong enough!");
        }

        return true;
    }
    
    /**
     * Check if the input password matches the stored password hash.
     * 
     * @param string $password The input password.
     * @param string|null $passwordHash The stored password hash.
     * 
     * @return bool if password matches the stored hash.
     * 
     * @throws Exception if password does not match the stored hash.
     */
    public function validatePasswordHash(string $password, ?string $passwordHash): bool
    {
        if (!password_verify($password, $passwordHash)) {
            throw new Exception("Username or Passwords is not correct!");
        }

        return true;
    }

    /**
     * Get one record of called class by selected column.
     * 
     * @param string $column The column to be selected.
     * @param mixed $value The value of the selected column to fetch.
     * @param string $orderBy Sort result of the query ascending (ASC) or descending (DESC)
     * 
     * @return array|null|bool query result.
     */
    public function getByColumn(string $column, $value, string $orderBy = 'ASC')
    {
        $class = static::class;
        
        return (new $class)->where($column, '=', $value)
            ->orderBy($column, $orderBy)
            ->select()
            ->one();
    }

    /**
     * Get all records of called class by selected column.
     * 
     * @param string $column The column to be selected.
     * @param mixed $value The value of the selected column to fetch.
     * @param string $orderBy Sort result of the query ascending (ASC) or descending (DESC)
     * 
     * @return array|null|bool query result.
     */
    public function getAllByColumn(string $column, $value, string $orderBy = 'ASC')
    {
        $class = static::class;

        return (new $class)->where($column, '=', $value)
            ->orderBy($column, $orderBy)
            ->select()
            ->all();
    }

    /**
     * Check spechial characters to prevent XSS and code execution attacks.
     * The entire value must save, but in view pages it has to check and sanitize for security reason.
     * 
     * @param mixed $value The value to be checked and convert its spechail characters to browser format data.
     * 
     * @return mixed converted value.
     */
    public static function checkSpecialChars($value)
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}