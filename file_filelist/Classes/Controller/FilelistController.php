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
 * @subpackage Tx_FileFilelist
 */
class Tx_FileFilelist_Controller_FileListController extends Tx_Extbase_MVC_Controller_ActionController {
	/**
	 * @var t3lib_file_Factory
	 */
	protected $factory;

	/**
	 * @var t3lib_file_Repository_StorageRepository
	 */
	protected $storageRepository;

	/**
	 * Initializes the controller before invoking an action method.
	 *
	 * @return void
	 */
	protected function initializeAction() {
		$this->factory = t3lib_div::makeInstance('t3lib_file_Factory');

		$this->storageRepository = t3lib_div::makeInstance('t3lib_file_Repository_StorageRepository');
	}

	public function indexAction() {
		/** @var $storageRepository t3lib_file_Repository_StorageRepository */
		$storages = $this->storageRepository->findAll();

		$this->view->assign('storages', $storages);
	}

	public function listAction() {
		$storageUid = $this->request->getArgument('storage');
		$path = $this->request->getArgument('path');

		$storage = $this->storageRepository->findByUid($storageUid);
		$currentFolder = $storage->getFolder($path);

		$this->view->assign('path', $path);
		$this->view->assign('storage', $storage);
		$this->view->assign('directories', $currentFolder->getSubfolders());
		$this->view->assign('files', $currentFolder->getFiles());
	}

	public function indexFileAction() {
		$storageUid = $this->request->getArgument('storage');
		$fileIdentifier = $this->request->getArgument('file');

		/** @var $storage t3lib_file_Storage */
		$storage = $this->storageRepository->findByUid($storageUid);
		$fileObject = $storage->getFile($fileIdentifier);

		/** @var t3lib_file_Repository_FileRepository $fileRepository */
		$fileRepository = t3lib_div::makeInstance('t3lib_file_Repository_FileRepository');
		$fileRepository->addToIndex($fileObject);

		$this->redirect('list', NULL, NULL, array('storage' => $storageUid, 'path' => dirname($fileObject->getIdentifier())));
	}

	public function uploadAction() {
		$storageUid = $this->request->getArgument('storage');
		$folderIdentifier = $this->request->getArgument('identifier');
		$folderCombinedIdentifier = $storageUid . ':' . $folderIdentifier;

		/** @var $storage t3lib_file_Storage */
		$storage = $this->storageRepository->findByUid($storageUid);

			// Initializing:
		/** @var $fileProcessor t3lib_extFileFunctions */
		$fileProcessor = t3lib_div::makeInstance('t3lib_extFileFunctions');
		$fileProcessor->init($GLOBALS['FILEMOUNTS'], $GLOBALS['TYPO3_CONF_VARS']['BE']['fileExtensions']);
		$fileProcessor->init_actionPerms($GLOBALS['BE_USER']->getFileoperationPermissions());
		$fileProcessor->dontCheckForUnique = t3lib_div::_GP('overwriteExistingFiles') ? 1 : 0; // @todo change this to fit Vidi UI


		// Rearranged $_FILES to suit extFileFunctions needs
		$files = $_FILES['tx_filefilelist_tools_filefilelistfilelist'];
		$_FILES['upload_tx_filefilelist_tools_filefilelistfilelist'] = array(
			'name' => $files['name']['file'],
			'type' => $files['type']['file'],
			'tmp_name' => $files['tmp_name']['file'],
			'error' => $files['error']['file'],
			'size' => $files['size']['file'],
		);
		unset($_FILES['tx_filefilelist_tools_filefilelistfilelist']); // remove the old reference
		if (isset($files['name']['file'])) {
			if ($files['error']['file']) {
				// TODO handle error
			}

			$fileValues = array(
				'upload' => array(
					array(
					'data' => 'tx_filefilelist_tools_filefilelistfilelist', // is used as an id within $_FILES
					'target' => $folderCombinedIdentifier,
					)
				)
			);

			$fileProcessor->start($fileValues);
			$fileProcessor->processData();
		} elseif (isset($files['name']['files']) && is_array($files['name']['files'])) {
			// TODO multiple files
		}


		$this->redirect('list', NULL, NULL, array('storage' => $storageUid, 'path' => $folderIdentifier));
	}

	public function copyAction() {
		$storageUid = $this->request->getArgument('storage');
		$sourceFileIdentifier = $this->request->getArgument('sourceFile');

		/** @var $storage t3lib_file_Storage */
		$storage = $this->storageRepository->findByUid($storageUid);

		if ($this->request->hasArgument('targetFolder')) {
			$targetIdentifier = $this->request->getArgument('targetFolder');
			$sourceFile = $storage->getFile($sourceFileIdentifier);
			$targetFolder = $storage->getFolder($targetIdentifier);
			$sourceFile->copyTo($targetFolder);
			$this->redirect('list', NULL, NULL, array('storage' => $storageUid, 'path' => dirname($targetIdentifier)));
		} else {
			$this->view->assign('sourceFile', $sourceFileIdentifier);
			$this->view->assign('storage', $storage);
		}
	}

	public function publishAction() {
		$storageUid = $this->request->getArgument('storage');
		$fileIdentifier = $this->request->getArgument('file');

		/** @var $storage t3lib_file_Storage */
		$storage = $this->storageRepository->findByUid($storageUid);
		$fileObject = $storage->getFile($fileIdentifier);

		$publisher = $storage->getPublisher();
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