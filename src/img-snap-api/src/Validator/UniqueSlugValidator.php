<?php
/*
 * This file actually implements the validation logic provided by [UniqueSlug]
 */

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

use App\Repository\ImageRepository;

final class UniqueSlugValidator extends ConstraintValidator
{
    public function __construct(private ImageRepository $imageRepository){}

    public function validate(mixed $value, Constraint $constraint): void
    {
        /* @var UniqueSlug $constraint */

        if (null === $value || '' === $value) {
            return;
        }

        if ($this->imageRepository->findOneBy(['slug' => $value])) {
            $this->context->buildViolation($constraint->message)
                          ->setParameter('{{ value }}', $value)
                          ->addViolation();
        }
    }
}
