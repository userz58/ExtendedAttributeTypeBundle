<?php

namespace U58\Bundle\ExtendedAttributeTypeBundle\Updater\Setter;

use Akeneo\Component\StorageUtils\Exception\InvalidPropertyTypeException;
use Pim\Component\Catalog\Model\AttributeInterface;
use Pim\Component\Catalog\Model\EntityWithValuesInterface;

class FeaturesAttributeSetter extends ImageCollectionAttributeSetter
{
    /**
     * {@inheritdoc}
     *
     * Expected data input format :
     * [
     *     {
     *         "filePath":  '/absolute/file/path/filename.extension',
     *         "title": 'Title 1',
     *         "description": 'text text text text text'
     *     },
     *     {
     *         "filePath":  '/absolute/file/path/filename2.extension',
     *         "title": 'Title 2',
     *         "description": 'text text text text text'
     *     },
     *     {
     *         "filePath":  '/absolute/file/path/filename3.extension',
     *         "title": 'Title 3',
     *         "description": 'text text text text text'
     *     }
     * ]
     */
    public function setAttributeData(
        EntityWithValuesInterface $entityWithValues,
        AttributeInterface $attribute,
        $data,
        array $options = []
    ) {
        $options = $this->resolver->resolve($options);

        $this->checkData($attribute, $data);

        $values = [];
        foreach ($data as $item) {
            if (null === $file = $this->repository->findOneByIdentifier($item['filePath'])) {
                $file = $this->handleFile($attribute, $item['filePath']);
            }

            $values[] = [
                'filePath'    => null !== $file ? $file->getKey() : null,
                'title'       => $item['title'],
                'description' => $item['description']
            ];
        }

        $this->entityWithValuesBuilder->addOrReplaceValue(
            $entityWithValues,
            $attribute,
            $options['locale'],
            $options['scope'],
            $values
        );
    }

    /**
     * @param AttributeInterface $attribute
     * @param mixed              $data
     *
     * @throws InvalidPropertyTypeException
     */
    protected function checkData(AttributeInterface $attribute, $data)
    {
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

            if (!array_key_exists('title', $item)) {
                throw InvalidPropertyTypeException::arrayKeyExpected(
                    $attribute->getCode(),
                    'title',
                    static::class,
                    $data
                );
            }

            if (!array_key_exists('description', $item)) {
                throw InvalidPropertyTypeException::arrayKeyExpected(
                    $attribute->getCode(),
                    'description',
                    static::class,
                    $data
                );
            }
        }
    }
}
