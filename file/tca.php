<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}


$TCA['sys_file'] = array (
	'ctrl' => $TCA['sys_file']['ctrl'],
	'interface' => array (
		'showRecordFieldList' => 'sys_language_uid,l10n_parent,l10n_diffsource,hidden,starttime,endtime,fe_group,mount,fieldname,name,sha1,size'
	),
	'feInterface' => $TCA['sys_file']['feInterface'],
	'columns' => array (
		't3ver_label' => array (		
			'label'  => 'LLL:EXT:lang/locallang_general.xml:LGL.versionLabel',
			'config' => array (
				'type' => 'input',
				'size' => '30',
				'max'  => '30',
			)
		),
		'sys_language_uid' => array (		
			'exclude' => 1,
			'label'  => 'LLL:EXT:lang/locallang_general.xml:LGL.language',
			'config' => array (
				'type'                => 'select',
				'foreign_table'       => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xml:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xml:LGL.default_value', 0)
				)
			)
		),
		'l10n_parent' => array (		
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude'     => 1,
			'label'       => 'LLL:EXT:lang/locallang_general.xml:LGL.l18n_parent',
			'config'      => array (
				'type'  => 'select',
				'items' => array (
					array('', 0),
				),
				'foreign_table'       => 'sys_file',
				'foreign_table_where' => 'AND sys_file.pid=###CURRENT_PID### AND sys_file.sys_language_uid IN (-1,0)',
			)
		),
		'l10n_diffsource' => array (		
			'config' => array (
				'type' => 'passthrough'
			)
		),
		'hidden' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array (
				'type'    => 'check',
				'default' => '0'
			)
		),
		'starttime' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.starttime',
			'config'  => array (
				'type'     => 'input',
				'size'     => '8',
				'max'      => '20',
				'eval'     => 'date',
				'default'  => '0',
				'checkbox' => '0'
			)
		),
		'endtime' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.endtime',
			'config'  => array (
				'type'     => 'input',
				'size'     => '8',
				'max'      => '20',
				'eval'     => 'date',
				'checkbox' => '0',
				'default'  => '0',
				'range'    => array (
					'upper' => mktime(3, 14, 7, 1, 19, 2038),
					'lower' => mktime(0, 0, 0, date('m')-1, date('d'), date('Y'))
				)
			)
		),
		'fe_group' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.fe_group',
			'config'  => array (
				'type'  => 'select',
				'items' => array (
					array('', 0),
					array('LLL:EXT:lang/locallang_general.xml:LGL.hide_at_login', -1),
					array('LLL:EXT:lang/locallang_general.xml:LGL.any_login', -2),
					array('LLL:EXT:lang/locallang_general.xml:LGL.usergroups', '--div--')
				),
				'foreign_table' => 'fe_groups'
			)
		),
		'mount' => array (
			'exclude' => 0,
			'label' => 'LLL:EXT:file/locallang_db.xml:sys_file.mount',
			'config' => array (
				'type' => 'select',
				'items' => array (
					array('',0),
				),
				'foreign_table' => 'sys_file_storage',
				'foreign_table_where' => 'ORDER BY sys_file_storage.uid',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1,
			)
		),
		'name' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:file/locallang_db.xml:sys_file.name',		
			'config' => array (
				'type' => 'input',	
				'size' => '30',
			)
		),
		'sha1' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:file/locallang_db.xml:sys_file.sha1',		
			'config' => array (
				'type' => 'input',	
				'size' => '30',
			)
		),
		'size' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:file/locallang_db.xml:sys_file.size',		
			'config' => array (
				'type'     => 'input',
				'size'     => '4',
				'max'      => '4',
				'eval'     => 'int',
				'checkbox' => '0',
				'range'    => array (
					'upper' => '1000',
					'lower' => '10'
				),
				'default' => 0
			)
		),
		'usage_count' => array (		
			'exclude'     => 1,
			'label'       => 'LLL:EXT:lang/locallang_general.xml:usage_count',
			'config' => array (
				'type' => 'group',	
				'internal_type' => 'db',	
				'allowed' => '*',
				'size' => 5,	
				'minitems' => 0,
				'maxitems' => 100,
				"MM_hasUidField" => TRUE,
				"MM" => "sys_file_references",
			)
		),
	),
	'types' => array (
		'0' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, mount, name, sha1, size, usage_count')
	),
	'palettes' => array (
		'1' => array('showitem' => 'starttime, endtime, fe_group')
	)
);



