<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class BanWordValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (null === $value || '' === $value) {
            return;
        }

        $value = strtolower($value);
        foreach ($constraint->BanWords as $banWord) {
            if (str_contains($value, $banWord)) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ banword }}', $banWord)
                    ->addViolation();
                return;
            }
        }

    }
}
