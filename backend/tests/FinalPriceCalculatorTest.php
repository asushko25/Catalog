<?php

namespace App\Tests;

use App\Domain\FinalPriceCalculator;
use App\Domain\Discount\PercentDiscount;
use App\Entity\DiscountRule;
use App\Entity\Product;
use App\Repository\DiscountRuleRepository;
use PHPUnit\Framework\TestCase;

final class FinalPriceCalculatorTest extends TestCase
{
    private function repoWith(?DiscountRule $rule): DiscountRuleRepository
    {
        $repo = $this->createMock(DiscountRuleRepository::class);
        $repo->method('findOneBy')->willReturn($rule);
        return $repo;
    }

    private function productWithPrice(string $price): Product
    {
        $p = new Product();
        $p->setName('Test')->setCategory('x')->setCurrency('PLN')->setPriceGross($price);
        $p->setCreatedAt(new \DateTimeImmutable());
        return $p;
    }

    public function testWithoutRule_returnsOriginalPrice(): void
    {
        $calc = new FinalPriceCalculator($this->repoWith(null), [new PercentDiscount()]);
        $p = $this->productWithPrice('100.00');

        $this->assertSame(100.00, $calc->calculate($p));
    }

    public function testPercentDiscount_applied(): void
    {
        $rule = (new DiscountRule())->setType('PERCENT')->setValue('10'); // -10%
        $calc = new FinalPriceCalculator($this->repoWith($rule), [new PercentDiscount()]);
        $p = $this->productWithPrice('99.99');

        $this->assertSame(89.99, $calc->calculate($p));
    }

    public function testPercentCannotGoBelowZero(): void
    {
        $rule = (new DiscountRule())->setType('PERCENT')->setValue('200'); // -200%
        $calc = new FinalPriceCalculator($this->repoWith($rule), [new PercentDiscount()]);
        $p = $this->productWithPrice('10.00');

        $this->assertSame(0.00, $calc->calculate($p));
    }
}
