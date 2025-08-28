<?php
namespace App\Domain\Discount;

use App\Entity\Product;
use App\Entity\DiscountRule;

final class PercentDiscount implements DiscountStrategyInterface
{
    public function supports(DiscountRule $rule): bool
    {
        return strtoupper($rule->getType()) === 'PERCENT';
    }

    public function apply(Product $product, DiscountRule $rule): float
    {
        $price = (float) $product->getPriceGross();
        $percent = (float) $rule->getValue(); // 10 => -10%
        $discount = $price * ($percent / 100);
        $final = max($price - $discount, 0);
        return (float) number_format($final, 2, '.', '');
    }
}
