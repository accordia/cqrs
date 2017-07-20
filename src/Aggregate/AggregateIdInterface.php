<?php
/**
 * This file is part of the daikon-cqrs/cqrs project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Daikon\EventSourcing\Aggregate;

interface AggregateIdInterface
{
    public static function fromNative(string $id): AggregateIdInterface;

    public function toNative(): string;

    public function equals(AggregateIdInterface $aggregateId): bool;

    public function __toString(): string;
}
