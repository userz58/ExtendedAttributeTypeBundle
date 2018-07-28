<?php

namespace U58\Bundle\ExtendedAttributeTypeBundle\Controller\Rest;

use Symfony\Component\Intl\Intl;
use Symfony\Component\HttpFoundation\JsonResponse;
use Pim\Bundle\UserBundle\Context\UserContext;

/**
 * Country rest controller
 */
class CountryController
{
    /** @var UserContext */
    protected $userContext;

    /**
     * @param UserContext                  $userContext
     */
    public function __construct(UserContext $userContext) {
        $this->userContext = $userContext;
    }

    /**
     * Get the list of all countries
     *
     * @return JsonResponse
     */
    public function listAction()
    {
        $locale = $this->userContext->getCurrentLocaleCode();
        $countries = Intl::getRegionBundle()->getCountryNames($locale);

        return new JsonResponse($countries);
    }

    /**
     * Get country
     *
     * @param string $identifier
     *
     * @return JsonResponse
     */
    public function getAction($identifier)
    {
        $locale = $this->userContext->getCurrentLocaleCode();
        $country = Intl::getRegionBundle()->getCountryName($identifier, $locale);

        return new JsonResponse($country);
    }
}
