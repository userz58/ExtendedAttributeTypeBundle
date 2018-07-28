<?php

namespace U58\Bundle\ExtendedAttributeTypeBundle\Value;

use Pim\Component\Catalog\Model\AbstractValue;
use Pim\Component\Catalog\Model\AttributeInterface;
use Pim\Component\Catalog\Model\ValueInterface;
use U58\Bundle\ExtendedAttributeTypeBundle\Model\FeaturesInterface;

class FeaturesValue extends AbstractValue implements ValueInterface
{
    /** @var FeaturesInterface */
    protected $data;

    /**
     * @param AttributeInterface $attribute
     * @param string $channel
     * @param string $locale
     * @param FeaturesInterface|null $data
     */
    public function __construct(AttributeInterface $attribute, $channel, $locale, FeaturesInterface $data = null)
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
        foreach ($this->data as $item) {
            $items[] = $item->getFileInfo() ? $media->getFileInfo()->getKey() : '';
        }

        return implode(', ', $items);
    }
}