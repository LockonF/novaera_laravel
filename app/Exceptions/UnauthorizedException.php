<?php
/**
 * Created by PhpStorm.
 * User: lockonDaniel
 * Date: 12/2/15
 * Time: 4:06 PM
 */

namespace App\Exceptions;

use Tymon\JWTAuth\Exceptions\JWTException;

class UnauthorizedException extends JWTException
{
    /**
     * @var integer
     */
    protected $statusCode = 401;

}