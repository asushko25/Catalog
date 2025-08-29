<?php
declare(strict_types=1);

namespace App\Domain;

use App\Entity\Product;
use App\Repository\DiscountRuleRepository;
use App\Domain\Discount\DiscountStrategyInterface;
use Traversable;

final class FinalPriceCalculator
{
    /** @var DiscountStrategyInterface[] */
    private array $strategies;

    public function __construct(
        private readonly DiscountRuleRepository $rules,
        iterable $strategies,
        private readonly float $fallbackDiscount = 0.0 
    ) {
        $this->strategies = $strategies instanceof Traversable
            ? iterator_to_array($strategies)
            : (array) $strategies;
    }

    public function calculate(Product $product): float
    {
        $price = (float) $product->getPriceGross();

        $rule = $this->rules->findOneBy([], ['id' => 'DESC']);
        if ($rule) {
            foreach ($this->strategies as $s) {
                if ($s instanceof DiscountStrategyInterface && $s->supports($rule)) {
                    return $this->round2($s->apply($product, $rule));
                }
            }
        }

        if ($this->fallbackDiscount > 0.0) {
            $price = $price * (1 - $this->fallbackDiscount);
        }

        return $this->round2($price);
    }

    private function round2(float $v): float
    {
        return round($v, 2);
    }
}
