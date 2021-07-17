{**
 * plugins/generic/themeIssues/themeIssues.tpl
 *
 * Copyright (c) 2014-2020 Simon Fraser University
 * Copyright (c) 2003-2020 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * Display themeIssues 
 *
 *}
{capture assign="pageTitle"}{translate key="plugins.generic.themeIssues.title"}{/capture}
{include file="frontend/components/header.tpl" pageTitleTranslated=$pageTitle}

<div class="container page-archives">

	<div class="page-header page-archives-header">
		<h1>{$pageTitle|escape}</h1>
	</div>

	{* No issues have been published *}
	{if empty($issues)}
		<div class="page-header page-issue-header">
			{include file="frontend/components/notification.tpl" messageKey="current.noCurrentIssueDesc"}
		</div>

{* List issues *}
	{else}
		{foreach from=$issues item="issue" key="i"}
			{if $i % 4 == 0 && $i > 0}
				</div>
				{assign var="open" value=false}
			{/if}
			{if $i % 4 == 0}
				<div class="row justify-content-around">
				{assign var="open" value=true}
			{/if}
			<div class="col-md-3 col-lg-2">
				{include file="frontend/objects/issue_summary.tpl" heading="h2"}
			</div>
		{/foreach}
		{if $open}
			</div>{* Close an open row *}
		{/if}

	{/if}
</div>

{include file="frontend/components/footer.tpl"}
