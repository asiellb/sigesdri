<?php
/**
 * Created by PhpStorm.
 * User: Jr.AvaLug
 * Date: 18/11/2016
 * Time: 7:36
 */

namespace DRI\UsefulBundle\Service;

use Vich\UploaderBundle\Naming\NamerInterface;
use Vich\UploaderBundle\Mapping\PropertyMapping;

use Symfony\Component\HttpFoundation\File\UploadedFile;

use DRI\UsefulBundle\Entity\Country;
use DRI\UsefulBundle\Useful\Useful;

class CountriesFlagsNamer implements NamerInterface
{
    /**
     * @param Country $country
     * @param PropertyMapping $flagImage
     * @return false|string|string[]|null
     */
    public function name($country, PropertyMapping $flagImage)
    {
        $file = $flagImage->getFile($country);
        $name = Useful::getSlug($country->getIso3());

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