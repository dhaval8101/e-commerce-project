<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;  
class CustomExceptionHandler extends Exception
{


    protected $message = 'The subcategory could not be found.';
    
}