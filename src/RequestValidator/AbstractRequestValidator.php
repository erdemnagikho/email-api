<?php

namespace App\RequestValidator;

use App\Exception\ValidationException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

abstract class AbstractRequestValidator implements RequestValidatorInterface
{
    /**
     * @var ValidatorInterface
     */
    protected ValidatorInterface $validator;

    /**
     * @var string
     */
    protected string $validationExceptionMessage = 'Invalid Parameters';

    /**
     * AbstractRequestValidator constructor.
     *
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param            $input
     * @param array|null $groups
     *
     * @throws ValidationException
     */
    public function validate($input, array $groups = null)
    {
        $violations = $this->validator->validate($input, $this->rules(), $groups);

        $this->violation($violations);
    }

    /**
     * @param ConstraintViolationListInterface $constraintViolationList
     *
     * @throws ValidationException
     */
    public function violation(ConstraintViolationListInterface $constraintViolationList)
    {
        if ($constraintViolationList->count() > 0) {
            throw new ValidationException($this->getValidationExceptionMessage(), $constraintViolationList);
        }
    }

    /**
     * @return string
     */
    public function getValidationExceptionMessage(): string
    {
        return $this->validationExceptionMessage;
    }

    /**
     * @param string $validationExceptionMessage
     *
     * @return string
     */
    public function setValidationExceptionMessage(string $validationExceptionMessage): string
    {
        return $this->validationExceptionMessage = $validationExceptionMessage;
    }
}