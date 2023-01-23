<?php

namespace App\Enums;

enum Action: string
{
    case Add = 'add';
    case Delete = 'delete';
    case Modify = 'modify';
}
