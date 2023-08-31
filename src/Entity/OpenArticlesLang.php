<?php

namespace Vex6\OpenArticles\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Vex6\OpenArticles\Repository\ArticleRepository")
 */
class OpenArticlesLang
{

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Vex6\OpenArticles\Entity\OpenArticles", inversedBy="articleLangs")
     * @ORM\JoinColumn(name="open_article_id", referencedColumnName="id", nullable=false)
     */
    private $article;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="PrestaShopBundle\Entity\Lang")
     * @ORM\JoinColumn(name="lang_id", referencedColumnName="id_lang", nullable=false, onDelete="CASCADE")
     */
    private $lang;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="resume", type="string")
     */
    private $resume;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string")
     */
    private $description;

    /**
     * @return mixed
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * @param mixed $article
     * @return OpenArticlesLang
     */
    public function setArticle($article)
    {
        $this->article = $article;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * @param mixed $lang
     * @return OpenArticlesLang
     */
    public function setLang($lang)
    {
        $this->lang = $lang;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): OpenArticlesLang
    {
        $this->title = $title;
        return $this;
    }

    public function getResume(): string
    {
        return $this->resume;
    }

    public function setResume(string $resume): OpenArticlesLang
    {
        $this->resume = $resume;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): OpenArticlesLang
    {
        $this->description = $description;
        return $this;
    }

}