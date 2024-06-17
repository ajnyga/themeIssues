{**
 * plugins/generic/themeIssues/templates/themeIssues.tpl
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
		<pre> hallo {print_r($issues)}</pre>

{* List issues *}
	{else}
			<pre> hallo {print_r($issues)}</pre>
			<div class="row mt-5 gx-md-5">
					{foreach from=$issues item="issue" key="i"}
				{include file="frontend/objects/issue_summary.tpl" heading="h2"}
						{/foreach}
			</div>
		{if $open}
			</div>{* Close an open row *}
		{/if}

	{/if}
</div>

{include file="frontend/components/footer.tpl"}
