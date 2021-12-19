<?php

namespace App\Exception;

class AbstractApiException extends \Exception implements ApiExceptionInterface
{
    /**
     * @var array
     */
    private array $errors = [];

    /**
     * @return array
     */
    public function getErrors(): array
    {
       return $this->errors;
    }

    /**
     * @param array $errors
     */
    public function setErrors(array $errors): void
    {
        $this->errors = $errors;
    }

    /**
     * @param $key
     * @param $error
     */
    public function addError($key, $error): void
    {
        $this->errors[$key] = $error;
    }
}