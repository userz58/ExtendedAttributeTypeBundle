<?php

namespace  U58\Bundle\ExtendedAttributeTypeBundle\Comparator\Attribute;

use Pim\Component\Catalog\Comparator\ComparatorInterface;

class ArrayComparator implements ComparatorInterface
{
    /** @var array */
    protected $types;

    /**
     * @param array                   $types
     */
    public function __construct(array $types)
    {
        $this->types = $types;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($type)
    {
        return in_array($type, $this->types);
    }

    /**
     * {@inheritdoc}
     */
    public function compare($data, $originals)
    {
        $default = ['locale' => null, 'scope' => null, 'data' => []];
        $originals = array_merge($default, $originals);
        if (null === $data['data']) {
            $data['data'] = [];
        }
        if ($data['data'] === $originals['data']) {
            return null;
        }

        return $data;
    }
}
