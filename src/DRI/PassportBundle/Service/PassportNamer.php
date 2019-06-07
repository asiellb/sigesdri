<?php
/**
 * Created by PhpStorm.
 * User: Jr.AvaLug
 * Date: 18/11/2016
 * Time: 7:36
 */

namespace DRI\PassportBundle\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

use Vich\UploaderBundle\Naming\NamerInterface;
use Vich\UploaderBundle\Mapping\PropertyMapping;

use DRI\PassportBundle\Entity\Passport;

class PassportNamer implements NamerInterface
{
    /**
     * @param Passport $passport
     * @param PropertyMapping $firstPage
     * @return string
     */
    public function name($passport, PropertyMapping $firstPage)
    {
        $file = $firstPage->getFile($passport);
        $number = $passport->getNumber();
        $name = $number;

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