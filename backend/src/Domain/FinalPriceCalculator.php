<?php
namespace App\Domain;

use App\Entity\Product;
use App\Repository\DiscountRuleRepository;
use App\Domain\Discount\DiscountStrategyInterface;

final class FinalPriceCalculator
{
    /** @var DiscountStrategyInterface[] */
    private array $strategies;

    public function __construct(
        private readonly DiscountRuleRepository $rules,
        iterable $strategies
    ) {
        $this->strategies = is_array($strategies) ? $strategies : iterator_to_array($strategies);
    }

    public function calculate(Product $product): float
    {
        $price = (float) $product->getPriceGross();

        $rule = $this->rules->findOneBy([], ['id' => 'DESC']);
        if (!$rule) {
            return (float) number_format($price, 2, '.', '');
        }

        foreach ($this->strategies as $s) {
            if ($s->supports($rule)) {
                return $s->apply($product, $rule);
            }
        }

        return (float) number_format($price, 2, '.', '');
    }
}
