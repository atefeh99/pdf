
<?php

return [
    'custom' => [
        '201' => 'successfully created',
        '400' => 'the given data is invalid',
        '401' => 'your api key is unauthorized',
        '403' => 'forbidden',
        '404' => 'resource not found',
        '405' => 'method not allowed',
        '408' => 'request timeout',
        '409' => 'resource conflict',
        '429' => 'too many request exception, retry after :retry hours',
        '500' => 'internal server error',
        '503' => 'Database Error',
        'token' => [
            'revoke' => 'successfully revoked',
            'client_revoke_notice' => 'your old token is going to revoke in 24 hours'
        ],
        'province_id' => 'province_id is required',
        'county_id' => 'county_id is required',
        'district_id' => 'district_id is required',
        'rural_dataset' => 'dataset_name and rural_id are required',
        'matched' => 'village match complete',
        'db_id' => 'post_id, vk_id and amar_id are required',
        'unauthorized_user' => 'x-user-id is required',
        'both_filled' => "both tour_id and block_id can't be filled together",
        'both_empty' => "both block_id or tour_id can't be empty",
        'null_field' => "tour_id or block_id can't be null",
        'notSuccess' => "file status is failed or pending",
        'link_expired'=>"the link has reached to expiration time",

        'recordExists' => 'this record exists in the dataset',
        'delete' => 'record deleted successfully',
        'sms_part1'=>'کد پستی های درخواستی شما :',
        'sms_part2'=>' با استفاده از آدرس  ',
        'sms_part3'=>'در دسترس است.',


        'error' => [
            'no_data' => 'your request has no content or content is not valid',
            'try_later' => 'please try later',
            'exist_plan_for_next_period' => 'you can not create new invoice, because have plan for next period!',
            'fields_not_supplied' => 'fields not supplied',
            'validation_regex' => 'validation.regex',
            'empty_result' => 'empty result',
            'not_found' => 'not found',
            'resource_not_found' => 'resource not found',
            'model_not_found' => 'requested :model not found',
            'unauthorized' => 'unauthorized',
            'query' => 'query exception',
            'route_not_found'=>'this route does not exists',
            'ResMsg'=> 'ناموفق',
            'payment'=> 'قیمت گواهی یافت نشد'
        ],
        'success' => [
            'update' => 'updated successfully',
            'create' => 'successfully created',
            'delete' => 'successfully deleted',
            'validation_email_sent' => 'validation email sent',
            'ok_validation' => 'your account has been successfully validated',
            'forgot_password' => 'please check your email to continue!',
            'unsubscribe' => 'you have unsubscribed successfully',
            'ResMsg'=> 'موفق'
        ]
    ]
];
