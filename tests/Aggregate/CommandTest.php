<?php
/**
 * This file is part of the daikon-cqrs/cqrs project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Daikon\Tests\EventSourcing;

use Daikon\Tests\EventSourcing\Aggregate\Mock\BakePizza;
use PHPUnit\Framework\TestCase;

final class CommandTest extends TestCase
{
    public function testFromNative()
    {
        /** @var BakePizza $bakePizza */
        $bakePizza = BakePizza::fromNative([
            'pizzaId' => 'pizza-42-6-23',
            'ingredients' => ['mushrooms', 'tomatoes', 'onions']
        ]);
        $this->assertEquals('pizza-42-6-23', $bakePizza->getPizzaId());
        $this->assertEquals(0, $bakePizza->getKnownAggregateRevision()->toNative());
        $this->assertEquals(['mushrooms', 'tomatoes', 'onions'], $bakePizza->getIngredients());
    }

    public function testToNative()
    {
        $bakePizzaArray = [
            'pizzaId' => 'pizza-42-6-23',
            'revision' => 0,
            'ingredients' => ['mushrooms', 'tomatoes', 'onions']
        ];
        $this->assertEquals($bakePizzaArray, BakePizza::fromNative($bakePizzaArray)->toNative());
    }
}
