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

use DRI\AgreementBundle\Entity\Institutional;

class AgrIntDCONamer implements NamerInterface
{
    /**
     * Creates a name for the file being uploaded.
     *
     * @param Institutional $institutional
     * @param PropertyMapping $univLogo
     * @return string
     */
    public function name($institutional, PropertyMapping $univLogo)
    {
        $file = $univLogo->getFile($institutional);

        $country     = strtolower($institutional->getInstitution()->getCountry()->getIso3());
        $institution = strtolower($institutional->getInstitution()->getAcronym());
        $date        = $institutional->getStartDate()->format('dmy');

        $name = sprintf('%s-%s-%s',$country, $institution, $date);

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