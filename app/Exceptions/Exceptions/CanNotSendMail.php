<?php

namespace App\Exceptions\Exceptions;

use Exception;

class CanNotSendMail extends Exception
{
    public static function postNotPublished()
    {
        return new self('The post is not published.');
    }
}
