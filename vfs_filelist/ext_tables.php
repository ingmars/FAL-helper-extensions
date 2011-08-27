<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

if (TYPO3_MODE == 'BE' && !(TYPO3_REQUESTTYPE & TYPO3_REQUESTTYPE_INSTALL)) {
	/**
	* Registers a Backend Module
	*/
	Tx_Extbase_Utility_Extension::registerModule(
		$_EXTKEY,
		'tools',    // Make module a submodule of 'tools'
		'filelist',    // Submodule key
		'', // Position
		array(
				// An array holding the controller-action-combinations that are accessible
			'Filelist'        => 'index,list,indexFile'
		),
		array(
			'access' => 'admin',
			'icon'   => 'EXT:'.$_EXTKEY.'/Resources/Public/Images/moduleicon.gif',
			'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_mod.xml'
		)
	);
}
?>