<?php

namespace U58\Bundle\ExtendedAttributeTypeBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraints\File as BaseFile;

class FeatureFile extends BaseFile
{
    /** @var array */
    public $allowedExtensions = [];

    /** @var string */
    public $extensionsMessage = 'The file extension is not allowed (allowed extensions: %extensions%).';
}
