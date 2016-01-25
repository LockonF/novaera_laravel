<?php
/**
 * Created by PhpStorm.
 * User: lockonDaniel
 * Date: 1/20/16
 * Time: 7:43 PM
 */

namespace App\Exceptions;


class NotFoundException extends CustomException
{
    /**
     * @var integer
     */
    protected $statusCode = 404;
}