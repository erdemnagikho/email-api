<?php

namespace App\RequestValidator\Email;

use App\Entity\Email;
use App\RequestValidator\AbstractRequestValidator;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EmailValidator extends AbstractRequestValidator
{
    /**
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        parent::__construct($validator);
    }

    public function rules(): Collection
    {
        return new Assert\Collection([
            'period' => [
                new Assert\NotBlank(),
                new Assert\Length(['max' => 255, 'min' => 2]),
                new Assert\Type(['type' => 'string']),
                new Assert\Choice(
                    choices: [
                    Email::DAILY_PERIOD,
                    Email::WEEKLY_PERIOD,
                    Email::MONTHLY_PERIOD,
                    Email::YEARLY_PERIOD,
                ],
                    multiple: false,
                    min: 1,
                )
            ],
            'date_range' => [
                new Assert\NotBlank(),
                new Assert\Collection([
                    'start' => [
                        new Assert\NotBlank(),
                        new Assert\DateTime([
                            'format' => 'Y-m-d H:i:s',
                        ]),
                    ],
                    'end' => [
                        new Assert\NotBlank(),
                        new Assert\DateTime([
                            'format' => 'Y-m-d H:i:s',
                        ]),
                        new Assert\GreaterThan([
                           'propertyPath' => 'start',
                        ]),
                    ],
                ]),
            ]
        ]);
    }
}