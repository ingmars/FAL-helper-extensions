<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

t3lib_extMgm::allowTableOnStandardPages('sys_file_storage');

$TCA['sys_file_storage'] = array (
	'ctrl' => array (
		'title'     => 'LLL:EXT:file/locallang_db.xml:sys_file_storage',
		'label'     => 'name',	
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'default_sortby' => 'ORDER BY name',
		'delete' => 'deleted',
		'type' => 'type',
		'enablecolumns' => array (		
			'disabled' => 'hidden',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'icon_sys_file_storage.gif'
	),
);


t3lib_extMgm::allowTableOnStandardPages('sys_file');

$TCA['sys_file'] = array (
	'ctrl' => array (
		'title'     => 'LLL:EXT:file/locallang_db.xml:sys_file',		
		'label'     => 'name',	
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'versioningWS' => TRUE, 
		'origUid' => 't3_origuid',
		'languageField'            => 'sys_language_uid',	
		'transOrigPointerField'    => 'l10n_parent',	
		'transOrigDiffSourceField' => 'l10n_diffsource',	
		'default_sortby' => 'ORDER BY crdate',	
		'delete' => 'deleted',	
		'enablecolumns' => array (		
			'disabled' => 'hidden',	
			'starttime' => 'starttime',	
			'endtime' => 'endtime',	
			'fe_group' => 'fe_group',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'icon_sys_file.gif',
	),
);


t3lib_extMgm::allowTableOnStandardPages('sys_file_references');

$TCA['sys_file_references'] = array (
	'ctrl' => array (
		'title'     => 'LLL:EXT:file/locallang_db.xml:sys_file_references',		
		'label'     => 'uid',
		#'label_userFunc' => 'EXT:file/class.tx_file_userFunc.php:&tx_file_userFunc->getReferenceRecordLabel',
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'sortby' => 'sorting',	
		'delete' => 'deleted',	
		'enablecolumns' => array (		
			'disabled' => 'hidden',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'icon_sys_file_references.gif',
	),
);


t3lib_extMgm::allowTableOnStandardPages('sys_file_collection');

$TCA['sys_file_collection'] = array (
	'ctrl' => array (
		'title'     => 'LLL:EXT:file/locallang_db.xml:sys_file_collection',		
		'label'     => 'name',
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'versioningWS' => TRUE,
		'origUid' => 't3_origuid',
		'languageField'            => 'sys_language_uid',	
		'transOrigPointerField'    => 'l10n_parent',	
		'transOrigDiffSourceField' => 'l10n_diffsource',	
		'default_sortby' => 'ORDER BY crdate',	
		'delete' => 'deleted',	
		'enablecolumns' => array (		
			'disabled' => 'hidden',	
			'starttime' => 'starttime',	
			'endtime' => 'endtime',	
			'fe_group' => 'fe_group',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'icon_sys_file_collection.gif',
	),
);

$tempColumns = array (
	'tx_file_images_overlayed' => array (
		'exclude' => 0,
		'label' => 'LLL:EXT:file/locallang_db.xml:tt_content.tx_file_images_overlayed',
		'config' => array (
				'type' => 'inline',
				'foreign_table' => 'sys_file_references',
				'foreign_field' => 'uid_foreign',
				'foreign_sortby' => 'sorting_foreign',
				'foreign_table_field' => 'tablenames',
				'foreign_match_fields' => array(
					'fieldname' => 'tx_file_images_overlayed',
				),
				'foreign_label' => 'uid_local',
				'foreign_selector' => 'uid_local',
				'appearance' => array(
					'useSortable' => 'true',
					'enabledControls' => array(
						'info' => false,
						'new' => false,
						'dragdrop' => true,
						'sort' => false,
						'hide' => false,
						'delete' => true,
					),
				),
			)
	),
);


t3lib_div::loadTCA('tt_content');
t3lib_extMgm::addTCAcolumns('tt_content',$tempColumns,1);
t3lib_extMgm::addToAllTCAtypes('tt_content','tx_file_images_overlayed');


$tempColumns = array (
	'tx_file_images_list' => array (
		'exclude' => 0,
		'label' => 'LLL:EXT:file/locallang_db.xml:pages.tx_file_images_list',
		'config' => array (
			'type' => 'group',
			'internal_type' => 'db',
			'allowed' => 'sys_file',
			'size' => 5,
			'minitems' => 0,
			'maxitems' => 100,
			"MM" => "sys_file_references",
			"MM_opposite_field" => "usage_count",
			"MM_hasUidField" => TRUE,
			"MM_match_fields" => array(
				'fieldname' => 'tx_file_images_list',
			),
		)
	),
);


t3lib_div::loadTCA('tt_content');
t3lib_extMgm::addTCAcolumns('tt_content',$tempColumns,1);
t3lib_extMgm::addToAllTCAtypes('tt_content','tx_file_images_list;;;;1-1-1');

?>