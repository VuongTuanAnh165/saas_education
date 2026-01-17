<?php

return [
    'validation_error' => 'The given data is invalid.',
    'unauthenticated' => 'Unauthenticated.',
    'forbidden' => 'Forbidden.',
    'not_found' => 'Not found.',
    'rate_limited' => 'Too many requests. Please try again later.',
    'internal_error' => 'Internal server error.',
    'http_error' => 'An error occurred.',

    // Business errors
    'invalid_credentials' => 'Invalid credentials.',
    'user_suspended' => 'User is suspended.',
    'teacher_suspended' => 'Teacher is suspended.',
    'email_already_used' => 'Email is already used.',
    'invalid_token' => 'Invalid token.',
    'token_expired' => 'Token has expired.',
    'data_integrity_error' => 'System data integrity error.',

    // Success messages
    'auth_login_success' => 'Login successful.',
    'auth_logout_success' => 'Logout successful.',
    'auth_forgot_password_accepted' => 'Password reset request has been accepted.',
    'auth_password_set_success' => 'Password has been set successfully.',

    'admin_teacher_created' => 'Teacher created successfully.',
    'admin_teacher_locked' => 'Teacher locked successfully.',
    'admin_teacher_unlocked' => 'Teacher unlocked successfully.',

    'teacher_student_created' => 'Student created successfully.',

    'student_dashboard_loaded' => 'Dashboard loaded successfully.',
];
