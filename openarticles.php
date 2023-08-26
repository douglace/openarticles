<?php

declare(strict_types=1);

use Vex6\OpenArticles\Install\Installer;

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once __DIR__.'/vendor/autoload.php';
class Openarticles extends Module
{
    public function __construct()
    {
        $this->name = 'openarticles';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'OpenInTech';
        $this->need_instance = 0;

        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Affichage des articles');
        $this->description = $this->l('Ce module permet de créer des articles et de les afficher sur la page d\'accueil et sur une page dédié');
        $this->ps_versions_compliancy = array('min' => '1.7.0', 'max' => _PS_VERSION_);
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

    public function getContent() {
        return "Hello world";
    }

    public function hookDisplayHome($params) {
        return "Hello world";
    }

    public function hookModuleRoutes($params) {

    }

    public function hookDisplayBackOfficeHeader($params) {

    }

}