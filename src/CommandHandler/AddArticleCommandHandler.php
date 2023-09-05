<?php

declare(strict_types=1);

namespace Vex6\OpenArticles\CommandHandler;

use Vex6\OpenArticles\Entity\OpenArticles as OpenArticlesEntity;
use Doctrine\ORM\EntityManagerInterface;
use Vex6\OpenArticles\Command\AddArticleCommand;
use PrestaShopBundle\Entity\Repository\LangRepository;
use Vex6\OpenArticles\Entity\OpenArticlesLang;
use Vex6\OpenArticles\Exception\CannotAddArticleException;
use Vex6\OpenArticles\Exception\InvalidArticleIdException;
use Vex6\OpenArticles\Repository\ArticleRepository;
use Vex6\OpenArticles\ValueObject\ArticleId;

final class AddArticleCommandHandler implements AddArticleCommandHandlerInterface
{

    /**
     * @var LangRepository
     */
    public $langRepository;

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
     * @throws InvalidArticleIdException
     */
    public function handle(AddArticleCommand $command): ArticleId
    {
        $entity = new OpenArticlesEntity();
        $this->createArticleFromCommand($entity, $command);
        return new ArticleId( $entity->getId() );
    }

    /**
     * @param OpenArticlesEntity $article
     * @param AddArticleCommand $command
     * @return void
     */
    protected function createArticleFromCommand(OpenArticlesEntity $article, AddArticleCommand $command)
    {
        try {
            $article->setActive((bool)$command->getActive());
            $article->setPosition((int)$this->repository->getNexPosition());
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
            
            $this->entityManager->persist($article);
            $this->entityManager->flush();

        } catch (CannotAddArticleException $e) {
            throw new CannotAddArticleException("An error occurred while creating the article");
        }
    }
}