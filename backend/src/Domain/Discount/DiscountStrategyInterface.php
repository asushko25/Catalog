<?php
namespace App\Domain\Discount;

use App\Entity\Product;
use App\Entity\DiscountRule;

interface DiscountStrategyInterface
{
    public function supports(DiscountRule $rule): bool;

    public function apply(Product $product, DiscountRule $rule): float;
}
