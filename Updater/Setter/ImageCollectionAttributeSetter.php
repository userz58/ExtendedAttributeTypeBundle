<?php

namespace U58\Bundle\ExtendedAttributeTypeBundle\Updater\Setter;

use Akeneo\Component\FileStorage\File\FileStorerInterface;
use Akeneo\Component\FileStorage\Model\FileInfoInterface;
use Akeneo\Component\FileStorage\Repository\FileInfoRepositoryInterface;
use Akeneo\Component\StorageUtils\Exception\InvalidPropertyException;
use Akeneo\Component\StorageUtils\Exception\InvalidPropertyTypeException;
use Pim\Component\Catalog\Builder\EntityWithValuesBuilderInterface;
use Pim\Component\Catalog\FileStorage;
use Pim\Component\Catalog\Model\AttributeInterface;
use Pim\Component\Catalog\Model\EntityWithValuesInterface;
use Pim\Component\Catalog\Updater\Setter\AbstractAttributeSetter;

class ImageCollectionAttributeSetter extends AbstractAttributeSetter
{
    /** @var FileStorerInterface */
    protected $storer;

    /** @var FileInfoRepositoryInterface */
    protected $repository;

    /**
     * @param EntityWithValuesBuilderInterface $entityWithValuesBuilder
     * @param FileStorerInterface              $storer
     * @param FileInfoRepositoryInterface      $repository
     * @param string[]                         $supportedTypes
     */
    public function __construct(
        EntityWithValuesBuilderInterface $entityWithValuesBuilder,
        FileStorerInterface $storer,
        FileInfoRepositoryInterface $repository,
        array $supportedTypes
    ) {
        parent::__construct($entityWithValuesBuilder);

        $this->storer = $storer;
        $this->repository = $repository;
        $this->supportedTypes = $supportedTypes;
    }

    /**
     * {@inheritdoc}
     *
     * Expected data input format :
     * [
     *     '/absolute/file/path/filename1.extension',
     *     '/absolute/file/path/filename2.extension',
     *     '/absolute/file/path/filename3.extension',
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
        foreach ($data as $filePath) {
            if (null === $file = $this->repository->findOneByIdentifier($filePath)) {
                $file = $this->handleFile($attribute, $filePath);
            }
            if($file) {
                $values[] = $file->getKey();
            }
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

        foreach ($data as $filePath) {
            if (null !== $filePath && !is_string($filePath)) {
                throw InvalidPropertyTypeException::stringExpected($attribute->getCode(), static::class, $filePath);
            }
        }
    }

    /**
     * Find file copy or store
     *
     * @param AttributeInterface $attribute
     * @param string             $filePath
     *
     * @return FileInfoInterface|null
     */
    protected function handleFile(AttributeInterface $attribute, $filePath) {
        if (null === $filePath) {
            return null;
        }

        $rawFile = new \SplFileInfo($filePath);
        if (!$rawFile->isFile()) {
            throw InvalidPropertyException::validPathExpected(
                $attribute->getCode(),
                static::class,
                $filePath
            );
        }

        $hash = sha1_file($rawFile->getPathname());
        //if (null !== $fileCopy = $this->repository->findOneBy(['hash' => $hash])) { //->findOneByHash($hash)) {
        if (null !== $fileCopy = $this->repository->findOneByHash($hash)) {
            return $fileCopy;
        }

        $file = $this->storer->store($rawFile, FileStorage::CATALOG_STORAGE_ALIAS);

        return $file;
    }
}
