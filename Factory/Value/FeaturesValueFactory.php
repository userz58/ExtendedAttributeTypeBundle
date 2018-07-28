<?php

namespace U58\Bundle\ExtendedAttributeTypeBundle\Factory\Value;

use Akeneo\Component\StorageUtils\Exception\InvalidPropertyException;
use Akeneo\Component\StorageUtils\Exception\InvalidPropertyTypeException;
use Akeneo\Component\StorageUtils\Repository\IdentifiableObjectRepositoryInterface;
use Akeneo\Component\FileStorage\Model\FileInfoInterface;
use Akeneo\Component\FileStorage\Repository\FileInfoRepositoryInterface;
use Doctrine\Common\Collections\Collection;
use Pim\Component\Catalog\Model\AttributeInterface;
use Pim\Component\Catalog\Factory\Value\ValueFactoryInterface;

class FeaturesValueFactory implements ValueFactoryInterface
{
    /** @var string */
    protected $featuresClass;

    /** @var string */
    protected $featureClass;

    /** @var string */
    protected $productValueClass;

    /** @var string */
    protected $supportedAttributeType;

    /** @var FileInfoRepositoryInterface */
    protected $fileInfoRepository;

    /**
     * @param string                                $featuresClass
     * @param string                                $featureClass
     * @param string                                $productValueClass
     * @param string                                $supportedAttributeType
     * @param IdentifiableObjectRepositoryInterface $fileInfoRepository
     */
    public function __construct($featuresClass, $featureClass, $productValueClass, $supportedAttributeType, $fileInfoRepository)
    {
        $this->featuresClass = $featuresClass;
        $this->featureClass = $featureClass;
        $this->productValueClass = $productValueClass;
        $this->supportedAttributeType = $supportedAttributeType;
        $this->fileInfoRepository = $fileInfoRepository;
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

        $value = new $this->productValueClass(
            $attribute, $channelCode, $localeCode, $this->getCollection($attribute, $data)
        );

        return $value;
    }

    /**
     * @param AttributeInterface $attribute
     * @param array              $data
     *
     * @return Collection
     */
    protected function getCollection(AttributeInterface $attribute, array $data)
    {
        $features = new $this->featuresClass();

        foreach ($data as $item) {
            try {
                $feature = new $this->featureClass();
                $feature->setTitle($item['title']);
                $feature->setDescription($item['description']);
                if (null !== $file = $this->fileInfoRepository->findOneByIdentifier($item['filePath'])) {
                    $feature->setFileInfo($file);
                }
                $features->add($feature);
            } catch (InvalidPropertyException $e) {
                throw InvalidPropertyException::expectedFromPreviousException($attribute->getCode(), self::class, $e);
            }
        }

        return $features;
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

        foreach ($data as $item) {
            if (!is_array($item)) {
                throw InvalidPropertyTypeException::arrayOfArraysExpected(
                    $attribute->getCode(),
                    static::class,
                    $data
                );
            }

            if (!array_key_exists('filePath', $item)) {
                throw InvalidPropertyTypeException::arrayKeyExpected(
                    $attribute->getCode(),
                    'filePath',
                    static::class,
                    $data
                );
            }

            //if (!is_string($item[filePath])) {

            if (!array_key_exists('title', $item)) {
                throw InvalidPropertyTypeException::arrayKeyExpected(
                    $attribute->getCode(),
                    'title',
                    static::class,
                    $data
                );
            }

            //if (!is_string($item[title])) {

            if (!array_key_exists('description', $item)) {
                throw InvalidPropertyTypeException::arrayKeyExpected(
                    $attribute->getCode(),
                    'description',
                    static::class,
                    $data
                );
            }

            //if (!is_string($item[description])) {
        }
    }
}
