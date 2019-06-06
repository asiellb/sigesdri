<?php
/**
 * Created by PhpStorm.
 * User: Jr.AvaLug
 * Date: 18/11/2016
 * Time: 7:36
 */

namespace DRI\ClientBundle\Service;

use Vich\UploaderBundle\Naming\NamerInterface;
use Vich\UploaderBundle\Mapping\PropertyMapping;

use Symfony\Component\HttpFoundation\File\UploadedFile;

use DRI\ClientBundle\Entity\Client;

class ClientImageNamer implements NamerInterface
{

    /**
     * Creates a name for the file being uploaded.
     *
     * @param Client $client
     * @param PropertyMapping $clientPicture
     * @return string
     */
    public function name($client, PropertyMapping $clientPicture)
    {
        $file = $clientPicture->getFile($client);
        $ci = $client->getCi();
        $shortNameSlug = $client->getShortNameSlug();
        $name = $ci.'-'.$shortNameSlug;

        if ($extension = $this->getExtension($file)) {
            $name = sprintf('%s.%s', $name, $extension);
        }

        return $name;
    }

    /**
     * @param UploadedFile $file
     * @return mixed|string|null
     */
    private function getExtension(UploadedFile $file)
    {
        $originalName = $file->getClientOriginalName();

        if ($extension = pathinfo($originalName, PATHINFO_EXTENSION)) {
            return $extension;
        }

        if ($extension = $file->guessExtension()) {
            return $extension;
        }

        return null;
    }
}