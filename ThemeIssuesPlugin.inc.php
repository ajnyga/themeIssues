<?php

/**
 * @file plugins/generic/themeIssues/ThemeIssuesPlugin.inc.php
 *
 * Copyright (c) 2014-2020 Simon Fraser University
 * Copyright (c) 2003-2020 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class ThemeIssuesPlugin
 * @ingroup plugins_generic_themeIssues
 *
 * @brief ThemeIssues plugin class
 */

import('lib.pkp.classes.plugins.GenericPlugin');

class ThemeIssuesPlugin extends GenericPlugin {

	/**
	 * Called as a plugin is registered to the registry
	 * @param $category String Name of category plugin was registered to
	 * @return boolean True if plugin initialized successfully; if false,
	 * 	the plugin will not be registered.
	 */
	function register($category, $path, $mainContextId = NULL) {
		$success = parent::register($category, $path);
		if ($success && $this->getEnabled()) {

			HookRegistry::register('LoadHandler', array($this, 'loadPageHandler'));

			// Handle issue form
			HookRegistry::register('Templates::Editor::Issues::IssueData::AdditionalMetadata', array($this, 'addIssueFormFields'));
			HookRegistry::register('issuedao::getAdditionalFieldNames', array($this, 'addIssueDAOFieldNames'));
			HookRegistry::register('issueform::readuservars', array($this, 'readIssueFormFields'));
			HookRegistry::register('issueform::initdata', array($this, 'initDataIssueFormFields'));	
			HookRegistry::register('issueform::execute', array($this, 'executeIssueFormFields'));
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
		if ($this->getEnabled() && $page === 'themeissues') {
			$this->import('pages/ThemeIssuesHandler');
			define('HANDLER_CLASS', 'ThemeIssuesHandler');
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
		$issueForm->setData('isThemeIssue', $request->getUserVar('isThemeIssue'));
	}	

	/**
	 * Save additional fields in the issue editing form
	 */
	public function executeIssueFormFields($hookName, $args) {
		$issueForm = $args[0];
		$issue = $args[1];
		$issue->setData('isThemeIssue', $issueForm->getData('isThemeIssue'));
		$issueDao = DAORegistry::getDAO('IssueDAO');
		$issueDao->updateObject($issue);
	}

	/**
	 * Initialize data when form is first loaded
	 */
	public function initDataIssueFormFields($hookName, $args) {
		$issueForm = $args[0];
		$issueForm->setData('isThemeIssue', $issueForm->issue->getData('isThemeIssue'));
	}

	/**
	 * Add section settings to IssueDAO
	 */
	public function addIssueDAOFieldNames($hookName, $args) {
		$fields =& $args[1];
		$fields[] = 'isThemeIssue';
	}

}
?>
