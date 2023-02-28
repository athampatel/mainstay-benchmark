<?php 
    return [
        'customer-signup' => [
            'confirmation_message' => 'Your request for member access has been submitted successfully, you will get a confirmation',
            'validation_email' => 'Thanks for validating your email address, you will get a confirmation',
            'mail' => [
                'title' => 'Customer Portal Access',
                'subject' => 'New customer request for member portal access'
            ]
            ],
        'customer_activate' => [
            'confirmation_message' => 'Customer activated successfully and email sent',
            'mail' => [
                'subject' => 'reset password link',
            ]
        ],
        'customer_cancel' => [
            'confirmation_message' => 'Customer Blocked successfully',
            'confirmation_error' => 'Customer not found',
        ],
        'customer_update' => [
            'confirmation_message' => 'Customer has been updated successfully'
        ],
        'customer_delete' => [
            'confirmation_message' => 'Customer has been deleted'
        ],
        'change_order_request' => [
            'not_found' => 'change request not found',
            'success' => 'Change order request sent successfully',
            'no_changes' => 'No changes in the order' 
        ],
        'email_verification' => [
            'confirmation_message' => 'Verification link sent'
        ],
        'customer_login' => [
            'success_message' => 'Successully Logged in',
            'error_message' => 'Invalid email and password'
        ],
        'admin_error_403' => 'Sorry !! You are Unauthorized to view any admin',
        'dashboard_error_403' => 'Sorry !! You are Unauthorized to view dashboard',
        'admin_create' => [
            'confirmation_message' => 'Admin has been created successfully',
        ],
        'superadmin_update' => [
            'error' => 'Sorry !! You are not authorized to update this Admin as this is the Super Admin. Please create new one if you need to test',
        ],
        'admin_update' => [
            'confirmation_message' => 'Admin has been updated successfully'
        ],
        'superadmin_delete' => [
            'error' => 'Sorry !! You are not authorized to delete this Admin as this is the Super Admin. Please create new one if you need to test'
        ],
        'admin_delete' => [
            'confirmation_message' => 'Admin has been deleted !!'
        ],
        'customer_account_page' => [
            'update_message' => 'Account Details Updated Succcessfully'
        ]
    ];
?>
