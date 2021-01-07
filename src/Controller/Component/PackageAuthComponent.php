<?php
declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Http\Session;

class PackageAuthComponent extends Component
{
    protected const AUTHORIZED_PACKAGE_SESSION_KEY = 'PackageAuth.authorizedPackage';

    /**
     * @var Session
     */
    protected Session $session;

    /**
     * @var string Id for the package that the current visitor's
     *             allowed to download.
     */
    protected ?string $authorizedPackageId;

    /**
     * {@inheritDoc}
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->session = $this->getController()->getRequest()->getSession();
        $this->authorizedPackageId = $this->session->read(self::AUTHORIZED_PACKAGE_SESSION_KEY);
    }

    public function allowDownloadsFor(string $packageId): self
    {
        return $this->setAuthorizedPackage($packageId);
    }

    public function downloadsAreAllowedFor(string $packageId): bool
    {
        return $packageId === $this->authorizedPackageId;
    }

    protected function setAuthorizedPackage(string $packageId): self
    {
        $this->authorizedPackageId = $packageId;
        $this->session->write(
            self::AUTHORIZED_PACKAGE_SESSION_KEY,
            $this->authorizedPackageId
        );

        return $this;
    }
}
