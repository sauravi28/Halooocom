<?php
$viewdefs ['Cases'] = 
array (
  'DetailView' => 
  array (
    'templateMeta' => 
    array (
      'form' => 
      array (
        'buttons' => 
        array (
          0 => 'EDIT',
          1 => 'DUPLICATE',
          2 => 'DELETE',
          3 => 'FIND_DUPLICATES',
        ),
        'links' => 
        array (
          0 => NULL,
        ),
      ),
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
            'label' => 'LBL_CASE_NUMBER',
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
            'label' => 'LBL_SUBJECT',
          ),
          1 => 'account_name',
        ),
        5 => 
        array (
          0 => 'resolution',
          1 => 
          array (
            'name' => 'client_email_id_c',
            'label' => 'LBL_CLIENT_EMAIL_ID',
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
          0 => 'description',
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'assigned_user_name',
            'label' => 'LBL_ASSIGNED_TO',
          ),
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
            'label' => 'LBL_DATE_MODIFIED',
            'customCode' => '{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}',
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
