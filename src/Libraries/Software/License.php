<?php

namespace CarlBennett\API\Libraries\Software;

use \CarlBennett\API\Libraries\Core\DateTimeImmutable;
use \CarlBennett\API\Libraries\Db\Database;
use \DateTimeInterface;
use \DateTimeZone;
use \StdClass;
use \UnexpectedValueException;

class License implements \CarlBennett\API\Interfaces\DatabaseObject, \JsonSerializable
{
    private bool $active = false;
    private string $email_address = '';
    private ?string $id = null;
    private ?DateTimeInterface $issued_datetime = null;
    private string $label = '';
    private ?string $paypal_transaction = null;

    public function __construct(StdClass|string|null $value)
    {
        if ($value instanceof StdClass)
        {
            $this->allocateObject($value);
        }
        else
        {
            $this->setId($value);
            if (!$this->allocate())
            {
                throw new \CarlBennett\API\Exceptions\SoftwareLicenseNotFoundException($value);
            }
        }
    }

    public function allocate(): bool
    {
        $id = $this->getId();
        if (\is_null($id)) return true;

        try
        {
            $q = Database::instance()->prepare('
                SELECT
                    `active`,
                    `email_address`,
                    `id`,
                    `issue_date` AS `issued_datetime`,
                    `label`,
                    `paypal_transaction`
                FROM `software_licenses`
                WHERE `id` = :id LIMIT 1;
            ');

            if (!$q || !$q->execute([':id' => $id]) || $q->rowCount() == 0)
            {
                return false;
            }

            $this->allocateObject($q->fetchObject());
            return true;
        }
        finally
        {
            if ($q) $q->closeCursor();
        }
    }

    private function allocateObject(StdClass $value): void
    {
        $this->setActive($value->active);
        $this->setEmailAddress($value->email_address);
        $this->setId($value->id);
        $this->setIssuedDateTime($value->issued_datetime);
        $this->setLabel($value->label);
        $this->setPayPalTransaction($value->paypal_transaction);
    }

    public function commit(): bool
    {
        $id = $this->getId();
        if (\is_null($id)) $id = \Ramsey\Uuid\Uuid::uuid4();

        try
        {
            $q = Database::instance()->prepare('
                INSERT INTO `software_licenses` (
                    `active`,
                    `email_address`,
                    `id`,
                    `issue_date` AS `issued_datetime`,
                    `label`,
                    `paypal_transaction`
                ) VALUES (
                    :active,
                    :email,
                    :id,
                    :issued_dt,
                    :label,
                    :paypal_txn
                ) ON DUPLICATE KEY UPDATE
                    `active` = :active,
                    `email_address` = :email,
                    `id` = :id,
                    `issue_date` = :issued_dt,
                    `label` = :label,
                    `paypal_transaction` = :paypal_txn
                ;
            ');

            $p = [
                'active' => $this->isActive(),
                'email_address' => $this->getEmailAddress(),
                'id' => $id,
                'issued_dt' => $this->getIssuedDateTime(),
                'label' => $this->getLabel(),
                'paypal_txn' => $this->getPayPalTransaction(),
            ];

            foreach ($p as &$v)
                if ($v instanceof DateTimeInterface)
                    $v = $v->format(self::DATE_SQL);

            if (!$q || !$q->execute($p)) return false;

            $this->setId($id);
            return true;
        }
        finally
        {
            if ($q) $q->closeCursor();
        }
    }

    public function deallocate(): bool
    {
        $id = $this->getId();
        if (\is_null($id)) return false;

        try
        {
            $q = Database::instance()->prepare('DELETE FROM `software_licenses` WHERE `id` = :id LIMIT 1;');
            return $q && $q->execute([':id' => $id]);
        }
        finally
        {
            if ($q) $q->closeCursor();
        }
    }

    public function getEmailAddress(): string
    {
        return $this->email_address;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getIssuedDateTime(): ?DateTimeInterface
    {
        return $this->issued_datetime;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getPayPalTransaction(): ?string
    {
        return $this->paypal_transaction;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'active' => $this->isActive(),
            'email_address' => $this->getEmailAddress(),
            'id' => $this->getId(),
            'issued_datetime' => $this->getIssuedDateTime(),
            'label' => $this->getLabel(),
            'paypal_transaction' => $this->getPayPalTransaction(),
        ];
    }

    public function setActive(bool $value): void
    {
        $this->active = $value;
    }

    public function setEmailAddress(string $value): void
    {
        if (!empty($value) && !\filter_var($value, \FILTER_VALIDATE_EMAIL))
        {
            throw new UnexpectedValueException(\sprintf('Invalid email: %s', $value));
        }

        $this->email_address = $value;
    }

    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    public function setIssuedDateTime(DateTimeInterface|string|null $value): void
    {
        $this->issued_datetime = \is_null($value) ? null : (
            \is_string($value) ? new DateTimeImmutable($value, new DateTimeZone(self::DATE_TZ)) :
                DateTimeImmutable::createFromInterface($value)
        );
    }

    public function setLabel(string $value): void
    {
        $this->label = $value;
    }

    public function setPayPalTransaction(?string $value): void
    {
        $this->paypal_transaction = $value;
    }
}
