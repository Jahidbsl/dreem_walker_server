<?php

namespace App\Filament\Resources\Orders\Schemas;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Customer & Shipping')
                    ->columns(2)
                    ->components([
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('phone')
                            
                            ->maxLength(20)
                            ->required(),
                        Textarea::make('address')
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('city')
                            ->required(),
                        Textarea::make('note')
                            ->columnSpanFull(),
                    ]),

                Section::make('Order Info')
                    ->columns(3)
                    ->components([
                        TextInput::make('payment_method')
                            ->disabled()
                            ->dehydrated(),
                        TextInput::make('total')
                            ->numeric()
                            ->prefix('$')
                            ->disabled()
                            ->dehydrated(),
                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'processing' => 'Processing',
                                'shipped' => 'Shipped',
                                'delivered' => 'Delivered',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required()
                            ->native(false),
                    ]),
            ]);
    }
}