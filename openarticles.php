<?php

declare(strict_types=1);

use Vex6\OpenArticles\Install\Installer;

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once __DIR__.'/vendor/autoload.php';

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
use PrestaShop\PrestaShop\Adapter\SymfonyContainer;
use Vex6\OpenArticles\Repository\ArticleRepository;
use Vex6\OpenArticles\Form\Data\ConfigurationFormData;

class Openarticles extends Module implements WidgetInterface
{

    /** @var string */
    private $templateFile;

    public function __construct()
    {
        $this->name = 'openarticles';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'OpenInTech';
        $this->need_instance = 0;


        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Affichage des articles');
        $this->description = $this->l('Ce module permet de créer des articles et de les afficher sur la page d\'accueil et sur une page dédié');
        $this->ps_versions_compliancy = [
            'min' => '1.7.0.0',
            'max' => _PS_VERSION_,
        ];

        $this->templateFile = 'module:openarticles/views/templates/hook/openarticles.tpl';
    }

    /**
     * @return bool
     */
    public function install(): bool
    {
        if (!parent::install()) {
            return false;
        }

        $installer = new Installer();
        return $installer->install($this);
    }

    /**
     * @return bool
     */
    public function uninstall(): bool
    {
        $installer = new Installer();
        return $installer->uninstall() && parent::uninstall();
    }

    /** Utilisation de la traduction moderne de prestashop */
    public function isUsingNewTranslationSystem(): bool
    {
        return true;
    }

    public function getContent() {
        Tools::redirectAdmin(
            SymfonyContainer::getInstance()
                ->get('router')
                ->generate('oit_article_index')
        );
    }

    public function hookModuleRoutes($params){
        return [
            'module-openarticles-articles' => [
                'controller' => 'articles',
                'rule' => 'oit/articles/',
                'keywords' => [],
                'params' => [
                    'fc' => 'module',
                    'module' => 'openarticles'
                ]
            ],
            'module-openarticles-article' => [
                'controller' => 'article',
                'rule' => 'oit/article/{articleId}',
                'keywords' => [
                    'articleId' => array('regexp' => '[0-9]+', 'param' => 'articleId'),
                ],
                'params' => [
                    'fc' => 'module',
                    'module' => 'openarticles'
                ]
            ],
        ];

    }

    /**
     * @param $hookName
     * @param array $configuration
     * @return mixed
     */
    public function renderWidget($hookName, array $configuration)
    {
        if(!Configuration::get(ConfigurationFormData::OPEN_ARTICLE_ACTIVE)) {
            return "";
        }

        if (!$this->isCached($this->templateFile, $this->getCacheId('openarticles'))) {
            $this->smarty->assign($this->getWidgetVariables($hookName, $configuration));
        }

        return $this->fetch($this->templateFile, $this->getCacheId('openarticles'));
    }

    /**
     * @param $hookName
     * @param array $configuration
     * @return mixed
     */
    public function getWidgetVariables($hookName, array $configuration)
    {
        /**
         * @var ArticleRepository $repository
         */
        $repository = $this->get('openarticles.repository.article_repository');
        $limit = (int) Configuration::get(ConfigurationFormData::OPEN_ARTICLE_TOTAL_SIZE);
        $id_lang = $this->context->language->id;

        $articles = $repository->getFrontData($id_lang, $limit);
        $title = Configuration::get(ConfigurationFormData::OPEN_ARTICLE_TITLE, $id_lang);

        return [
            'articles' => $articles,
            'title' => $title,
            'all_article_link' => $this->context->link->getModuleLink($this->name, 'articles'),
        ];
    }

    public function hookDisplayHeader()
    {
        if (
            Tools::getValue("module") == "openarticles" ||
            (isset($this->context->controller->php_self) && $this->context->controller->php_self == 'index')
        ) {
            $this->context->controller->registerStylesheet('modules-openarticle', 'modules/' . $this->name . '/views/assets/css/articles.css');
        }
    }
}