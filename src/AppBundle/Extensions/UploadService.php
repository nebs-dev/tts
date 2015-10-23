<?php

namespace AppBundle\Extensions;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UploadService {

    private $em;
    private $container;

    function __construct(EntityManager $em, ContainerInterface $container) {
        $this->em = $em;
        $this->container = $container;
    }

    /**
     * Upload base64 file
     * @param $file
     * @param $request
     * @return bool|string
     */
    public function uploadBase64File($request) {
        try {
//            list($type, $file) = explode(';', $file);
//            list(, $file) = explode(',', $file);
//            $file = base64_decode($file);

//            $data = explode(',', $file);
            $file = $request->request->get('image');
            $file = base64_decode($file);

            $urlPath = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();
            $basePath = $this->container->get('kernel')->getRootDir() . '/../web';

            $random = substr( md5(rand()), 0, 7);
            $imageName = sha1('MIDGET' . $random .'NINJA');

            $filePath = 'uploads/' . $imageName . '.png';

            file_put_contents($basePath . $filePath, $file);

            return $filePath;

        } catch(\ExportException $e) {
            return false;
        }
    }
}