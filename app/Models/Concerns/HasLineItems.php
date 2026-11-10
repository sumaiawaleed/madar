<?php

namespace App\Models\Concerns;

trait HasLineItems
{
    /**
     * Sum of quantity × price across all line items, before VAT.
     */
    public function subtotal(): float
    {
        return round(collect($this->items ?? [])->sum(
            fn (array $item): float => (float) ($item['quantity'] ?? 0) * (float) ($item['price'] ?? 0)
        ), 2);
    }
}
