<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Models\Category;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Forms\Set;
use Illuminate\Support\Str;
use Filament\Schemas\Components\Utilities\Get;


class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
              TextInput::make('name')
                ->required()
                ->maxLength(255)
                ->live(onBlur: true)
                ->afterStateUpdated(function (Get $get, $state, Set $set) {
                   if ($get('slug') === null || $get('slug') === '') {
                   $set('slug', Str::slug($state));
        }
    }),

                TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(Category::class, 'slug', ignoreRecord: true),

                Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }
}