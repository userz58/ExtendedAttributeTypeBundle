<?php

namespace U58\Bundle\ExtendedAttributeTypeBundle\Model;

use Akeneo\Component\FileStorage\Model\FileInfoInterface;

class Feature implements FeatureInterface
{
    /** @var FileInfoInterface $fileInfo */
    protected $fileInfo;

    /** @var string $title */
    protected $title;

    /** @var string $description */
    protected $description;

    /**
     * {@inheritdoc}
     */
    public function getFileInfo()
    {
        return $this->fileInfo;
    }

    /**
     * {@inheritdoc}
     */
    public function setFileInfo(FileInfoInterface $fileInfo)
    {
        $this->fileInfo = $fileInfo;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * {@inheritdoc}
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * {@inheritdoc}
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->getTitle();
    }

    /**
     * {@inheritdoc}
     */
    public function isEqual(FeatureInterface $item)
    {
        return $item->getFileInfo()->getKey() === $this->fileInfo->getKey() && $item->getTitle() === $this->title && $item->getDescription() === $this->description;
    }
}
