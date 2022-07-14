<?php


namespace App\ResponseHandler;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\Exception\NoDataFoundException;
use App\Exception\NonEmptyFieldsException;
use App\Exception\InvalidDataException;

class ResponseHandler {

    private $logger;

    private $responseObject;

    public function __construct(LoggerInterface $logger) {
        $this->logger = $logger;
        $this->responseObject  = new \StdClass();
    }

    public function successResponse($dataObject = null) {
        $this->responseObject->status = 'success';
        if ($dataObject) {
            $this->responseObject->data = $dataObject;
        }
        return new JsonResponse($this->responseObject);
    }

    public function errorResponse($ex) {
        $this->responseObject->status = 'error';
        $this->responseObject->error = new \StdClass();
        
        if ($ex instanceof AccessDeniedHttpException 
            || $ex instanceof BadRequestHttpException 
            || $ex instanceof NoDataFoundException
            || $ex instanceof InvalidDataException
        ) {
            $message = $ex->getMessage();
        } else if ($ex instanceof NonEmptyFieldsException) {
            $message = 'Cannot insert empty value for parameter(s)!';
        } else if ($ex instanceof UniqueConstraintViolationException) {
            $message = 'Data with request parameters already exist!';
        } else {
            $message = 'An error occurred, please check your data and try again.';
        }
        $this->logger->info($ex->getMessage());
        $this->responseObject->error->message = $message;
        return new JsonResponse($this->responseObject);
    }
}

?>