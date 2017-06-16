<?php

namespace Accordia\Tests\Cqrs\Fixture\AccountManagement\Domain\Account\Event;

use Accordia\MessageBus\FromArrayTrait;
use Accordia\MessageBus\ToArrayTrait;
use Accordia\Cqrs\Aggregate\AggregateId;
use Accordia\Cqrs\Aggregate\CommandInterface;
use Accordia\Cqrs\Aggregate\DomainEvent;
use Accordia\Cqrs\Aggregate\Revision;
use Accordia\Tests\Cqrs\Fixture\AccountManagement\Domain\Account\Command\RegisterAccount;
use Accordia\Tests\Cqrs\Fixture\AccountManagement\Domain\Account\Command\RegisterOauthAccount;
use Accordia\Tests\Cqrs\Fixture\AccountManagement\Domain\Account\ValueObject\RandomToken;
use Accordia\Entity\ValueObject\Timestamp;
use Accordia\Entity\ValueObject\Uuid;

final class AuthenticationTokenWasAdded extends DomainEvent
{
    use ToArrayTrait;
    use FromArrayTrait;

    /**
     * @var \Accordia\Entity\ValueObject\Uuid
     * @buzz::fromArray->fromNative
     */
    private $id;

    /**
     * @var \Accordia\Tests\Cqrs\Fixture\AccountManagement\Domain\Account\ValueObject\RandomToken
     * @buzz::fromArray->fromNative
     */
    private $token;

    /**
     * @var \Accordia\Entity\ValueObject\Timestamp
     * @buzz::fromArray->createFromString
     */
    private $expiresAt;

    /**
     * @param CommandInterface $registration
     * @return AuthenticationTokenWasAdded
     */
    public static function viaCommand(CommandInterface $registration): self
    {
        // @todo check $registration instanceof RegiserAccount|RegisterOauthAccount
        /* @var RegisterAccount|RegisterOauthAccount $registration */
        return new static(
            Uuid::generate(),
            $registration->getAggregateId(),
            RandomToken::generate(),
            $registration->getExpiresAt()
        );
    }

    /**
     * @return Uuid
     */
    public function getId(): Uuid
    {
        return $this->id;
    }

    /**
     * @return RandomToken
     */
    public function getToken(): RandomToken
    {
        return $this->token;
    }

    /**
     * @return Timestamp
     */
    public function getExpiresAt(): Timestamp
    {
        return $this->expiresAt;
    }

    /**
     * @param Uuid $id
     * @param AggregateId $aggregateId
     * @param RandomToken $token
     * @param Timestamp $expiresAt
     * @param Revision|null $aggregateRevision
     */
    protected function __construct(
        Uuid $id,
        AggregateId $aggregateId,
        RandomToken $token,
        Timestamp $expiresAt,
        Revision $aggregateRevision = null
    ) {
        parent::__construct($aggregateId, $aggregateRevision);
        $this->id = $id;
        $this->token = $token;
        $this->expiresAt = $expiresAt;
    }
}
