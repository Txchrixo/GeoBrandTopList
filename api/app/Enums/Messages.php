<?php

namespace App\Enums;

enum Messages: string
{
    case LOGIN_SUCCESSFUL = "Login successful";
    case LOGGED_OUT_SUCCESSFULLY = 'Successfully logged out';
    case INVALID_CREDENTIALS = 'Invalid credentials';
    case VALIDATION_FAILED = 'Validation failed';
    case UNAUTHORIZED = 'Unauthorized';
    case RESOURCE_NOT_FOUND = 'Resource not found';
    case RESOURCE_LIST_FETCHED_SUCCESSFULLY = 'Resource list fetched successfully';
    case RESOURCE_FETCHED_SUCCESSFULLY = 'Resource fetched successfully';
    case INTERNAL_SERVER_ERROR_MSG = "Internal Server Error";
    case RESOURCE_DELETED_SUCCESSFULLY = 'Resource deleted successfully';
    case RESOURCE_CREATED_SUCCESSFULLY = 'Resource created successfully';
    case RESOURCE_UPDATED_SUCCESSFULLY = 'Resource updated successfully';
    case NO_UPDATE_FIELDS_PROVIDED = 'At least one field must be provided for update';
}