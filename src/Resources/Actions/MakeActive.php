<?php

namespace Eduka\Nova\Resources\Actions;

class MakeActive extends MakeVisible
{
    protected array $data = ['is_active' => true];
}
