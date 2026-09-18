<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

/**
 * Trait Filterable
 * 
 * Provides common query scopes for filtering and searching.
 * Extracted to prevent repeating basic WHERE logic across every single controller.
 */
trait Filterable
{
    /**
     * Scope a query to filter by active/inactive status.
     *
     * @param Builder $query
     * @param string|null $status
     * @return Builder
     */
    public function scopeFilterByStatus(Builder $query, ?string $status): Builder
    {
        if (empty($status)) {
            return $query;
        }

        // We explicitly check the exact string here instead of trusting user input directly,
        // although validation usually catches it first.
        return $query->where('status', $status);
    }

    /**
     * Scope a query to dynamically search across provided columns.
     *
     * @param Builder $query
     * @param string|null $searchTerm
     * @param array $columns
     * @return Builder
     */
    public function scopeSearchAcross(Builder $query, ?string $searchTerm, array $columns): Builder
    {
        if (empty($searchTerm) || empty($columns)) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($searchTerm, $columns) {
            foreach ($columns as $index => $column) {
                if ($index === 0) {
                    $q->where($column, 'like', "%{$searchTerm}%");
                } else {
                    $q->orWhere($column, 'like', "%{$searchTerm}%");
                }
            }
        });
    }
}
