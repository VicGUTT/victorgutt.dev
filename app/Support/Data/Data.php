<?php

declare(strict_types=1);

namespace App\Support\Data;

use ReflectionClass;
use ReflectionProperty;
use ReflectionNamedType;
use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, mixed>
 */
abstract readonly class Data implements Arrayable
{
    public static function from(array $attributes): static
    {
        // @phpstan-ignore-next-line new.static
        return new static(...static::attributesToProperties($attributes));
    }

    protected static function attributesToProperties(array $attributes): array
    {
        $class = new ReflectionClass(static::class);
        $properties = $class->getProperties(ReflectionProperty::IS_PUBLIC);

        return array_reduce($properties, static function (array $acc, ReflectionProperty $property) use ($attributes): array {
            $key = $property->name;
            $value = $attributes[$key];

            $type = $property->getType();

            if ($value && $type instanceof ReflectionNamedType && !$type->isBuiltin()) {
                $typeName = $type->getName();
                $value = is_subclass_of($typeName, self::class)
                    ? $typeName::from($value)
                    : $value;
            }

            $acc[$key] = $value;

            return $acc;
        }, []);
    }

    /**
     * Get the instance as an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->propertiesToArray();
    }

    protected function propertiesToArray(): array
    {
        return collect((array) $this)->toArray();
    }
}
