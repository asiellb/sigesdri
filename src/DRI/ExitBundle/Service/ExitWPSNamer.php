<?php
/**
 * Created by PhpStorm.
 * User: Jr.AvaLug
 * Date: 18/11/2016
 * Time: 7:36
 */

namespace DRI\ExitBundle\Service;

use Vich\UploaderBundle\Naming\NamerInterface;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use DRI\UsefulBundle\Useful\Useful;

class ExitWPSNamer implements NamerInterface
{

    public function name($exitApplication, PropertyMapping $workPlanSynthesis)
    {
        $file = $workPlanSynthesis->getFile($exitApplication);
        $id = $exitApplication->getId();
        $client = $exitApplication->getClient()->getShortNameSlug();
        $institution = $exitApplication->getInstitution();

        $name = $id.'-'.$client.'-'.$institution;

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