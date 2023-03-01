<?php 
    return [
        'customer-signup' => [
            // 'confirmation_message' => 'Your request for member access has been submitted successfully, you will get a confirmation',
            'confirmation_message' => 'Request for access submitted. You will receive confirmation by email.',
            'validation_email' => 'Thank you for validating your email address. You will receive confirmation by email.',
            'mail' => [
                'title' => 'Customer Portal Access',
                'subject' => 'Your request for access to the Benchmark member portal'
            ]
            ],
        'customer_activate' => [
            'confirmation_message' => 'Customer activated successfully, and email sent.',
            'mail' => [
                'subject' => 'Reset Password',
            ]
        ],
        'customer_cancel' => [
            'confirmation_message' => 'Customer blocked successfully',
            'confirmation_error' => 'Customer not found',
        ],
        'customer_update' => [
            'confirmation_message' => 'Customer updated successfully'
        ],
        'customer_delete' => [
            'confirmation_message' => 'Customer successfully deleted'
        ],
        'change_order_request' => [
            'not_found' => 'Change Request not found',
            'success' => 'Change order request sent successfully',
            'no_changes' => 'No changes in the order' 
        ],
        'email_verification' => [
            'confirmation_message' => 'Verification email sent'
        ],
        'customer_login' => [
            'success_message' => 'Login Successful',
            'error_message' => 'Invalid email or password'
        ],
        'admin_error_403' => 'Sorry, you are not an authorized administrator',
        'dashboard_error_403' => 'Sorry, dashboard access is not authorized',
        'admin_create' => [
            'confirmation_message' => 'Admin created successfully',
        ],
        'superadmin_update' => [
            'error' => 'Sorry, you cannot update the Super Admin.  Please create a new user if you need to test',
        ],
        'admin_update' => [
            'confirmation_message' => 'Admin updated successfully'
        ],
        'superadmin_delete' => [
            'error' => 'Sorry, you cannot update the Super Admin.  Please create a new user if you need to test'
        ],
        'admin_delete' => [
            'confirmation_message' => 'Admin deleted'
        ],
        'customer_account_page' => [
            'update_message' => 'Account Details Updated Succcessfully'
        ],
        'multiple_customer' => 'More than one customer account was found for the email address',
        'customer_found' => 'Customer details found for the specified account',
        'api_error' => 'Unable to locate any customer details for this email address',
        'customer_not_found' => 'Unable to locate any customer details for this email address',
        'missing_manager' => 'Customer No ({$customerNo}) is missing regional manager details',
    ];
?>
