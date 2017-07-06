<?php
/**
 * This file is part of the daikon-cqrs/cqrs project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Daikon\Cqrs\EventStore;

interface StreamStoreInterface
{
    public function checkout(
        CommitStreamId $streamId,
        CommitStreamRevision $from = null,
        CommitStreamRevision $to = null
    ): CommitStreamInterface;

    public function commit(
        CommitStreamInterface $stream,
        CommitStreamRevision $storeHead
    ): StoreResultInterface;
}