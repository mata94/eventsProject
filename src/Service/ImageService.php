<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Filesystem;
use Imagick;

class ImageService
{
    private ParameterBagInterface $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    /**
     * @param UploadedFile $file
     * @return string|null
     * @throws \ImagickException
     */
    public function uploadImage(UploadedFile $file): ?string
    {
        if (!$file instanceof UploadedFile) {
            throw new \Exception('No file uploaded');
        }

        $imageName = uniqid() . '.' . $file->guessExtension();

        /** @var string $path */
        $path = $this->params->get('kernel.project_dir');
        $targetPath =  $path . '/public/images';

        $filePath = $targetPath . '/' . $imageName;
        $file->move($targetPath,$imageName);

        $imagick = new Imagick($filePath);
        $imagick->resizeImage(459, 129, Imagick::FILTER_LANCZOS, 1);
        $imagick->writeImage($filePath);
        $imagick->clear();
        $imagick->destroy();

        return $imageName;
    }

    /**
     * @param string $imagePath
     * @return void
     */
    public function removeImage(string $imagePath): void
    {
        $filesystem = new Filesystem();
        /** @var string $path */
        $path = $this->params->get('kernel.project_dir');
        $fullPath = $path . '/public/images/' . $imagePath;

        if ($filesystem->exists($fullPath)) {
            $filesystem->remove($fullPath);
        }
    }
}