<?php

namespace App\RequestValidator;

use Symfony\Component\Validator\Constraints\Collection;

interface RequestValidatorInterface
{
    /**
     * @param $input
     * @param $groups
     */
    public function validate($input, array $groups);

    /**
     * @return Collection
     */
    public function rules(): Collection;
}