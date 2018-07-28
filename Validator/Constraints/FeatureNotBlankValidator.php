<?php

namespace U58\Bundle\ExtendedAttributeTypeBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class FeatureNotBlankValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($object, Constraint $constraint)
    {
        if (empty($object->getTitle())) {
            $this->context->buildViolation($constraint->titleMessage)
                ->atPath('title')
                ->addViolation();
        }

        if (empty($object->getDescription())) {
            $this->context->buildViolation($constraint->descriptionMessage)
                ->atPath('description')
                ->addViolation();
        }

        if (is_null($object->getFileInfo())) {
            $this->context->buildViolation($constraint->fileMessage)
                ->atPath('file')
                ->addViolation();
        }
    }
}
