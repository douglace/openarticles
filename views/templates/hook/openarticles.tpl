<section class="clearfix oit-articles">
    <h2 class="h2 section-title text-uppercase text-xs-center">
        {$title}
    </h2>
    <div class="row">
        {foreach from=$articles item="article"}
            {include file="module:openarticles/views/templates/hook/article.tpl" product=$article}
        {/foreach}
    </div>
    <a class="all-article-link float-xs-left float-md-right h4" href="#">
        {l s='Tous les articles' d='Modules.Openarticles.Shop'}<i class="material-icons"></i>
    </a>
</section>