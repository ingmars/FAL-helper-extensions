<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}
t3lib_extMgm::addUserTSConfig('
	options.saveDocNew.tx_file_mountpoints=1
');
t3lib_extMgm::addUserTSConfig('
	options.saveDocNew.tx_file_theFile=1
');
t3lib_extMgm::addUserTSConfig('
	options.saveDocNew.tx_file_mm=1
');
t3lib_extMgm::addUserTSConfig('
	options.saveDocNew.tx_file_collection=1
');
?>