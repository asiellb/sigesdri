<?php
/**
 * Created by PhpStorm.
 * User: Jr.AvaLug
 * Date: 18/11/2016
 * Time: 7:36
 */

namespace DRI\UserBundle\Service;

use Vich\UploaderBundle\Naming\NamerInterface;
use Vich\UploaderBundle\Mapping\PropertyMapping;

use Symfony\Component\HttpFoundation\File\UploadedFile;

use DRI\UserBundle\Entity\User;

class UserNamer implements NamerInterface
{
    /**
     * @param User $user
     * @param PropertyMapping $userImage
     * @return string
     */
    public function name($user, PropertyMapping $userImage)
    {
        $file = $userImage->getFile($user);
        $id = $user->getId();
        $usernameCanonical = $user->getUsernameCanonical();
        $name = $usernameCanonical.'_'.$id;

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