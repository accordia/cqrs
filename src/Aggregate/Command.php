<?php
/**
 * This file is part of the daikon-cqrs/cqrs project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Daikon\Cqrs\Aggregate;

use Daikon\MessageBus\FromArrayTrait;
use Daikon\MessageBus\ToArrayTrait;

abstract class Command implements CommandInterface
{
    use FromArrayTrait;
    use ToArrayTrait;

    /**
     * @MessageBus::deserialize(\Daikon\Cqrs\Aggregate\AggregateId::fromNative)
     */
    private $aggregateId;

    /**
     * @MessageBus::deserialize(\Daikon\Cqrs\Aggregate\AggregateRevision::fromNative)
     */
    private $knownAggregateRevision;

    public function getAggregateId(): AggregateIdInterface
    {
        return $this->aggregateId;
    }

    public function getKnownAggregateRevision(): ?AggregateRevision
    {
        return $this->knownAggregateRevision;
    }

    public function hasKnownAggregateRevision(): bool
    {
        return $this->knownAggregateRevision !== null;
    }

    protected function __construct(AggregateIdInterface $aggregateId, AggregateRevision $knownAggregateRevision = null)
    {
        $this->aggregateId = $aggregateId;
        $this->knownAggregateRevision = $knownAggregateRevision;
    }
}
