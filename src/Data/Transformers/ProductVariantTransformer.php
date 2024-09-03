<?php namespace Rushing\YouTubeDl\Data\Transformers;

use Rushing\YouTubeDl\Data\ProductVariantData;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Support\Transformation\TransformationContext;
use Spatie\LaravelData\Transformers\Transformer;

class ProductVariantTransformer implements Cast, Transformer
{
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): mixed
    {
        if(data_get($value, 'id')) {
            return ProductVariantData::from($value);
        } elseif(is_array($value)) {
            return array_map(fn($item) => ProductVariantData::from($item), $value);
        }
        return $value;
    }

    public function transform(DataProperty $property, mixed $value, TransformationContext $context): mixed
    {
        if(data_get($value, 'id')) {
            return ProductVariantData::from($value);
        } elseif(is_array($value)) {
            return array_map(fn($item) => ProductVariantData::from($item), $value);
        }
        return $value;
    }
}
