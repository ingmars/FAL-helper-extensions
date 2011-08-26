<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2011 Andreas Wolf <andreas.wolf@ikt-werk.de>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  A copy is found in the textfile GPL.txt and important notices to the license
 *  from the author is found in LICENSE.txt distributed with these scripts.
 *
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/


/**
 * A controller for listing files and directories
 *
 * @author Andreas Wolf <andreas.wolf@ikt-werk.de>
 * @package TYPO3
 * @subpackage Tx_VfsFilelist
 */
class Tx_VfsFilelist_Controller_FileListController extends Tx_Extbase_MVC_Controller_ActionController {
	/**
	 * @var t3lib_vfs_Factory
	 */
	protected $factory;

	/**
	 * @var t3lib_vfs_Domain_Repository_MountRepository
	 */
	protected $mountRepository;

	/**
	 * Initializes the controller before invoking an action method.
	 *
	 * @return void
	 */
	protected function initializeAction() {
		$this->factory = t3lib_div::makeInstance('t3lib_vfs_Factory');

		$this->mountRepository = t3lib_div::makeInstance('t3lib_vfs_Domain_Repository_MountRepository');
	}

	public function indexAction() {
		/** @var $mountRepository t3lib_vfs_Domain_Repository_MountRepository */
		$mounts = $this->mountRepository->findAvailableMounts();

		$this->view->assign('mounts', $mounts);
	}

	public function listAction() {
		$mount = $this->request->getArgument('mount');
		$mount = $this->mountRepository->findByUid($mount);
		$this->view->assign('mount', $mount);

		$path = $this->request->getArgument('path');
		$currentCollection = $this->factory->createStorageCollectionObject($mount, $path, '');
		$this->view->assign('path', $path);

		$this->view->assign('directories', $currentCollection->getSubcollections());
		$this->view->assign('files', $currentCollection->getFiles());
	}

	/**
	 * Processes a general request. The result can be returned by altering the given response.
	 *
	 * @param Tx_Extbase_MVC_RequestInterface $request The request object
	 * @param Tx_Extbase_MVC_ResponseInterface $response The response, modified by this handler
	 * @throws Tx_Extbase_MVC_Exception_UnsupportedRequestType if the controller doesn't support the current request type
	 * @return void
	 */
	public function processRequest(Tx_Extbase_MVC_RequestInterface $request, Tx_Extbase_MVC_ResponseInterface $response) {
		parent::processRequest($request, $response);

		$this->template = t3lib_div::makeInstance('template');
		$this->pageRenderer = $this->template->getPageRenderer();

		$GLOBALS['SOBE'] = new stdClass();
		$GLOBALS['SOBE']->doc = $this->template;


		$pageHeader = $this->template->startpage('Foobar'
		//$GLOBALS['LANG']->sL('LLL:EXT:workspaces/Resources/Private/Language/locallang.xml:module.title')
		);
		$pageEnd = $this->template->endPage();

		$response->setContent($pageHeader . $response->getContent() . $pageEnd);
	}
}