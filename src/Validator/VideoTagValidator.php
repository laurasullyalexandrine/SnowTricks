<?php

namespace App\Validator;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class VideoTagValidator extends ConstraintValidator
{
    const EMBED = "<embed";
    const IFRAME = "<iframe";

    public function __construct(private RequestStack $requestStack)
    {
    }

    public function validate($value, Constraint $constraint)
    {
        /* @var App\Validator\VideoTag $constraint */

        if (null === $value || '' === $value) {
            return;
        }

       if (!preg_match('/^<iframe/', $value) && (!preg_match('/^<embed/', $value))) {
            $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation();
        }
    }
}
