<?php

declare(strict_types=1);

namespace Vex6\OpenArticles\CommandHandler;

use PrestaShopBundle\Entity\Repository\LangRepository;
use Vex6\OpenArticles\Command\EditArticleCommand;
use Vex6\OpenArticles\Entity\OpenArticles as OpenArticlesEntity;
use Doctrine\ORM\EntityManagerInterface;
use Vex6\OpenArticles\Command\AddArticleCommand;
use Vex6\OpenArticles\Entity\OpenArticlesLang;
use Vex6\OpenArticles\Exception\CannotAddArticleException;
use Vex6\OpenArticles\Exception\CannotUpdateArticleException;
use Vex6\OpenArticles\Exception\InvalidArticleIdException;
use Vex6\OpenArticles\Repository\ArticleRepository;
use Vex6\OpenArticles\ValueObject\ArticleId;

final class EditArticleCommandHandler implements EditArticleCommandHandlerInterface
{

    /**
     * @var LangRepository
     */
    public $langRepository;

    /**
     * @var ArticleRepository
     */
    public $repository;

    /**
     * @var EntityManagerInterface
     */
    public $entityManager;

    /**
     * @param LangRepository $langRepository
     * @param ArticleRepository $repository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(LangRepository $langRepository, ArticleRepository $repository, EntityManagerInterface $entityManager)
    {
        $this->langRepository = $langRepository;
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    /**
     * @param EditArticleCommand $command
     * @return ArticleId
     * @throws CannotUpdateArticleException
     */
    public function handle(EditArticleCommand $command): ArticleId
    {
        $entity = $this->repository->find($command->getArticleId()->getValue());
        $this->updateArticleFromCommand($entity, $command);
        return $command->getArticleId();
    }

    /**
     * @param OpenArticlesEntity $article
     * @param EditArticleCommand $command
     * @return void
     * @throws CannotUpdateArticleException
     */
    protected function updateArticleFromCommand(OpenArticlesEntity $article, EditArticleCommand $command)
    {
        try {
            $article->setActive((bool)$command->getActive());
            $article->setProductId((int)$command->getProductId());
            $languages = $this->langRepository->findAll();

            foreach($languages as $language){
                $articleLang = null;
                foreach($article->getArticleLangs() as $pl){
                    if($pl->getLang()->getId() == $language->getId()) {
                        $articleLang = $pl;
                        break;
                    }
                }

                if($articleLang === null)
                {
                    $articleLang = new OpenArticlesLang();
                    $articleLang->setLang($language);
                }

                if(isset($command->getTitle()[$language->getId()])){
                    $articleLang->setTitle($command->getTitle()[$language->getId()]);
                }

                if(isset($command->getResume()[$language->getId()])){
                    $articleLang->setResume($command->getResume()[$language->getId()]);
                }

                if(isset($command->getDescription()[$language->getId()])){
                    $articleLang->setDescription($command->getDescription()[$language->getId()]);
                }

                $article->addArticleLang($articleLang);
            }
            
            $this->entityManager->merge($article);
            $this->entityManager->flush();

        } catch (CannotUpdateArticleException $e) {
            throw new CannotUpdateArticleException("An error occurred while creating the article");
        }
    }
}