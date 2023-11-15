<?php

namespace Eduka\Nova\Resources\Actions;

class MakeInactive extends MakeVisible
{
    protected array $data = ['is_active' => false];
}
