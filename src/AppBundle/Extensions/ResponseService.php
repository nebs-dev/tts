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

        return new JSonResponse(array('status' => 'ERROR', 'code' => 400,'message' => 'Bad Request','data' => $errors),400);
    }

    /**
     * Access Denied
     * @return JsonResponse
     */
    public function accessDenied() {
        return new JSonResponse(array('status' => 'ERROR', 'code' => 403,'message' => 'Access Denied'),403);
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
        return new JSonResponse(array('status' => 'ERROR', 'code' => 404,'message' => 'Not Found'),404);
    }
}