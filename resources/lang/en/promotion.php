<?php

return [
    'index' => [
        'title' => 'Promotion Management',
        'table' => 'Promotion List'
    ],
    'create' => [
        'title' => 'Add New Promotion'
    ],
    'edit' => [
        'title' => 'Update Promotion'
    ],
    'delete' => [
        'title' => 'Delete Promotion'
    ],
    'translate' => [
        'title' => 'Create :language Translation for Promotion'
    ],
    'request' => [
        'name_required'                 => 'You have not entered the promotion name',
        'code_required'                 => 'You have not entered the promotion code',
        'code_unique'                   => 'Promotion code already exists',
        'startDate_required'            => 'You have not entered the promotion start date',
        'startDate_custom_date_format'  => 'Promotion start date is not in the correct format',
        'endDate_required'              => 'You have not entered the promotion end date',
        'endDate_custom_date_format'    => 'Promotion end date is not in the correct format',
        'endDate_custom_after'          => 'Promotion end date must be later than the start date',
        'amountValue_fail'              => 'Promotion value configuration is invalid',
        'amount_fail'                   => 'The promotion range has not been correctly initialized',
        'conflict_fail'                 => 'There is a conflict between the promotion value ranges',
        'quantity_fail'                 => 'You have not entered the minimum purchase quantity',
        'discountValue_fail'            => 'You have not entered the discount value',
        'object_fail'                   => 'You have not selected the applicable discount object',
        'method_not_in'                 => 'You have not selected the promotion method',
    ],
];
