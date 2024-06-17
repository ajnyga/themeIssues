<?php

/**
 * @file plugins/generic/themeIssues/pages/themeIssuesHandler.inc.php
 *
 * Copyright (c) 2014-2020 Simon Fraser University
 * Copyright (c) 2003-2020 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class themeIssuesHandler
 * @ingroup plugins_generic_themeIssues
 *
 * @brief Handle reader-facing router requests
 */

use APP\core\Application;
use APP\facades\Repo;
use APP\file\IssueFileManager;
use APP\handler\Handler;
use APP\issue\Collector;
use APP\issue\IssueAction;
use APP\issue\IssueGalleyDAO;
use APP\observers\events\UsageEvent;
use APP\payment\ojs\OJSCompletedPaymentDAO;
use APP\payment\ojs\OJSPaymentManager;
use APP\security\authorization\OjsIssueRequiredPolicy;
use APP\security\authorization\OjsJournalMustPublishPolicy;
use APP\template\TemplateManager;
use PKP\config\Config;
use PKP\db\DAORegistry;
use PKP\facades\Locale;
use PKP\plugins\Hook;
use PKP\plugins\PluginRegistry;
use PKP\security\authorization\ContextRequiredPolicy;
use PKP\security\Validation;
use PKP\submission\GenreDAO;
use PKP\submission\PKPSubmission;


class themeIssuesHandler extends Handler {

	/**
	 * @copydoc PKPHandler::authorize()
	 */
	function authorize($request, &$args, $roleAssignments) {
		import('lib.pkp.classes.security.authorization.ContextRequiredPolicy');
		$this->addPolicy(new ContextRequiredPolicy($request));

		import('classes.security.authorization.OjsJournalMustPublishPolicy');
		$this->addPolicy(new OjsJournalMustPublishPolicy($request));

		return parent::authorize($request, $args, $roleAssignments);
	}

	/**
	 * View themeIssues
	 */
	public function index($args, $request) {
		$this->setupTemplate($request);
		$templateMgr = TemplateManager::getManager($request);
		$context = $request->getContext();
		$plugin = PluginRegistry::getPlugin('generic', 'themeIssuesplugin');

		
		$collector = Repo::issue()->getCollector()
            ->filterByContextIds([$context->getId()])
            ->orderBy(Collector::ORDERBY_SEQUENCE)
            ->filterByPublished(true);
		
		$issues = $collector->getMany()->toArray();
		

		$themeIssues = [];
		foreach ($issues as $issue) {
			if ($issue->getData('isthemeIssues')){
				$themeIssues[] = $issue;
			}
		}

		  $templateMgr->assign([
			  'issues' => $themeIssues,
			  ]);

		$templateMgr->display($plugin->getTemplateResource('themeIssues.tpl'));
	}
}
