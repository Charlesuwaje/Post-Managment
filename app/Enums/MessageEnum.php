<?php

namespace App\Enums;

enum MessageEnum: string
{
    case POST_DELETED = 'Post deleted successfully.';
    case POST_CREATED = 'Post created successfully.';
    case POST_UPDATED = 'Post updated successfully.';

    case ROLE_DELETED = 'Role deleted successfully.';
    case ROLE_CREATED = 'Role created successfully.';
    case ROLE_UPDATED = 'Role updated successfully.';
}
