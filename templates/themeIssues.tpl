{**
 * plugins/generic/themeIssues/themeIssues.tpl
 *
 * Copyright (c) 2014-2020 Simon Fraser University
 * Copyright (c) 2003-2020 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * Edit themeIssues 
 *
 *}
{capture assign="pageTitle"}{translate key="plugins.generic.themeIssues.title"}{/capture}
{include file="frontend/components/header.tpl" pageTitleTranslated=$pageTitle}

<div class="page page_issue_archive">
	{include file="frontend/components/breadcrumbs.tpl" currentTitle=$pageTitle}
	<h1>
		{$pageTitle|escape}
	</h1>

	{* No issues have been published *}
	{if empty($issues)}
		<p>{translate key="current.noCurrentIssueDesc"}</p>

	{* List issues *}
	{else}
		<ul class="issues_archive">
			{foreach from=$issues item="issue"}
				<li>
					{include file="frontend/objects/issue_summary.tpl"}
				</li>
			{/foreach}
		</ul>
	{/if}
</div>

{include file="frontend/components/footer.tpl"}
