<?php

/**
 * @file plugins/generic/themeIssues/themeIssuesPlugin.inc.php
 *
 * Copyright (c) 2014-2020 Simon Fraser University
 * Copyright (c) 2003-2020 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class themeIssuesPlugin
 * @ingroup plugins_generic_themeIssues
 *
 * @brief themeIssues plugin class
 */

namespace APP\plugins\generic\themeIssues;

use PKP\plugins\GenericPlugin;
use PKP\plugins\Hook;
use PKP\config\Config;
use APP\core\Application;
use PKP\i18n\Locale;
use PKP\db\DAORegistry;
use APP\facades\Repo;

class themeIssuesPlugin extends GenericPlugin {

	/**
	 * Called as a plugin is registered to the registry
	 * @param $category String Name of category plugin was registered to
	 * @return boolean True if plugin initialized successfully; if false,
	 * 	the plugin will not be registered.
	 */
	function register($category, $path, $mainContextId = NULL) {
		$success = parent::register($category, $path);
		if ($success && $this->getEnabled()) {

			Hook::add('LoadHandler', array($this, 'loadPageHandler'));

			// Handle issue form
			Hook::add('Templates::Editor::Issues::IssueData::AdditionalMetadata', array($this, 'addIssueFormFields'));
			Hook::add('issuedao::getAdditionalFieldNames', array($this, 'addIssueDAOFieldNames'));
		/**	Hook::add('issueform::readuservars', array($this, 'readIssueFormFields')); */
			Hook::add('issueform::initdata', array($this, 'initDataIssueFormFields'));	
		/**	Hook::add('issueform::execute', array($this, 'executeIssueFormFields')); */
		}
		return $success;
	}

	/**
	 * @copydoc Plugin::getDisplayName()
	 */
	function getDisplayName() {
		return __('plugins.generic.themeIssues.displayName');
	}

	/**
	 * @copydoc Plugin::getDescription()
	 */
	function getDescription() {
		return __('plugins.generic.themeIssues.description');
	}

	/**
	 * Load the handler to deal with browse by section page requests
	 */
	public function loadPageHandler($hookName, $args) {
		$page = $args[0];
		if ($this->getEnabled() && $page === 'themeIssues') {
			$this->import('pages/themeIssuesHandler');
			define('HANDLER_CLASS', 'themeIssuesHandler');
			return true;
		}

		return false;
	}

	/**
	 * Add fields to the issue editing form
	 */
	public function addIssueFormFields($hookName, $args) {
		$smarty =& $args[1];
		$output =& $args[2];
		$output .= $smarty->fetch($this->getTemplateResource('themeIssuesEdit.tpl'));
		return false;
	}

	/**
	 * Read user input from additional fields in the issue editing form
	 */
	public function readIssueFormFields($hookName, $args) {
		$issueForm =& $args[0];
		$request = $this->getRequest();
		$issueForm->setData('isthemeIssues', $request->getUserVar('isthemeIssues'));
	}	

	/**
	 * Save additional fields in the issue editing form
	 */
	 
/**	 
*	public function executeIssueFormFields($hookName, $args) {
*		$issueForm = $args[0];
*		$issue = $args[1];
*		$issue->setData('isthemeIssues', $issueForm->getData('isthemeIssues'));
*		$issueDao = DAORegistry::getDAO('IssueDAO');
*		$issueDao->updateObject($issue);
*	}
*/


	/**
	 * Initialize data when form is first loaded
	 */
	public function initDataIssueFormFields($hookName, $args) {
		$issueForm = $args[0];
		$issueForm->setData('isthemeIssues', $issueForm->issue->getData('isthemeIssues'));
	}

	/**
	 * Add section settings to IssueDAO
	 */
	public function addIssueDAOFieldNames($hookName, $args) {
		$fields =& $args[1];
		$fields[] = 'isthemeIssues';
	}

}
?>
