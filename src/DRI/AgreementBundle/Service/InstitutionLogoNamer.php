<?php
/**
 * Created by PhpStorm.
 * User: Jr.AvaLug
 * Date: 18/11/2016
 * Time: 7:36
 */

namespace DRI\AgreementBundle\Service;

use Vich\UploaderBundle\Naming\NamerInterface;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use DRI\UsefulBundle\Useful\Useful;

class InstitutionLogoNamer implements NamerInterface
{

    public function name($institution, PropertyMapping $logo)
    {
        $file = $logo->getFile($institution);

        $countryIso3 = Useful::getSlug($institution->getCountry()->getIso3());
        $institutionName = $institution->getNameSlug();

        $name = $countryIso3.'_'.$institutionName;

        if ($extension = $this->getExtension($file)) {
            $name = sprintf('%s.%s', $name, $extension);
        }

        return $name;
    }

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