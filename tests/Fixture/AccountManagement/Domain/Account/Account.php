<?php

namespace Daikon\Tests\Cqrs\Fixture\AccountManagement\Domain\Account;

use Daikon\Cqrs\Aggregate\AggregateIdInterface;
use Daikon\Cqrs\Aggregate\AggregateRoot;
use Daikon\Tests\Cqrs\Fixture\AccountManagement\Domain\Account\Command\RegisterAccount;
use Daikon\Tests\Cqrs\Fixture\AccountManagement\Domain\Account\Command\RegisterOauthAccount;
use Daikon\Tests\Cqrs\Fixture\AccountManagement\Domain\Account\Entity\AccountEntityType;
use Daikon\Tests\Cqrs\Fixture\AccountManagement\Domain\Account\Event\AccountWasRegistered;
use Daikon\Tests\Cqrs\Fixture\AccountManagement\Domain\Account\Event\AuthenticationTokenWasAdded;
use Daikon\Tests\Cqrs\Fixture\AccountManagement\Domain\Account\Event\OauthAccountWasRegistered;
use Daikon\Tests\Cqrs\Fixture\AccountManagement\Domain\Account\Event\OauthTokenWasAdded;
use Daikon\Tests\Cqrs\Fixture\AccountManagement\Domain\Account\Event\PasswordTokenWasAdded;
use Daikon\Tests\Cqrs\Fixture\AccountManagement\Domain\Account\Event\VerificationTokenWasAdded;

final class Account extends AggregateRoot
{
    private $accountState;

    public static function register(RegisterAccount $registerAccount, AccountEntityType $accountStateType): self
    {
        return (new Account($registerAccount->getAggregateId(), $accountStateType))
            ->reflectThat(AccountWasRegistered::viaCommand($registerAccount))
            ->reflectThat(AuthenticationTokenWasAdded::viaCommand($registerAccount))
            ->reflectThat(VerificationTokenWasAdded::viaCommand($registerAccount));
    }

    public static function registerOauth(
        RegisterOauthAccount $registerOauthAccount,
        AccountEntityType $accountStateType
    ): self {
        return (new Account($registerOauthAccount->getAggregateId(), $accountStateType))
            ->reflectThat(OauthAccountWasRegistered::viaCommand($registerOauthAccount))
            ->reflectThat(AuthenticationTokenWasAdded::viaCommand($registerOauthAccount))
            ->reflectThat(OauthTokenWasAdded::viaCommand($registerOauthAccount));
    }

    protected function whenAccountWasRegistered(AccountWasRegistered $accountWasRegistered)
    {
        $this->accountState = $this->accountState
            ->withIdentity($accountWasRegistered->getAggregateId())
            ->withLocale($accountWasRegistered->getLocale())
            ->withRole($accountWasRegistered->getRole());
    }

    protected function whenOauthAccountWasRegistered(OauthAccountWasRegistered $oauthAccountWasRegistered)
    {
        $this->accountState = $this->accountState
            ->withIdentity($oauthAccountWasRegistered->getAggregateId())
            ->withRole($accountWasRegistered->getRole());
    }

    protected function whenAuthenticationTokenWasAdded(AuthenticationTokenWasAdded $tokenWasAdded)
    {
        $this->accountState = $this->accountState->addAuthenticationToken([
            "id" => $tokenWasAdded->getId(),
            "token" => $tokenWasAdded->getToken(),
            "expires_at" => $tokenWasAdded->getExpiresAt()
        ]);
    }

    protected function whenVerificationTokenWasAdded(VerificationTokenWasAdded $tokenWasAdded)
    {
        $this->accountState = $this->accountState->addVerificationToken([
            "id" => $tokenWasAdded->getId(),
            "token" => $tokenWasAdded->getToken()
        ]);
    }

    protected function whenOauthTokenWasAdded(OauthTokenWasAdded $tokenWasAdded)
    {
        $this->accountState = $this->accountState->addOauthToken([
            "id" => $tokenWasAdded->getId(),
            "token" => $tokenWasAdded->getToken(),
            "token_id" => $tokenWasAdded->getTokenId(),
            "service" => $tokenWasAdded->getService(),
            "expires_at" => $tokenWasAdded->getExpiresAt()
        ]);
    }

    protected function whenPasswordTokenWasAdded(PasswordTokenWasAdded $tokenWasAdded)
    {
        // @todd implement
    }

    protected function __construct(AggregateIdInterface $aggregateId, AccountEntityType $accountStateType)
    {
        parent::__construct($aggregateId);
        $this->accountState = $accountStateType->makeEntity([ "identity" => $aggregateId ]);
    }
}
