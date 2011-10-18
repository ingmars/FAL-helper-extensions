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
		$storage = $this->request->getArgument('storage');
		$storage = $this->storageRepository->findByUid($storage);
		$this->view->assign('storage', $storage);

		$path = $this->request->getArgument('path');
		$currentFolder = $this->factory->createFolderObject($storage, $path, '');
		$this->view->assign('path', $path);

		$this->view->assign('directories', $currentFolder->getSubfolders());
		$this->view->assign('files', $currentFolder->getFiles());
	}

	public function indexFileAction() {
		$storageUid = $this->request->getArgument('storage');
		/** @var $storage t3lib_file_Storage */
		$storage = $this->storageRepository->findByUid($storageUid);

		$file = $this->request->getArgument('file');
		$file = $storage->getDriver()->getFile($file);

		$fileObject = $this->factory->createFileObject($file);

		/** @var t3lib_file_Repository_FileRepository $fileRepository */
		$fileRepository = t3lib_div::makeInstance('t3lib_file_Repository_FileRepository');
		$fileRepository->addToIndex($fileObject);

		$this->redirect('list', NULL, NULL, array('storage' => $storageUid, 'path' => dirname($fileObject->getIdentifier())));
	}

	public function uploadAction() {
		$storageUid = $this->request->getArgument('storage');
		/** @var $storage t3lib_file_Storage */
		$storage = $this->storageRepository->findByUid($storageUid);

		$path = $this->request->getArgument('identifier');

		/** @var $uploader t3lib_file_Service_UploaderService */
		$uploader = t3lib_div::makeInstance('t3lib_file_Service_UploaderService');

		$files = $_FILES['tx_filefilelist_tools_filefilelistfilelist'];
		if (isset($files['name']['file'])) {
			if ($files['error']['file']) {
				// TODO handle error
			}
			$tempfileName = $files['tmp_name']['file'];
			$origFilename = $files['name']['file'];
			$uploader->addUploadedFile($tempfileName, $storage, $path, $origFilename);
		} elseif (isset($files['name']['files']) && is_array($files['name']['files'])) {
			// TODO multiple files
		}
		//$uploader->addUploadedFile();

		$this->redirect('list', NULL, NULL, array('storage' => $storageUid, 'path' => $path));
	}

	public function copyAction() {
		$storageUid = $this->request->getArgument('storage');
		/** @var $storage t3lib_file_Domain_Model_Storage */
		$storage = $this->storageRepository->findByUid($storageUid);
		$sourceIdentifier = $this->request->getArgument('sourceFile');

		if ($this->request->hasArgument('targetFile')) {
			$targetIdentifier = $this->request->getArgument('targetFile');
			$sourceFile = $storage->getFileByIdentifier($sourceIdentifier);
			/** @var $factory t3lib_file_Factory */
			$factory = t3lib_div::makeInstance('t3lib_file_Factory');
			$sourceFile = $factory->createFileObject($sourceFile);

			$storage->copyFile($sourceFile, $targetIdentifier);
			$this->redirect('list', NULL, NULL, array('storage' => $storageUid, 'path' => dirname($targetIdentifier)));
		} else {
			$this->view->assign('sourceFile', $sourceIdentifier);
			$this->view->assign('storage', $storage);
		}
	}

	public function publishAction() {
		$storageUid = $this->request->getArgument('storage');
		/** @var $storage t3lib_file_Domain_Model_Storage */
		$storage = $this->storageRepository->findByUid($storageUid);

		$fileIdentifier = $this->request->getArgument('file');
		$fileRecord = $storage->getDriver()->getFile($fileIdentifier);
		$fileObject = $this->factory->createFileObject($fileRecord);

		$publisher = $storage->getPublisher();

		$publisher->publishFile($fileObject);
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