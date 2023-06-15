<?php
$viewdefs ['Cases'] = 
array (
  'EditView' => 
  array (
    'templateMeta' => 
    array (
      'maxColumns' => '2',
      'widths' => 
      array (
        0 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
        1 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
      ),
      'includes' => 
      array (
        0 => 
        array (
          'file' => 'include/javascript/bindWithDelay.js',
        ),
        1 => 
        array (
          'file' => 'modules/AOK_KnowledgeBase/AOK_KnowledgeBase_SuggestionBox.js',
        ),
        2 => 
        array (
          'file' => 'include/javascript/qtip/jquery.qtip.min.js',
        ),
		3 => 
        array (
          'file' => 'include/javascript/validationform.js',
        ),
      ),
      'useTabs' => false,
      'tabDefs' => 
      array (
        'LBL_CASE_INFORMATION' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
      'form' => 
      array (
        'enctype' => 'multipart/form-data',
      ),
    ),
    'panels' => 
    array (
      'lbl_case_information' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'case_number',
            'type' => 'readonly',
          ),
          1 => 'priority',
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'state',
            'comment' => 'The state of the case (i.e. open/closed)',
            'label' => 'LBL_STATE',
          ),
          1 => 
          array (
            'name' => 'case_type_c',
            'studio' => 'visible',
            'label' => 'LBL_CASE_TYPE',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'department_c',
            'studio' => 'visible',
            'label' => 'LBL_DEPARTMENT',
          ),
          1 => 
          array (
            'name' => 'category_c',
            'studio' => 'visible',
            'label' => 'LBL_CATEGORY',
          ),
        ),
        3 => 
        array (
          0 => 'type',
          1 => 
          array (
            'name' => 'trunk_c',
            'studio' => 'visible',
            'label' => 'LBL_TRUNK',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'name',
            'displayParams' => 
            array (
            ),
          ),
          1 => 'account_name',
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'resolution',
            'nl2br' => true,
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'history_c',
            'studio' => 'visible',
            'label' => 'LBL_HISTORY',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'description',
          ),
        ),
        8 => 
        array (
          0 => 'assigned_user_name',
        ),
        9 => 
        array (
          0 => 
          array (
            'name' => 'issue_reported_via_c',
            'studio' => 'visible',
            'label' => 'LBL_ISSUE_REPORTED_VIA',
          ),
          1 => 
          array (
            'name' => 'date_modified',
            'comment' => 'Date record last modified',
            'label' => 'LBL_DATE_MODIFIED',
          ),
        ),
        10 => 
        array (
          0 => 
          array (
            'name' => 'update_text',
            'studio' => 'visible',
            'label' => 'LBL_UPDATE_TEXT',
          ),
        ),
        11 => 
        array (
          0 => 
          array (
            'name' => 'created_by_name',
            'label' => 'LBL_CREATED',
          ),
          1 => 
          array (
            'name' => 'end_date_c',
            'label' => 'LBL_END_DATE',
          ),
        ),
      ),
    ),
  ),
);
;
?>
