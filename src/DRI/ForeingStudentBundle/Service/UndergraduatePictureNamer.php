<?php
/**
 * Created by PhpStorm.
 * User: Jr.AvaLug
 * Date: 18/11/2016
 * Time: 7:36
 */

namespace DRI\ForeingStudentBundle\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

use Vich\UploaderBundle\Naming\NamerInterface;
use Vich\UploaderBundle\Mapping\PropertyMapping;

use DRI\ForeingStudentBundle\Entity\Undergraduate;

class UndergraduatePictureNamer implements NamerInterface
{
    /**
     * @param Undergraduate $undergraduate
     * @param PropertyMapping $picture
     * @return string
     */
    public function name($undergraduate, PropertyMapping $picture)
    {
        $file = $picture->getFile($undergraduate);
        $ci = $undergraduate->getCi();
        $fullNameSlug = $undergraduate->getFullNameSlug();
        $name = $ci.'-'.$fullNameSlug;

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