$TCA['sys_file_references'] = array (
	'ctrl' => $TCA['sys_file_references']['ctrl'],
	'interface' => array (
		'showRecordFieldList' => 'hidden,uid_local,uid_foreign,tablenames,fieldname,sorting_foreign,table_local,title,description,downloadname'
	),
	'feInterface' => $TCA['sys_file_references']['feInterface'],
	'columns' => array (
		'hidden' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array (
				'type'    => 'check',
				'default' => '0'
			)
		),
		'uid_local' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:file/locallang_db.xml:sys_file_references.uid_local',		
			'config' => array (
				'type' => 'select',	
				'items' => array (
					array('',0),
				),
				'foreign_table' => 'sys_file',	
				'foreign_table_where' => 'ORDER BY sys_file.uid',	
				'size' => 1,	
				'minitems' => 0,
				'maxitems' => 1,
			)
		),
		'uid_foreign' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:file/locallang_db.xml:sys_file_references.uid_foreign',		
			'config' => array (
				'type' => 'select',	
				'items' => array (
					array('',0),
				),
				'foreign_table' => 'tt_content',	
				'foreign_table_where' => 'ORDER BY tt_content.uid',	
				'size' => 1,	
				'minitems' => 0,
				'maxitems' => 1,
			)
		),
		'tablenames' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:file/locallang_db.xml:sys_file_references.tablenames',		
			'config' => array (
				'type' => 'input',	
				'size' => '30',	
				'eval' => 'trim',
			)
		),
		'fieldname' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:file/locallang_db.xml:sys_file_references.fieldname',		
			'config' => array (
				'type' => 'input',	
				'size' => '30',
				'default' => 'images',
			)
		),
		'sorting_foreign' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:file/locallang_db.xml:sys_file_references.sorting_foreign',		
			'config' => array (
				'type'     => 'input',
				'size'     => '4',
				'max'      => '4',
				'eval'     => 'int',
				'checkbox' => '0',
				'range'    => array (
					'upper' => '1000',
					'lower' => '10'
				),
				'default' => 0
			)
		),
		'table_local' => array (
			'exclude' => 0,
			'label' => 'LLL:EXT:file/locallang_db.xml:sys_file_references.table_local',
			'config' => array (
				'type' => 'input',
				'size' => '20',
				'default' => 'sys_file',
			)
		),
		'title' => array (
			'exclude' => 0,
			'label' => 'LLL:EXT:file/locallang_db.xml:sys_file_references.title',
			'config' => array (
				'type' => 'input',
				'size' => '20',
			)
		),
		'description' => array (
			'exclude' => 0,
			'label' => 'LLL:EXT:file/locallang_db.xml:sys_file_references.description',
			'config' => array (
				'type' => 'text',
				'cols' => '30',
				'rows' => '5',
			)
		),
		'downloadname' => array (
			'exclude' => 0,
			'label' => 'LLL:EXT:file/locallang_db.xml:sys_file_references.downloadname',
			'config' => array (
				'type' => 'input',
				'size' => '20',
			)
		),
	),
	'types' => array (
		'0' => array(
			'showitem' => '
				--palette--;LLL:EXT:file/locallang_db.xml:sys_file_references.overlayPalette;overlayPalette,
				--palette--;;filePalette',
		)
	),
	'palettes' => array (
		'overlayPalette' => array(
			'showitem' => 'title,description;;;;3-3-3,--linebreak--,downloadname',
			'canNotCollapse' => true,
		),
		'filePalette' => array(
			'showitem' => 'uid_local',
		),
	)
);



$TCA['sys_file_collection'] = array (
	'ctrl' => $TCA['sys_file_collection']['ctrl'],
	'interface' => array (
		'showRecordFieldList' => 'sys_language_uid,l10n_parent,l10n_diffsource,hidden,starttime,endtime,fe_group,files,name'
	),
	'feInterface' => $TCA['sys_file_collection']['feInterface'],
	'columns' => array (
		't3ver_label' => array (
			'label'  => 'LLL:EXT:lang/locallang_general.xml:LGL.versionLabel',
			'config' => array (
				'type' => 'input',
				'size' => '30',
				'max'  => '30',
			)
		),
		'sys_language_uid' => array (
			'exclude' => 1,
			'label'  => 'LLL:EXT:lang/locallang_general.xml:LGL.language',
			'config' => array (
				'type'                => 'select',
				'foreign_table'       => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xml:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xml:LGL.default_value', 0)
				)
			)
		),
		'l10n_parent' => array (		
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude'     => 1,
			'label'       => 'LLL:EXT:lang/locallang_general.xml:LGL.l18n_parent',
			'config'      => array (
				'type'  => 'select',
				'items' => array (
					array('', 0),
				),
				'foreign_table'       => 'sys_file_collection',
				'foreign_table_where' => 'AND sys_file_collection.pid=###CURRENT_PID### AND sys_file_collection.sys_language_uid IN (-1,0)',
			)
		),
		'l10n_diffsource' => array (		
			'config' => array (
				'type' => 'passthrough'
			)
		),
		'hidden' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array (
				'type'    => 'check',
				'default' => '0'
			)
		),
		'starttime' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.starttime',
			'config'  => array (
				'type'     => 'input',
				'size'     => '8',
				'max'      => '20',
				'eval'     => 'date',
				'default'  => '0',
				'checkbox' => '0'
			)
		),
		'endtime' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.endtime',
			'config'  => array (
				'type'     => 'input',
				'size'     => '8',
				'max'      => '20',
				'eval'     => 'date',
				'checkbox' => '0',
				'default'  => '0',
				'range'    => array (
					'upper' => mktime(3, 14, 7, 1, 19, 2038),
					'lower' => mktime(0, 0, 0, date('m')-1, date('d'), date('Y'))
				)
			)
		),
		'fe_group' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.fe_group',
			'config'  => array (
				'type'  => 'select',
				'items' => array (
					array('', 0),
					array('LLL:EXT:lang/locallang_general.xml:LGL.hide_at_login', -1),
					array('LLL:EXT:lang/locallang_general.xml:LGL.any_login', -2),
					array('LLL:EXT:lang/locallang_general.xml:LGL.usergroups', '--div--')
				),
				'foreign_table' => 'fe_groups'
			)
		),
		'files' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:file/locallang_db.xml:sys_file_collection.files',		
			'config' => array (
				'type' => 'inline',
				'foreign_table' => 'sys_file_references',
				'foreign_field' => 'uid_foreign',
				'foreign_sortby' => 'sorting_foreign',
				'foreign_table_field' => 'tablenames',
				'foreign_label' => 'uid_local',
				'foreign_selector' => 'uid_local',
			)
		),
		'name' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:file/locallang_db.xml:sys_file_collection.name',		
			'config' => array (
				'type' => 'input',	
				'size' => '30',
			)
		),
	),
	'types' => array (
		'0' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, name, files')
	),
	'palettes' => array (
		'1' => array('showitem' => 'starttime, endtime, fe_group')
	)
);
?>