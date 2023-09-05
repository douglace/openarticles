<?php

declare(strict_types=1);

namespace Vex6\OpenArticles\Form\Type;

use PrestaShop\PrestaShop\Core\ConstraintValidator\Constraints\CleanHtml;
use PrestaShopBundle\Form\Admin\Type\CustomContentType;
use PrestaShopBundle\Form\Admin\Type\FormattedTextareaType;
use PrestaShopBundle\Form\Admin\Type\SwitchType;
use PrestaShopBundle\Form\Admin\Type\TranslatableType;
use PrestaShopBundle\Form\Admin\Type\TranslateType;
use PrestaShopBundle\Form\Admin\Type\TranslatorAwareType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Vex6\OpenArticles\Form\Data\ConfigurationFormData;
use Vex6\OpenArticles\Repository\ArticleRepository;
use Vex6\OpenArticles\Uploader\ArticleImageUploader;

class ConfigurationType extends TranslatorAwareType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options); // TODO: Change the autogenerated stub
        $builder
            ->setMethod("PUT")
            ->add(ConfigurationFormData::OPEN_ARTICLE_TITLE, TranslatableType::class, [
                'type' => TextType::class,
                'required' => true,
                'label' => $this->trans('Titre du block', 'Modules.Openarticles.Admin')
            ])->add(ConfigurationFormData::OPEN_ARTICLE_TOTAL_SIZE, TextType::class, [
                'required' => true,
                'label' => $this->trans('Nombre d\'articles sur un bloc', 'Modules.Openarticles.Admin')
            ])->add(ConfigurationFormData::OPEN_ARTICLE_ACTIVE, SwitchType::class, [
                'label'   => $this->trans('Active',  'Modules.Openarticles.Admin'),
                'required' => false,
            ])
        ;

    }
}