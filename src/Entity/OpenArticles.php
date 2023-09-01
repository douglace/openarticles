<?php

declare(strict_types=1);

namespace Vex6\OpenArticles\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Vex6\OpenArticles\Repository\ArticleRepository")
 */
class OpenArticles
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @ORM\Column(name="position", type="integer")
     */
    private $position;


    /**
     * @ORM\Column(name="product_id", type="integer")
     */
    private $productId;


    /**
     * @ORM\Column(name="active", type="integer")
     */
    private $active;

    /**
     * @ORM\OneToMany(targetEntity="OpenArticlesLang", cascade={"persist", "remove"}, mappedBy="article")
     */
    private $articleLangs;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->articleLangs = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): Article
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param mixed $position
     * @return Article
     */
    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * @param mixed $productId
     * @return Article
     */
    public function setProductId($productId)
    {
        $this->productId = $productId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param mixed $active
     * @return Article
     */
    public function setActive($active)
    {
        $this->active = $active;
        return $this;
    }

    public function getArticleLangs()
    {
        return $this->articleLangs;
    }

    public function addArticleLang(OpenArticlesLang $articleLang): OpenArticles
    {
        $this->articleLangs[] = $articleLang;
        $articleLang->setArticle($this);
        return $this;
    }

}