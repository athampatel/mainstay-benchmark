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
        'customer_already_exists' => 'Customer already exists',
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
        'user_not_found' => 'user not found',
        'change_order_request' => [
            'not_found' => 'Change Request not found',
            'success' => 'Change order request sent successfully',
            'no_changes' => 'No changes in the order',
            'request_exsist'    => 'An order change request has already been submitted.'
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
        'admin_customer_create' => [
            'success' => 'The system has successfully created a new customer.',
            'mail' => [
                'success' => 'A user has been created.',
                'error' => 'Oops something went wrong',
            ]
        ],
        'customer_not_found' => 'Customer not found',
        'email' => [
            'admin' => [
                'customer_activate' => [
                    'title' => 'Your account is activated.',
                    'subject' => 'Your account is activated. Please set the password.',
                    'body' => '<p>Your account has been activated for the Benchmark Member Portal access. Please check and set a new password. <br/>'
                ],
                'customer_create' => [
                    'title' => 'Your Login Credentials',
                    'subject' => 'Your Login Credentials',
                ],
                'admin_create' => [
                    'title' => 'Your Login Credentials',
                    'subject' => 'Your Login Credentials'
                ],
                'change_order' => [
                    'title' => 'Change Order Request',
                    'subject' => 'New Change Order Request'
                ]
            ],
            'customer' => [
                'customer_create' => [
                    'message' => 'Thank you for validating your email address. You will receive a confirmation.',
                    'requested_already' => 'You have already made a request. Please wait.',
                    'deleted_by_admin' => 'Please Contact Support',
                    'title' => 'New customer request for portal access',
                    'subject' => 'New customer request for member portal access'
                ]
            ]

        ],
        '403' => [
            'role' => [
                'view' => 'Sorry, but you do not have the necessary authorization to view any roles.',
                'create' => 'Sorry, but you do not have the necessary authorization to create any roles.',
                'edit' => 'Sorry, but you do not have the necessary authorization to edit any roles.',
                'delete' => 'Sorry, but you do not have the necessary authorization to delete any roles.'
            ]
            ],
        'admin' => [
            'role' => [
                'create' => 'Role has been created !!',
                'update' => 'Role has been updated !!',
                'delete' => 'Role has been deleted !!'
            ],
            'change_order' => [
                'approve' => 'The approved change order request.',
                'decline' => 'The declined change order request.'   
            ]
        ],
        'notification' => [
            'signup' => 'New Sign Up Request from Customer',
            'change_order' => 'New Change Order Request from Customer',
            'contact_details' => 'New Contact details update request from Customer',
            'inventory_update' => 'New Inventory update request from Customer'
        ]
    ];
?>
