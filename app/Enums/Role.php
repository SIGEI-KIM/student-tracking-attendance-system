<?php

namespace App\Enums;

enum Role: string
{
    case ADMIN = 'admin';
    case LECTURER = 'lecturer';
    case STUDENT = 'student';
}
