<?php

namespace App\Enums;

use App\Concerns\WithOptions;

enum EmailProvider : string
{
    use WithOptions;

    case SMTP = 'SMTP';
    case MAILGUN = 'Mailgun';
    case SES = 'Amazon SES';
    case POSTMARK = 'Postmark';
}
