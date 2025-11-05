<?php

declare(strict_types=1);

namespace Lyrasoft\ShopGo\Entity;

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
// phpcs:disable
// todo: remove this when phpcs supports 8.4
#[Table('addresses', 'address')]
#[\AllowDynamicProperties]
class Address implements EntityInterface, AddressAwareInterface
{
    use EntityTrait;

    #[Column('id'), PK, AutoIncrement]
    public ?int $id = null;

    #[Column('user_id')]
    public int $userId = 0;

    #[Column('location_id')]
    public int $locationId = 0;
    #[Column('type')]
    public string $type = '';

    #[Column('firstname')]
    public string $firstname = '';

    #[Column('lastname')]
    public string $lastname = '';

    #[Column('name')]
    public string $name = '';

    #[Column('email')]
    public string $email = '';

    #[Column('company')]
    public string $company = '';

    #[Column('address1')]
    public string $address1 = '';

    #[Column('address2')]
    public string $address2 = '';

    #[Column('country')]
    public string $country = '';

    #[Column('state')]
    public string $state = '';

    #[Column('city')]
    public string $city = '';

    #[Column('postcode')]
    public string $postcode = '';

    #[Column('phone')]
    public string $phone = '';

    #[Column('mobile')]
    public string $mobile = '';

    #[Column('vat')]
    public string $vat = '';

    #[Column('details')]
    #[Cast(JsonCast::class)]
    public array $details = [];

    #[Column('enabled')]
    #[Cast('bool', 'int')]
    public bool $enabled = false;

    #[Column('formatted')]
    public string $formatted = '';

    #[Column('created')]
    #[CastNullable(Chronos::class)]
    #[CreatedTime]
    public ?Chronos $created = null {
        set(\DateTimeInterface|string|null $value) => $this->created = Chronos::tryWrap($value);
    }

    #[Column('modified')]
    #[CastNullable(Chronos::class)]
    #[CurrentTime]
    public ?Chronos $modified = null {
        set(\DateTimeInterface|string|null $value) => $this->modified = Chronos::tryWrap($value);
    }

    public ?int $addressId {
        get => $this->id;
    }

    #[EntitySetup]
    public static function setup(EntityMetadata $metadata): void
    {
        //
    }

    public function formatByLocation(?Location $location = null, bool $withName = false): string
    {
        return AddressService::formatByLocation($this, $location, $withName);
    }

    public function fillFrom(AddressAwareInterface $address): static
    {
        $this->locationId = $address->locationId;
        $this->firstname = $address->firstname;
        $this->lastname = $address->lastname;
        $this->email = $address->email;
        $this->phone = $address->phone;
        $this->mobile = $address->mobile;
        $this->company = $address->company;
        $this->vat = $address->vat;
        $this->address1 = $address->address1;
        $this->address2 = $address->address2;
        $this->postcode = $address->postcode;
        $this->country = $address->country;
        $this->state = $address->state;
        $this->city = $address->city;
        $this->formatted = $address->formatted;

        return $this;
    }
}
