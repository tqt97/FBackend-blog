<?php

namespace App\Enums;

use App\Concerns\WithOptions;

enum TypeField: string
{
    use WithOptions;

    case Text = 'text';
    case Boolean = 'boolean';
    case Select = 'select';
    case Textarea = 'textarea';
    case Datetime = 'datetime';
}
