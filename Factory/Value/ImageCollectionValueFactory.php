<?php

namespace U58\Bundle\ExtendedAttributeTypeBundle\Factory\Value;

use Akeneo\Component\StorageUtils\Exception\InvalidPropertyException;
use Akeneo\Component\StorageUtils\Exception\InvalidPropertyTypeException;
use Akeneo\Component\FileStorage\Model\FileInfoInterface;
use Akeneo\Component\FileStorage\Repository\FileInfoRepositoryInterface;
use Pim\Component\Catalog\Model\AttributeInterface;
use Pim\Component\Catalog\Factory\Value\ValueFactoryInterface;
use U58\Bundle\ExtendedAttributeTypeBundle\Model\ImageCollection;

class ImageCollectionValueFactory implements ValueFactoryInterface
{
    /** @var FileInfoRepositoryInterface */
    protected $fileInfoRepository;

    /** @var string */
    protected $productValueClass;

    /** @var string */
    protected $supportedAttributeType;

    /**
     * @param FileInfoRepositoryInterface $fileInfoRepository,
     * @param string                      $productValueClass
     * @param string                      $supportedAttributeType
     */
    public function __construct(FileInfoRepositoryInterface $fileInfoRepository, $productValueClass, $supportedAttributeType)
    {
        $this->fileInfoRepository = $fileInfoRepository;
        $this->productValueClass = $productValueClass;
        $this->supportedAttributeType = $supportedAttributeType;
    }

    /**
     * {@inheritdoc}
     */
    public function create(AttributeInterface $attribute, $channelCode, $localeCode, $data)
    {
        $this->checkData($attribute, $data);

        if (null === $data) {
            $data = [];
        }

        $files = new ImageCollection();
        foreach ($data as $key) {
            $files->add($this->getFileInfo($attribute, $key));
        }

        $value = new $this->productValueClass($attribute, $channelCode, $localeCode, $files);

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($attributeType)
    {
        return $attributeType === $this->supportedAttributeType;
    }

    /**
     * Checks if data are valid.
     *
     * @param AttributeInterface $attribute
     * @param mixed              $data
     *
     * @throws InvalidPropertyTypeException
     */
    protected function checkData(AttributeInterface $attribute, $data)
    {
        if (null === $data || [] === $data) {
            return;
        }

        if (!is_array($data)) {
            throw InvalidPropertyTypeException::arrayExpected(
                $attribute->getCode(),
                static::class,
                $data
            );
        }

        foreach ($data as $key) {
            if (!is_string($key)) {
                throw InvalidPropertyTypeException::stringExpected(
                    $attribute->getCode(),
                    static::class,
                    $key
                );
            }
        }
    }

    /**
     * @param AttributeInterface $attribute
     * @param string             $data
     *
     * @throws InvalidPropertyException
     * @return FileInfoInterface
     */
    protected function getFileInfo(AttributeInterface $attribute, $data)
    {
        $file = $this->fileInfoRepository->findOneByIdentifier($data);

        if (null === $file) {
            throw InvalidPropertyException::validEntityCodeExpected(
                $attribute->getCode(),
                'fileinfo key',
                'The media does not exist',
                static::class,
                $data
            );
        }

        return $file;
    }
}
