<?php
declare(strict_types=1);

namespace App\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;
use Closure;
use Psr\Http\Message\UploadedFileInterface;

class UploadPackageForm extends Form
{
    /**
     * Mapping of supported file extensions to their
     * MIME type names.
     */
    protected const SUPPORTED_FILE_TYPES = [
        'png' => 'image/png',
        'jpeg' => 'image/jpeg',
        'jpg' => 'image/jpeg',
        'zip' => 'application/zip',
    ];

    /**
     * Maximum size of uploaded file(s) in Mebibytes.
     */
    protected const FILE_MAX_SIZE = 10;

    /**
     * {@inheritDoc}
     */
    protected function _buildSchema(Schema $schema): Schema
    {
        return $schema
            ->addField('file', 'uploadedFile')
            ->addField('key', 'string');
    }

    /**
     * {@inheritDoc}
     */
    public function validationDefault(Validator $validator): Validator
    {
        return $validator
            ->allowEmptyString('key')

            ->requirePresence('file')
            ->uploadedFile('file', [
                'types' => $this->supportedMimeTypes(),
                'maxSize' => $this->fileMaxSizeInBytes(),
            ], sprintf(
                'Your file must be a JPEG, PNG or ZIP file, not larger than %d MB',
                self::FILE_MAX_SIZE
            ))

            ->add('image', 'validFileExtension', [
                'rule' => Closure::fromCallable([$this, 'isValidFileExtension']),
                'message' => 'Unsupported file type'
            ]);
    }

    /**
     * Returns the maximum allowed size of a single file in bytes.
     *
     * @return integer
     */
    public function fileMaxSizeInBytes(): int
    {
        return self::FILE_MAX_SIZE * 1024 * 1024;
    }

    /**
     * Returns a list of MIME types currently supported
     * for listing images.
     *
     * @return array
     */
    public function supportedMimeTypes(): array
    {
        return array_unique(
            array_values(self::SUPPORTED_FILE_TYPES)
        );
    }

    /**
     * Returns a list of file extensions currently supported
     * for listing images.
     *
     * @return array
     */
    public function supportedFileExtensions(): array
    {
        return array_keys(self::SUPPORTED_FILE_TYPES);
    }

    /**
     * Custom validation method used to see if the uploaded
     * file has a valid file extension.
     *
     * @param UploadedFileInterface $value
     *
     * @return boolean
     */
    public function isValidFileExtension(UploadedFileInterface $value): bool
    {
        return in_array(
            pathinfo($value->getClientFilename(), PATHINFO_EXTENSION),
            $this->supportedFileExtensions()
        );
    }
}
