<?php

namespace AppBundle\Extensions;

use JMS\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use stdClass;

class ResponseService {

    protected $serializer;

    function __construct(Serializer $serializer) {
        $this->serializer = $serializer;
    }

    /**
     * Bad Request
     * @param null $errors
     * @return JsonResponse
     */
    public function badRequest($errors = null) {
        if(!is_null($errors)) {
            $errors = json_decode($this->serializer->serialize($errors, 'json'));
        }

        return new JSonResponse(array('status' => 'ERROR', 'code' => 400,'message' => 'Bad Request','data' => $errors),200);
    }

    /**
     * Access Denied
     * @return JsonResponse
     */
    public function accessDenied($message = 'Access Denied') {
        return new JSonResponse(array('status' => 'ERROR', 'code' => 403,'message' => $message),200);
    }

    /**
     * Success
     * @param $data
     * @return JsonResponse
     */
    public function success($data = null) {
        $data = (is_null($data)) ? new stdClass() : $data;

        $json = json_decode($this->serializer->serialize($data, 'json'));
        return new JSonResponse(array('status' => 'OK', 'code' => 200,'message' => 'OK','data' => $json),200);
    }

    /**
     * Not Found
     * @return JsonResponse
     */
    public function notFound() {
        return new JSonResponse(array('status' => 'ERROR', 'code' => 404,'message' => 'Not Found'),200);
    }

    /**
     * Internal Server Error
     * @return JsonResponse
     */
    public function internalServerError($message = 'Internal Server Error', $errors = null) {
        if(!is_null($errors)) {
            $errors = json_decode($this->serializer->serialize($errors, 'json'));
        }

        return new JSonResponse(array('status' => 'ERROR', 'code' => 500,'message' => $message,'data' => $errors),200);
    }
}