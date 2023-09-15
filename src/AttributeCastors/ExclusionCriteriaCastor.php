<?php

namespace OnrampLab\Webhooks\AttributeCastors;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use OnrampLab\Webhooks\ValueObjects\ExclusionCriterion;

class ExclusionCriteriaCastor implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        if (is_null($value)) {
            return null;
        }
        // Convert the JSON string to an array of ExclusionCriterion objects
        $exclusionCriteria = json_decode($value, true);
        $casted = [];

        foreach ($exclusionCriteria as $criterion) {
            $casted[] = new ExclusionCriterion([
                'name' => $criterion['name'],
                'values' => $criterion['values']
            ]);
        }

        return $casted;
    }

    public function set($model, string $key, $value, array $attributes)
    {
        if (is_null($value)) {
            return null;
        }
        // Convert the array of ExclusionCriterion objects back to a JSON string
        $formatted = [];

        foreach ($value as $criterion) {
            $formatted[] = [
                'name' => $criterion->getName(),
                'values' => $criterion->getValues(),
            ];
        }

        return json_encode($formatted);
    }
}
