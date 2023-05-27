<?php 
    return [
        'customer-signup' => [
            'confirmation_message' => 'A request for access has been submitted. You will receive a confirmation via email.',
            'validation_email' => 'Thank you for validating your email address. You will receive a confirmation via email.',
            'mail' => [
                'title' => 'Customer Portal Access',
                'subject' => 'Your request for access to the Benchmark member portal has been received.'
            ],
            'password-reset' => 'Your password has been updated successfully. Please use your new credentials to log in.'
        ],
        'customer_already_exists' => 'This customer already exists.',
        'customer_activate' => [
            'confirmation_message' => 'The customer has been activated successfully, and an email has been sent.',
            'mail' => [
                'subject' => 'Reset Password',
            ]
        ],
        'customer_cancel' => [
            'confirmation_message' => 'The customer has been blocked successfully.',
            'confirmation_error' => 'The customer was not found.',
        ],
        'customer_update' => [
            'confirmation_message' => 'The customer has been updated successfully.'
        ],
        'customer_delete' => [
            'confirmation_message' => 'The customer has been successfully deleted.'
        ],
        'user_not_found' => 'The user was not found.',
        'change_order_request' => [
            'not_found' => 'The change request was not found.',
            'success' => 'The change order request has been sent successfully.',
            'no_changes' => 'There are no changes to be made to the order.',
            'request_exsist'    => 'An order change request has already been submitted.'
        ],
        'email_verification' => [
            'confirmation_message' => 'A verification email has been sent.'
        ],
        'customer_login' => [
            'success_message' => 'Login successful.',
            'error_message' => 'The email or password entered is invalid.'
        ],
        'admin_error_403' => 'Sorry, you are not an authorized administrator.',
        'dashboard_error_403' => 'Sorry, access to the dashboard is not authorized.',
        'admin_create' => [
            'confirmation_message' => 'The Admin has been created successfully.',
        ],
        'superadmin_update' => [
            'error' => 'Sorry, you cannot update the Super Admin. Please create a new user if you need to test.',
        ],
        'admin_update' => [
            'confirmation_message' => 'The Admin has been updated successfully.'
        ],
        'superadmin_delete' => [
            'error' => 'Sorry, you cannot delete the Super Admin. Please create a new user if you need to test.'
        ],
        'admin_delete' => [
            'confirmation_message' => 'The Admin has been deleted.'
        ],
        'customer_account_page' => [
            'update_message' => 'The account details have been updated successfully.'
        ],
        'multiple_customer' => 'More than one customer account was found for the email address.',
        'customer_found' => 'Customer details have been found for the specified account.',
        'api_error' => 'We are unable to locate any customer details for this email address.',
        'customer_not_found' => 'We are unable to locate any customer details for this email address.',
        // 'missing_manager' => 'Customer Number ({$customerNo}) is missing the regional manager details',
        'missing_manager' => 'The regional manager details are missing for Customer Number ({$customerNo}).',
        'admin_customer_create' => [
            'success' => 'A new customer has been created successfully.',
            'mail' => [
                'success' => 'A user has been created.',
                'error' => 'Oops! Something went wrong.',
            ]
        ],
        'customer_not_found' => 'The customer was not found.',
        'email' => [
            'admin' => [
                'customer_activate' => [
                    'title' => 'Your account has been activated.',
                    'subject' => 'Your account has been activated. Please set your password.',
                    'body' => '<p>Your account has been activated. Please set a password in the Benchmark Member portal.<br/> Please check your account and set a new password. <br/>'
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
                    'message' => 'Thank you for validating your email address. You will receive a confirmation message.',
                    'requested_already' => 'You have already made a request. Please wait for a response.',
                    'deleted_by_admin' => 'Please contact support for assistance.',
                    'title' => 'New customer request for portal access',
                    'subject' => 'New customer request for member portal access'
                ]
                ],

            'help' => [
                'title' => 'Help Request',
                'subject' => 'Help Request'
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
                'create' => 'Role has been created.',
                'update' => 'Role has been updated.',
                'delete' => 'Role has been deleted.'
            ],
            'change_order' => [
                'approve' => 'The request for a change order has been approved.',
                'decline' => 'The request for a change order has been declined.'   
            ],
            'inventory_update' => [
                'error' => 'It is not possible to update the quantity of the item.',
                'success' => 'The quantity has been updated successfully.',
                'validation' => [
                    'new_quanity' => 'Please provide a value for the new quantity. It is required.'
                ]
            ]
        ],
        'notification' => [
            'signup' => 'A new signup request has been received from a customer.',
            'change_order' => 'A new change order request has been received from a customer.',
            'contact_details' => 'A customer has requested an update to their contact details.',
            'inventory_update' => 'A customer has submitted a request to update the inventory.'
        ],
        'user_request_not_found' => 'We were unable to find a customer with the specified details.',
        'api_error_message' => 'An error has occurred.',
        'api_error_email' => [
            'title' => 'SDE API Error',
            'subject' => 'SDE API Error Occurred',
            'message' => 'the error api message',
        ],
        'api_connection_error_email' => [
            'title' => 'SDE API Error',
            'subject' => 'SDE API Connection Error Occurred',
            'message' => 'the api conection error message',
        ],
        '404_page_message' => 'Sorry, this page does not exist.',      
        'label' => [
            'admin' => [
                // user requests
                'users'                     => 'Benchmark Staff Users',
                'manager'                   => 'Regional Managers',
                'total_customer'            => 'Total Customers',
                'vmi_customer'              => ' VMI Customers',
                'signup_request'            => 'Customer Sign-up Requests',
                'change_order_request'      => 'Change Order Requests',
                'export_request'            => 'Export Request',
                'full_name'                 => 'Full Name',
                'email_address'             => 'Email Address',
                'company_name'              => 'Company Name',
                'sl'                        => 'sl',
                'user_name'                 => 'User Name',
                'password'                  => 'Password',
                'user_email'                => 'Customer Email',
                'assign_roles'              => 'Assign Roles',
                'user_account_name'         => 'User Account Name',
                'send_login_credentials'    => 'Send Login Credentials',
                'role_name'                 => 'Role Name',
                'search_customer_number_email' => 'Search Customer With Contact Email',
                'customer_no'               => 'Customer Number',
                'customer_email'            => 'Customer Email',
                'customer_name'             => 'Customer Name',
                'contact_name'              => 'Contact Name',
                'contact_email'             => 'Contact Email',
                'ar_division_no'            => 'AR Division Number',
                'phone_no'                  => 'Phone Number',
                'address_line_1'            => 'Address Line 1',
                'address_line_2'            => 'Address Line 2',
                'address_line_3'            => 'Address Line 3',
                'city'                      => 'City',
                'state'                     => 'State',
                'zipcode'                   => 'Zipcode',
                'division_no'               => 'Division Number',
                'permissions'               => 'Permissions',
                'manager_no'                => 'Manager Number',
                'admin_name'                => 'Display Name',
                'admin_email'               => 'Email',
                'admin_username'            => 'Username',
                'contact_no'                => 'Contact Number',
                'relational_manager_no'     => 'Benchmark Regional Manager Number',
                'relational_manager_name'   => 'Benchmark Regional Manager Name',
                'relational_manager_email'  => 'Benchmark Regional Manager Email',
                'generate_random_password'  => 'Generate Random Password',
                'relational_manager'        => 'Benchmark Regional Manager',
                'region_manager'            => 'Region Manager',
                'order_date'                => 'Order Date',
                'contact_code'              => 'Contact Code',
                // 'contact_name'              => 'Contact Name',
                'buttons' => [
                    'create' => 'Create',
                    'save_role' => 'Save Role',
                    'search' => 'Search',
                    'create_customer' => 'Create Customer',
                    'create_new_admin' => 'Create New User',
                    'customer_search' => 'Search',
                    'create_new_role' => 'Create New Role',
                    'update_role' => 'Update Role',
                    'save_admin' => 'Save User',
                    'lookup_customer' => 'Lookup Customer',
                    'decline_request' => 'Decline Request',
                    'activate_customer' => 'Activate Customer',
                    'create_request' => 'Create Request',
                ]
            ],
            'customer' => [

            ]
        ],
        'validation' => [
            'admin' => [
                'search_customer_number_email' => 'The search for a customer requires either the customer number or email to be provided in the search field. This field is mandatory.',
                'customer_search_unable' => 'We were unable to locate any customer details with the information provided.',
                'customer_detail_found' => 'Customer details have been found for the specified account.',
            ]
            ],
        // vmi inventory
        'vmi_inventory' => [
            'no_change' => 'No changes have been added.',
        ],


        // page title
        'page_title' => [
            'customers'=> [
                'dashboard' => 'Dashboard',
                'invoice' => 'Invoiced Orders',
                'open_order' => 'Open Orders',
                'analysis' => 'Analysis',
                'vmi' => 'VMI',
                'account_setting' => 'Account Settings',
                'help' => 'Help',
                'invoice_detail' => 'Invoice Details',
                'change_order' => 'Open Orders',
                'change_request' => 'Change Orders'
            ]
        ],

        'export_message' => [
            'message' => 'Your Export Request Has been received. You will be notified when it is complete.'
        ],
        'analysis_message' => [
            'message' => 'Your Export Request Has been received. You will be notified when it is complete.'
        ],

        'help_message' => [
            'message' => 'Your message has been sent successfully.'
        ],

        'change_order_cancel' => [
            'success' => 'Change order request cancelled successfully.',
            'not_found' => 'Unable to find the change order request.'
        ]
    ];
    // {{ config('constants.label.admin.role_name') }}
    // we lost the connection in SDE API so the portal doesn't work
?>
