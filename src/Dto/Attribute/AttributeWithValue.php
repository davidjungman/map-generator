<?php

namespace App\Dto\Attribute;

interface AttributeWithValue
{
    public function getValue(): string;
}