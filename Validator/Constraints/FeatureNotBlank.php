<?php

namespace U58\Bundle\ExtendedAttributeTypeBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class FeatureNotBlank extends Constraint
{
    /** @var int */
    public $titleLength = 100;

    /** @var int */
    public $descriptionLength = 255;

    /** @var string */
    public $fileMessage = 'Image should not be blank.';
    public $titleMessage = 'Title should not be blank.';
    public $descriptionMessage = 'Description should not be blank.';

    public $titleLengthMessage = 'Title is too long. (It should have {{ limit }} characters or less.)';
    public $descriptionLengthMessage = 'Description is too long. (It should have {{ limit }} characters or less.)';

}
