<?php

namespace U58\Bundle\ExtendedAttributeTypeBundle\Validator\Constraints;

use Akeneo\Component\FileStorage\Model\FileInfoInterface;
use Symfony\Component\Validator\Constraint;
use Pim\Component\Catalog\Validator\Constraints\FileValidator;
use U58\Bundle\ExtendedAttributeTypeBundle\Model\FeatureInterface;

class FeatureFileValidator extends FileValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($item, Constraint $constraint)
    {
        if (!$item instanceof FeatureInterface) {
            return;
        }

        $fileInfo = $item->getFileInfo();

        if ($fileInfo instanceof FileInfoInterface && (null !== $fileInfo->getId() || null !== $fileInfo->getUploadedFile())) {
            $this->validateFileSize($fileInfo, $constraint);
            $this->validateFileExtension($fileInfo, $constraint);
        }
    }
}
