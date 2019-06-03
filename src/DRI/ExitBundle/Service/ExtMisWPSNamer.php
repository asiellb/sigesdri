<?php
/**
 * Created by PhpStorm.
 * User: Jr.AvaLug
 * Date: 18/11/2016
 * Time: 7:36
 */

namespace DRI\ExitBundle\Service;

use Cocur\Slugify\Slugify;
use Vich\UploaderBundle\Naming\NamerInterface;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use DRI\UsefulBundle\Useful\Useful;

class ExtMisWPSNamer implements NamerInterface
{

    public function name($mission, PropertyMapping $workPlanSynthesis)
    {
        $file = $workPlanSynthesis->getFile($mission);

        $client  = $mission->getApplication()->getClient()->getShortNameSlug();
        $country = $mission->Country()->getIso3();
        $concept = strtolower($mission->getConcept());
        $date    = $mission->getFromDate()->format('dmy');

        $name = sprintf('%s-%s-%s-%s', $client, $country, $concept, $date);

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