<?php

namespace App\Validator;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class VideoTagValidator extends ConstraintValidator
{

    public function __construct(private RequestStack $requestStack)
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        /* @var App\Validator\VideoTag $constraint */

        if (null === $value || '' === $value) {
            return;
        }

       if (!preg_match('/ src="([^"]*)"/', $value) && !str_starts_with("https://", $value)) {
            $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation();
        }
    }
}
