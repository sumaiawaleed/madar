<?php

namespace App\Http\Requests\Concerns;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Exists;

trait ValidatesWorkspaceRecords
{
    /**
     * Only allow linking to a record that belongs to the signed-in freelancer.
     */
    protected function ownedBy(string $table): Exists
    {
        return Rule::exists($table, 'id')->where('user_id', $this->user()->id);
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    protected function lineItemRules(): array
    {
        return [
            'items' => ['required', 'array', 'min:1', 'max:50'],
            'items.*.description' => ['required', 'string', 'max:255'],
            'items.*.quantity' => ['required', 'numeric', 'min:0', 'max:100000'],
            'items.*.price' => ['required', 'numeric', 'min:0', 'max:100000000'],
        ];
    }

    /**
     * Drop rows the freelancer left completely empty before validating.
     */
    protected function prepareLineItems(): void
    {
        $items = collect($this->input('items', []))
            ->filter(fn (mixed $item): bool => is_array($item) && (filled($item['description'] ?? null) || (float) ($item['price'] ?? 0) > 0))
            ->values()
            ->all();

        $this->merge(['items' => $items]);
    }

    /**
     * Normalise validated line items to typed values for storage.
     *
     * @param  array<int, array{description: string, quantity: numeric-string|float, price: numeric-string|float}>  $items
     * @return array<int, array{description: string, quantity: float, price: float}>
     */
    public static function castLineItems(array $items): array
    {
        return array_map(fn (array $item): array => [
            'description' => $item['description'],
            'quantity' => (float) $item['quantity'],
            'price' => (float) $item['price'],
        ], $items);
    }
}
