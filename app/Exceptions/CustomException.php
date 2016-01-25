<?php
/**
 * Created by PhpStorm.
 * User: lockonDaniel
 * Date: 1/20/16
 * Time: 7:11 PM
 */

namespace App\Exceptions;


class CustomException extends \Exception
{
    /**
     * @var integer
     */
    protected $statusCode = 500;

    /**
     * @param string $message
     * @param integer $statusCode
     */
    public function __construct($message = 'An error occurred', $statusCode = null)
    {
        parent::__construct($message);

        if (!is_null($statusCode)) {
            $this->setStatusCode($statusCode);
        }
    }

    /**
     * @param integer $statusCode
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }

    /**
     * @return integer the status code
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }
}