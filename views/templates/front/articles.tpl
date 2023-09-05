{extends file='page.tpl'}

{block name="page_title"}
    {l s='Mes articles' mod='Modules.Openarticles.Front'}
{/block}

{block name="page_content_container"}
    <div class="row oit-articles-list">
        {foreach from=$articles item="article"}
            {include file="module:openarticles/views/templates/hook/article.tpl" article=$article}
        {/foreach}
    </div>
{/block}