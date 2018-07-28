<?php

namespace U58\Bundle\ExtendedAttributeTypeBundle\Value;

use Pim\Component\Catalog\Model\AbstractValue;
use Pim\Component\Catalog\Model\AttributeInterface;
use Pim\Component\Catalog\Model\ValueInterface;
use U58\Bundle\ExtendedAttributeTypeBundle\Model\ImageCollectionInterface;

class ImageCollectionValue extends AbstractValue implements ValueInterface
{
    /** @var ImageCollectionInterface */
    protected $data;

    /**
     * @param AttributeInterface $attribute
     * @param string $channel
     * @param string $locale
     * @param ImageCollectionInterface|null $data
     */
    public function __construct(AttributeInterface $attribute, $channel, $locale, ImageCollectionInterface $data = null)
    {
        $this->setAttribute($attribute);
        $this->setScope($channel);
        $this->setLocale($locale);

        $this->data = $data;
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function hasData()
    {
        if (!is_array($this->data) || is_null($this->data)) {
            return false;
        }

        if (count($this->data) > 0) {
            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        $items = [];
        foreach ($this->data as $file) {
            $items[] = $file->getKey();
        }

        return implode(', ', $items);
    }
}