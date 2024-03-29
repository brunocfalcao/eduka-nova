<?php

namespace Eduka\Nova\Resources;

use Brunocfalcao\LaravelNovaHelpers\Fields\Canonical;
use Eduka\Nova\Abstracts\EdukaResource;
use Eduka\Nova\Resources\Fields\EdBelongsTo;
use Eduka\Nova\Resources\Fields\EdBelongsToMany;
use Eduka\Nova\Resources\Fields\EdHasMany;
use Eduka\Nova\Resources\Fields\EdID;
use Eduka\Nova\Resources\Fields\EdUUID;
use Eduka\Nova\Resources\Filters\ByCourse;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class Variant extends EdukaResource
{
    public static $model = \Eduka\Cube\Models\Variant::class;

    public static $search = ['canonical', 'description'];

    public function title()
    {
        return $this->name.' ('.$this->course->name.')';
    }

    public function fields(NovaRequest $request)
    {
        return [
            // Confirmed.
            EdID::make(),

            Text::make('Name')
                ->helpInfo('The variant name, normally the same as the Lemon Squeezy one')
                ->rules($this->model()->rule('name')),

            // Confirmed.
            EdUUID::make('UUID'),

            // Confirmed.
            Canonical::make()
                ->onlyOnDetail(),

            // Confirmed.
            Text::make('Description', 'description')
                ->helpInfo('An elaborated description, in case we have similiar variants')
                ->rules($this->model()->rule('description')),

            // Confirmed.
            Boolean::make('Default', 'is_default')
                ->helpInfo('In case of several variants for the same course, you can force this one to be the default one'),

            // Confirmed.
            Text::make('Variant ID (LS)', 'lemon_squeezy_variant_id')
                ->rules($this->model()->rule('lemon_squeezy_variant_id'))
                ->helpInfo('This is the Lemon Squeezy Variant ID and not any other variant code. Please check your Lemon Squeezy Store information')
                ->hideFromIndex(),

            // Confirmed.
            Currency::make('Price override (LS)', 'lemon_squeezy_price_override')
                ->rules($this->model()->rule('lemon_squeezy_price_override'))
                ->canSee(function ($request) {
                    return ! via_resource();
                })
                ->helpInfo('In case you want to override the price that you have configured for this variant id in Lemon Squeezy'),

            // Confirmed.
            KeyValue::make('Data (LS)', 'lemon_squeezy_data')
                ->readonly(),

            // Confirmed.
            Panel::make('Timestamps', $this->timestamps($request)),

            // Confirmed.
            EdBelongsTo::make('Course', 'course', Course::class),

            // Confirmed.
            EdBelongsToMany::make('Students', 'students', Student::class),

            // Confirmed.
            EdBelongsToMany::make('Chapters', 'chapters', Chapter::class),

            // Confirmed.
            EdHasMany::make('Orders', 'orders', Order::class),
        ];
    }

    public function filters(Request $request)
    {
        return [
            new ByCourse(),
        ];
    }
}
