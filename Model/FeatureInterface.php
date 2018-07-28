<?php

namespace U58\Bundle\ExtendedAttributeTypeBundle\Model;

use Akeneo\Component\FileStorage\Model\FileInfoInterface;

/**
 * Media collection file interface (backend type entity)
 */
interface FeatureInterface
{
    /**
     * Get fileinfo object
     *
     * @return FileInfoInterface|null
     */
    public function getFileInfo();

    /**
     * @param FileInfoInterface $fileInfo
     */
    public function setFileInfo(FileInfoInterface $fileInfo);

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @param string $title
     */
    public function setTitle($title);

    /**
     * @return string
     */
    public function getDescription();

    /**
     * @param string $description
     */
    public function setDescription($description);

    /**
     * Is equal
     *
     * @param FeatureInterface $item
     *
     * @return bool
     */
    public function isEqual(FeatureInterface $item);
}
