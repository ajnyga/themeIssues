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
					{if $issue->getShowTitle()}
					{assign var=issueTitle value=$issue->getLocalizedTitle()}
					{/if}
					{assign var=issueSeries value=$issue->getIssueSeries()}
					{assign var=issueCover value=$issue->getLocalizedCoverImageUrl()}

					<div class="obj_issue_summary">

						{if $issueCover}
							<a class="cover" href="{url op="view" path=$issue->getBestIssueId()}">
								<img src="{$issueCover|escape}" alt="{$issue->getLocalizedCoverImageAltText()|escape|default:''}">
							</a>
						{/if}

						<h2>
							<a class="title" href="{url page="issue" op="view" path=$issue->getBestIssueId()}">
								{if $issueTitle}
									{$issueTitle|escape}
								{else}
									{$issueSeries|escape}
								{/if}
							</a>
							{if $issueTitle && $issueSeries}
								<div class="series">
									{$issueSeries|escape}
								</div>
							{/if}
						</h2>

						<div class="description">
							{$issue->getLocalizedDescription()|strip_unsafe_html}
						</div>
					</div><!-- .obj_issue_summary -->
				</li>
			{/foreach}
		</ul>
	{/if}
</div>

{include file="frontend/components/footer.tpl"}
