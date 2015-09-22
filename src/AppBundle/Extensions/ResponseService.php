<?php

namespace AppBundle\Extensions;

use JMS\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;

class ResponseService {

    protected $serializer;

    function __construct(Serializer $serializer) {
        $this->serializer = $serializer;
    }

    public function badRequest($errors = null) {
        if(!is_null($errors)) {
            $errors = json_decode($this->serializer->serialize($errors, 'json'));
        }

        return new JSonResponse(array('status' => 'ERROR', 'code' => 400,'message' => 'Bad Request','data' => $errors),400);
    }

    public function accessDenied() {
        return new JSonResponse(array('status' => 'ERROR', 'code' => 403,'message' => 'Access Denied'),403);
    }

    public function success($data) {
        $json = json_decode($this->serializer->serialize($data, 'json'));
        return new JSonResponse(array('status' => 'OK', 'code' => 200,'message' => 'OK','data' => $json),200);
    }
}