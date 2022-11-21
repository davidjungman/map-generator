<?php

namespace App\Dto\Attribute;

interface AttributeWithValue
{
    public function renderValue(): string;
}