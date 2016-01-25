<?php
/**
 * Created by PhpStorm.
 * User: lockonDaniel
 * Date: 1/20/16
 * Time: 7:34 PM
 */

namespace App\Exceptions;


class InvalidAccessException extends CustomException
{
    /**
     * @var integer
     */
    protected $statusCode = 403;
}