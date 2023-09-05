{extends file='page.tpl'}

{block name="page_title"}
    {$article.title}
{/block}

{block name="page_content_container"}
    <div class="oit-article-page row">
        <div class="col-xs-4">
            <img class="oit-article-image" src="{$article.image}" alt="{$article.title}">
            <a class="oit-article-product-name" href="{$article.product_link}">{$article.product_name}</a>
            <p class="oit-article-resume">{$article.resume}</p>
        </div>
        <div class="col-xs-6">
            <div class="oit-article-description">
                {$article.description nofilter}
            </div>
        </div>
    </div>
{/block}