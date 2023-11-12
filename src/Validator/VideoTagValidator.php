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

    public function validate($value, Constraint $constraint): void
    {
        /* @var App\Validator\VideoTag $constraint */

        if (null === $value || '' === $value) {
            return;
        }
        filter_var($value);
        htmlentities($value);
       if (!preg_match('/^<iframe/', $value) && (!preg_match('/^<embed/', $value))) {
            $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation();
        }
    }
}
