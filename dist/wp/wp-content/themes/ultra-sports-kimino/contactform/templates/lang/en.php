<?php
return [
    // Validation messages (server-side final check)
    'validation' => [
        // Generic messages
        'input_required' => 'Please enter this field.',
        'invalid' => 'The input content is incorrect.',

        // Individual format checks
        'email_invalid' => 'Please enter a valid email address.',
        'tel_invalid' => 'Please enter a valid phone number.',
    ],

    // Error messages (server-side errors)
    'error' => [
        'csrf' => 'Invalid request.',
        'timeout' => 'Session has expired. Please try again.',
        'no_data' => 'No submission data found.',
        'send_failed' => 'Failed to send email. Please try again later.',
        'system_error' => 'A system error occurred.',
    ],
];