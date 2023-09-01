<?php

declare(strict_types=1);

namespace Vex6\OpenArticles\Uploader;

use PrestaShop\PrestaShop\Core\Image\Uploader\Exception\UploadedImageConstraintException;
use PrestaShop\PrestaShop\Core\Image\Uploader\Exception\ImageUploadException;
use PrestaShop\PrestaShop\Core\Image\Uploader\Exception\MemoryLimitException;
use PrestaShop\PrestaShop\Core\Image\Uploader\ImageUploaderInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ArticleImageUploader  implements ImageUploaderInterface
{
    public const IMAGE_PATH = 'openarticles/views/images/';

    /**
     * @param int $articleId
     * @param UploadedFile $image
     */
    public function upload($articleId, UploadedFile $image)
    {
        $this->checkImageIsAllowedForUpload($image);
        $tempImageName = $this->createTemporaryImage($image);

        $this->deleteOldImage($articleId);
        $destination = _PS_MODULE_DIR_ . self::IMAGE_PATH . $articleId.'.jpg';

        $cacheImage = 'open_articles_mini_' . $articleId . '.jpg';
        if (file_exists(_PS_TMP_IMG_DIR_ . $cacheImage)) {
            @unlink(_PS_TMP_IMG_DIR_ . $cacheImage);
        }

        $this->uploadFromTemp($tempImageName, $destination);
    }

    /**
     * Deletes old image
     *
     * @param $articleId
     */
    private function deleteOldImage($articleId)
    {
        $destination = _PS_MODULE_DIR_ . self::IMAGE_PATH . $articleId.'.jpg';

        if (file_exists($destination)) {
            unlink($destination);
        }
    }

    /**
     * Creates temporary image from uploaded file
     *
     * @param UploadedFile $image
     *
     * @throws ImageUploadException
     *
     * @return string
     */
    protected function createTemporaryImage(UploadedFile $image)
    {
        $temporaryImageName = tempnam(_PS_TMP_IMG_DIR_, 'PS');

        if (!$temporaryImageName || !move_uploaded_file($image->getPathname(), $temporaryImageName)) {
            throw new ImageUploadException('Failed to create temporary image file');
        }

        return $temporaryImageName;
    }

    /**
     * Uploads resized image from temporary folder to image destination
     *
     * @param $temporaryImageName
     * @param $destination
     *
     * @throws ImageUploadException
     * @throws MemoryLimitException
     */
    protected function uploadFromTemp($temporaryImageName, $destination)
    {
        if (!\ImageManager::checkImageMemoryLimit($temporaryImageName)) {
            throw new MemoryLimitException('Cannot upload image due to memory restrictions');
        }

        if (!\ImageManager::resize($temporaryImageName, $destination)) {
            throw new ImageUploadException('An error occurred while uploading the image. Check your directory permissions.');
        }

        unlink($temporaryImageName);
    }



    /**
     * Check if image is allowed to be uploaded.
     *
     * @param UploadedFile $image
     *
     * @throws UploadedImageConstraintException
     */
    protected function checkImageIsAllowedForUpload(UploadedFile $image)
    {
        $maxFileSize = \Tools::getMaxUploadSize();

        if ($maxFileSize > 0 && $image->getSize() > $maxFileSize) {
            throw new UploadedImageConstraintException(sprintf('Max file size allowed is "%s" bytes. Uploaded image size is "%s".', $maxFileSize, $image->getSize()), UploadedImageConstraintException::EXCEEDED_SIZE);
        }

        if (!\ImageManager::isRealImage($image->getPathname(), $image->getClientMimeType())
            || !\ImageManager::isCorrectImageFileExt($image->getClientOriginalName())
            || preg_match('/\%00/', $image->getClientOriginalName()) // prevent null byte injection
        ) {
            throw new UploadedImageConstraintException(sprintf('Image format "%s", not recognized, allowed formats are: .gif, .jpg, .png', $image->getClientOriginalExtension()), UploadedImageConstraintException::UNRECOGNIZED_FORMAT);
        }
    }
}