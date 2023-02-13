<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    MIT
 */

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Entity;

use DateTimeInterface;
use Lyrasoft\ShopGo\Data\Contract\AddressAwareInterface;
use Lyrasoft\ShopGo\Service\AddressService;
use Windwalker\Core\DateTime\Chronos;
use Windwalker\ORM\Attributes\AutoIncrement;
use Windwalker\ORM\Attributes\Cast;
use Windwalker\ORM\Attributes\CastNullable;
use Windwalker\ORM\Attributes\Column;
use Windwalker\ORM\Attributes\CreatedTime;
use Windwalker\ORM\Attributes\CurrentTime;
use Windwalker\ORM\Attributes\EntitySetup;
use Windwalker\ORM\Attributes\PK;
use Windwalker\ORM\Attributes\Table;
use Windwalker\ORM\Cast\JsonCast;
use Windwalker\ORM\EntityInterface;
use Windwalker\ORM\EntityTrait;
use Windwalker\ORM\Metadata\EntityMetadata;

/**
 * The Address class.
 */
#[Table('addresses', 'address')]
#[\AllowDynamicProperties]
class Address implements EntityInterface, AddressAwareInterface
{
    use EntityTrait;

    #[Column('id'), PK, AutoIncrement]
    protected ?int $id = null;

    #[Column('user_id')]
    protected int $userId = 0;

    #[Column('location_id')]
    protected int $locationId = 0;

    #[Column('firstname')]
    protected string $firstname = '';

    #[Column('lastname')]
    protected string $lastname = '';

    #[Column('fullname')]
    protected string $fullname = '';

    #[Column('email')]
    protected string $email = '';

    #[Column('company')]
    protected string $company = '';

    #[Column('address1')]
    protected string $address1 = '';

    #[Column('address2')]
    protected string $address2 = '';

    #[Column('country')]
    protected string $country = '';

    #[Column('state')]
    protected string $state = '';

    #[Column('city')]
    protected string $city = '';

    #[Column('postcode')]
    protected string $postcode = '';

    #[Column('phone')]
    protected string $phone = '';

    #[Column('mobile')]
    protected string $mobile = '';

    #[Column('vat')]
    protected string $vat = '';

    #[Column('details')]
    #[Cast(JsonCast::class)]
    protected array $details = [];

    #[Column('enabled')]
    #[Cast('bool', 'int')]
    protected bool $enabled = false;

    #[Column('created')]
    #[CastNullable(Chronos::class)]
    #[CreatedTime]
    protected ?Chronos $created = null;

    #[Column('modified')]
    #[CastNullable(Chronos::class)]
    #[CurrentTime]
    protected ?Chronos $modified = null;

    #[EntitySetup]
    public static function setup(EntityMetadata $metadata): void
    {
        //
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): static
    {
        $this->userId = $userId;

        return $this;
    }

    public function getLocationId(): int
    {
        return $this->locationId;
    }

    public function setLocationId(int $locationId): static
    {
        $this->locationId = $locationId;

        return $this;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFullname(): string
    {
        return $this->fullname;
    }

    public function setFullname(string $fullname): static
    {
        $this->fullname = $fullname;

        return $this;
    }

    public function getCompany(): string
    {
        return $this->company;
    }

    public function setCompany(string $company): static
    {
        $this->company = $company;

        return $this;
    }

    public function getAddress1(): string
    {
        return $this->address1;
    }

    public function setAddress1(string $address1): static
    {
        $this->address1 = $address1;

        return $this;
    }

    public function getAddress2(): string
    {
        return $this->address2;
    }

    public function setAddress2(string $address2): static
    {
        $this->address2 = $address2;

        return $this;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getPostcode(): string
    {
        return $this->postcode;
    }

    public function setPostcode(string $postcode): static
    {
        $this->postcode = $postcode;

        return $this;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getMobile(): string
    {
        return $this->mobile;
    }

    public function setMobile(string $mobile): static
    {
        $this->mobile = $mobile;

        return $this;
    }

    public function getVat(): string
    {
        return $this->vat;
    }

    public function setVat(string $vat): static
    {
        $this->vat = $vat;

        return $this;
    }

    public function getDetails(): array
    {
        return $this->details;
    }

    public function setDetails(array $details): static
    {
        $this->details = $details;

        return $this;
    }

    public function getCreated(): ?Chronos
    {
        return $this->created;
    }

    public function setCreated(DateTimeInterface|string|null $created): static
    {
        $this->created = Chronos::wrapOrNull($created);

        return $this;
    }

    public function getModified(): ?Chronos
    {
        return $this->modified;
    }

    public function setModified(DateTimeInterface|string|null $modified): static
    {
        $this->modified = Chronos::wrapOrNull($modified);

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param  string  $email
     *
     * @return  static  Return self to support chaining.
     */
    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @param  string  $country
     *
     * @return  static  Return self to support chaining.
     */
    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getAddressId(): int
    {
        return (int) $this->getId();
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function setState(string $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): static
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function formatByLocation(?Location $location = null, bool $withName = false): string
    {
        return AddressService::formatByLocation($this, $location, $withName);
    }
}
