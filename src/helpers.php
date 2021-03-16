<?php

use Illuminate\Support\Carbon;

function this_week_days()
{
    return now()->diffInDays(Carbon::create('previous monday')) + 1;
}
