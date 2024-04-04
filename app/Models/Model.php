<?php

namespace App\Models;


class Model
{
    public function fill(array $data)
    {
        $attributes = static::getAttributes();

        foreach ($attributes as $key => $value) {
            $this->setAttribute($key, $data[$key]);
        }

        return $this;
    }

    public function toArray() : array
    {
        $attributes = static::getAttributes();

        $array = [];

        foreach ($attributes as $key => $value) {
            $array[$key] = $this->{$key};
        }

        return $array;
    }
    public static function create(array $data)
    {
        return (new static())->fill($data);
    }

    protected function setAttribute(string $attributeName, mixed $value): void
    {
        if($value === null){
            return;
        }
        $this->{$attributeName} = $value ?? $this->{$attributeName};
    }

    protected static function getAttributes() {
        return get_class_vars(static::class);
    }
}