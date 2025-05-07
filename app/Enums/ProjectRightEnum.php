<?php

namespace App\Enums;

enum ProjectRightEnum: string
{
    case Reader = 'reader';
    case Admin = 'admin';
    case Manager = 'manager';
    case Editor = 'editor';
}
