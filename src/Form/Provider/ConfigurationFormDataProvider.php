<?php

declare(strict_types=1);

namespace Vex6\OpenArticles\Form\Provider;

use PrestaShop\PrestaShop\Core\Configuration\DataConfigurationInterface;
use PrestaShop\PrestaShop\Core\Form\FormDataProviderInterface;

class ConfigurationFormDataProvider implements FormDataProviderInterface
{

    /**
     * @var DataConfigurationInterface
     */
    private $articleConfiguration;

    /**
     * @param DataConfigurationInterface $articleConfiguration
     */
    public function __construct(DataConfigurationInterface $articleConfiguration)
    {
        $this->articleConfiguration = $articleConfiguration;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->articleConfiguration->getConfiguration();
    }

    /**
     * @param array $data
     * @return array
     */
    public function setData(array $data)
    {
        return $this->articleConfiguration->updateConfiguration($data);
    }
}