<?php
$module_name = 'engdt_Engineering';
$viewdefs [$module_name] = 
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
          'file' => 'include/javascript/validationforEngineeringform.js',
        ),
      ),
      
      'useTabs' => false,
      'tabDefs' => 
      array (
        'DEFAULT' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
    ),
    'panels' => 
    array (
      'default' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'task_title',
            'label' => 'LBL_TASK_TITLE',
          ),
          1 => 
          array (
            'name' => 'task_description',
            'studio' => 'visible',
            'label' => 'LBL_TASK_DESCRIPTION',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'product_type',
            'studio' => 'visible',
            'label' => 'LBL_PRODUCT_TYPE',
          ),
          1 => 
          array (
            'name' => 'priority',
            'studio' => 'visible',
            'label' => 'LBL_PRIORITY',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'task_type',
            'studio' => 'visible',
            'label' => 'LBL_TASK_TYPE',
          ),
          1 => 
          array (
            'name' => 'accounts_engdt_engineering_1_name',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'status',
            'studio' => 'visible',
            'label' => 'LBL_STATUS',
			'displayParams' => 
            array (
              'field' => 
              array (
                'onChange' => 'checkticketValidation()',
              ),
            ),
          ),
          1 => 
          array (
            'name' => 'assigned_user_name',
            'label' => 'LBL_ASSIGNED_TO_NAME',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'date_entered',
            'comment' => 'Date record created',
            'label' => 'LBL_DATE_ENTERED',
          ),
          1 => 
          array (
            'name' => 'end_date',
            'label' => 'LBL_END_DATE',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'taskno_c',
            'label' => 'LBL_TASKNO',
          ),
          1 => '',
        ),
        6 => 
        array (
          0 => '',
          1 => '',
        ),
      ),
    ),
  ),
);
;
?>
