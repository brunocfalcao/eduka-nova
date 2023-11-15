<?php

namespace Eduka\Nova\Resources\Actions;

class MakeInvisible extends MakeVisible
{
    protected array $data = ['is_visible' => false];
}
