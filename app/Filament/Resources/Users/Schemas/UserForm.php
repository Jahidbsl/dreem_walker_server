<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->disabled()
                    ->maxLength(255),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->disabled()
                    ->maxLength(255),
                
                TextInput::make('password')
                    ->password()
                    ->disabled()
                    ->maxLength(255),
                Toggle::make('is_active')
                   ->label('Active / Unblocked')
                   ->required(),
            ]);
    }
}
