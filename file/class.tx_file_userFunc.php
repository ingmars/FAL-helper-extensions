<?php


class tx_file_userFunc {

    function getReferenceRecordLabel(&$params, $pObj) {

        $params['title'] = '';
        $referenceRow = t3lib_BEFunc::getRecordRaw('sys_file_references', 'uid = '.intval($params['row']['uid']));


        # $fieldName = ($referenceRow['fieldname']?'/'.$referenceRow['fieldname']:'');

            // Displaying of filename disabled, because otherwise the string gets truncated by the list module:
        $fieldName = '';

        $params['title'] = $referenceRow['tablenames'].$fieldName.':'.$referenceRow['uid_foreign'].' <-> sys_file:'.$referenceRow['uid_local'];
    }
}
 

?>