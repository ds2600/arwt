<?php

return [
    'AMAT_PUBACC_AM' => [
        'fields' => [
            'record_type',
            'unique_system_identifier',
            'uls_file_num',
            'ebf_number',
            'callsign',
            'operator_class',
            'group_code',
            'region_code',
            'trustee_callsign',
            'trustee_indicator',
            'physician_certification',
            've_signature',
            'systematic_callsign_change',
            'vanity_callsign_change',
            'vanity_relationship',
            'previous_callsign',
            'previous_operator_class',
            'trustee_name'
        ],
        'updateFields' => [
            'record_type',
            'uls_file_num',
            'ebf_number',
            'callsign',
            'operator_class',
            'group_code',
            'region_code',
            'trustee_callsign',
            'trustee_indicator',
            'physician_certification',
            've_signature',
            'systematic_callsign_change',
            'vanity_callsign_change',
            'vanity_relationship',
            'previous_callsign',
            'previous_operator_class',
            'trustee_name'
        ]
    ],
    'AMAT_PUBACC_CO' => [
        'fields' => [
            'record_type',
            'unique_system_identifier',
            'uls_file_num',
            'callsign',
            'comment_date',
            'description',
            'status_code',
            'status_date'
        ],
        'updateFields' => [
            'record_type',
            'uls_file_num',
            'callsign',
            'comment_date',
            'description',
            'status_code',
            'status_date'
        ]
    ],
    'AMAT_PUBACC_EN' => [
        'fields' => [
            'record_type',
            'unique_system_identifier',
            'uls_file_number',
            'ebf_number',
            'call_sign',
            'entity_type',
            'licensee_id',
            'entity_name',
            'first_name',
            'mi',
            'last_name',
            'suffix',
            'phone',
            'fax',
            'email',
            'street_address',
            'city',
            'state',
            'zip_code',
            'po_box',
            'attention_line',
            'sgin',
            'frn',
            'applicant_type_code',
            'applicant_type_other',
            'status_code',
            'status_date'
        ],
        'updateFields' => [
            'record_type',
            'uls_file_number',
            'ebf_number',
            'call_sign',
            'entity_type',
            'licensee_id',
            'entity_name',
            'first_name',
            'mi',
            'last_name',
            'suffix',
            'phone',
            'fax',
            'email',
            'street_address',
            'city',
            'state',
            'zip_code',
            'po_box',
            'attention_line',
            'sgin',
            'frn',
            'applicant_type_code',
            'applicant_type_other',
            'status_code',
            'status_date'
        ]
    ],
    'AMAT_PUBACC_HD' => [
        'fields' => [
            'record_type',
            'unique_system_identifier',
            'uls_file_number',
            'ebf_number',
            'call_sign',
            'license_status',
            'radio_service_code',
            'grant_date',
            'expired_date',
            'cancellation_date',
            'eligibility_rule_num',
            'applicant_type_code_reserved',
            'alien',
            'alien_government',
            'alien_corporation',
            'alien_officer',
            'alien_control',
            'revoked',
            'convicted',
            'adjudged',
            'involved_reserved',
            'common_carrier',
            'non_common_carrier',
            'private_comm',
            'fixed',
            'mobile',
            'radiolocation',
            'satellite',
            'developmental_or_sta',
            'interconnected_service',
            'certifier_first_name',
            'certifier_mi',
            'certifier_last_name',
            'certifier_suffix',
            'certifier_title',
            'gender',
            'african_american',
            'native_american',
            'hawaiian',
            'asian',
            'white',
            'ethnicity',
            'effective_date',
            'last_action_date',
            'auction_id',
            'reg_stat_broad_serv',
            'band_manager',
            'type_serv_broad_serv',
            'alien_ruling',
            'licensee_name_change'
        ],
        'updateFields' => [
            'record_type',
            'uls_file_number',
            'ebf_number',
            'call_sign',
            'license_status',
            'radio_service_code',
            'grant_date',
            'expired_date',
            'cancellation_date',
            'eligibility_rule_num',
            'applicant_type_code_reserved',
            'alien',
            'alien_government',
            'alien_corporation',
            'alien_officer',
            'alien_control',
            'revoked',
            'convicted',
            'adjudged',
            'involved_reserved',
            'common_carrier',
            'non_common_carrier',
            'private_comm',
            'fixed',
            'mobile',
            'radiolocation',
            'satellite',
            'developmental_or_sta',
            'interconnected_service',
            'certifier_first_name',
            'certifier_mi',
            'certifier_last_name',
            'certifier_suffix',
            'certifier_title',
            'gender',
            'african_american',
            'native_american',
            'hawaiian',
            'asian',
            'white',
            'ethnicity',
            'effective_date',
            'last_action_date',
            'auction_id',
            'reg_stat_broad_serv',
            'band_manager',
            'type_serv_broad_serv',
            'alien_ruling',
            'licensee_name_change'
        ]
    ],
    'AMAT_PUBACC_HS' => [
        'fields' => [
            'record_type',
            'unique_system_identifier',
            'uls_file_number',
            'callsign',
            'log_date',
            'code'
        ],
        'updateFields' => [
            'record_type',
            'uls_file_number',
            'callsign',
            'log_date',
            'code'
        ]
    ],
    'AMAT_PUBACC_LA' => [
        'fields' => [
            'record_type',
            'unique_system_identifier',
            'callsign',
            'attachment_code',
            'attachment_desc',
            'attachment_date',
            'attachment_filename',
            'action_performed'
        ],
        'updateFields' => [
            'record_type',
            'unique_system_identifier',
            'callsign',
            'attachment_code',
            'attachment_desc',
            'attachment_date',
            'attachment_filename',
            'action_performed'
        ]
    ],
    'AMAT_PUBACC_SC' => [
        'fields' => [
            'record_type',
            'unique_system_identifier',
            'uls_file_number',
            'ebf_number',
            'callsign',
            'special_condition_type',
            'special_condition_code',
            'status_code',
            'status_date'
        ],
        'updateFields' => [
            'record_type',
            'uls_file_number',
            'ebf_number',
            'callsign',
            'special_condition_type',
            'special_condition_code',
            'status_code',
            'status_date'
        ]
    ],
    'AMAT_PUBACC_SF' => [
        'fields' => [
            'record_type',
            'unique_system_identifier',
            'uls_file_number',
            'ebf_number',
            'callsign',
            'lic_freeform_cond_type',
            'unique_lic_freeform_id',
            'sequence_number',
            'lic_freeform_condition',
            'status_code',
            'status_date'
        ],
        'updateFields' => [
            'record_type',
            'uls_file_number',
            'ebf_number',
            'callsign',
            'lic_freeform_cond_type',
            'unique_lic_freeform_id',
            'sequence_number',
            'lic_freeform_condition',
            'status_code',
            'status_date'
        ]
    ],
];