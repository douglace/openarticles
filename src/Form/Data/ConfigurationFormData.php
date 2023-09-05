<?php

declare(strict_types=1);

namespace Vex6\OpenArticles\Form\Data;

use PrestaShop\PrestaShop\Core\Configuration\DataConfigurationInterface;
use PrestaShop\PrestaShop\Core\ConfigurationInterface;

class ConfigurationFormData implements DataConfigurationInterface
{

    public const OPEN_ARTICLE_TITLE = 'OPEN_ARTICLE_TITLE';
    public const OPEN_ARTICLE_TOTAL_SIZE = 'OPEN_ARTICLE_TOTAL_SIZE';
    public const OPEN_ARTICLE_ACTIVE = 'OPEN_ARTICLE_ACTIVE';

    /**
     * @var ConfigurationInterface
     */
    private $configuration;

    /**
     * @param ConfigurationInterface $configuration
     */
    public function __construct(ConfigurationInterface $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @return array
     */
    public function getConfiguration()
    {
        $return = [];

        if ($title = $this->configuration->get(static::OPEN_ARTICLE_TITLE)) {
            $return[static::OPEN_ARTICLE_TITLE] = $title;
        }
        if ($total = $this->configuration->get(static::OPEN_ARTICLE_TOTAL_SIZE)) {
            $return[static::OPEN_ARTICLE_TOTAL_SIZE] = (int)$total;
        }
        if ($active = $this->configuration->get(static::OPEN_ARTICLE_ACTIVE)) {
            $return[static::OPEN_ARTICLE_ACTIVE] = (bool)$active;
        }

        return $return;
    }

    /**
     * @param array $configuration
     * @return array
     */
    public function updateConfiguration(array $configuration)
    {
        $this->configuration->set(static::OPEN_ARTICLE_TITLE, $configuration[static::OPEN_ARTICLE_TITLE]);
        $this->configuration->set(static::OPEN_ARTICLE_TOTAL_SIZE, (int)$configuration[static::OPEN_ARTICLE_TOTAL_SIZE]);
        $this->configuration->set(static::OPEN_ARTICLE_ACTIVE, (bool)$configuration[static::OPEN_ARTICLE_ACTIVE]);
        return [];
    }

    /**
     * @param array $configuration
     * @return bool
     */
    public function validateConfiguration(array $configuration)
    {
        return true;
    }
}