<?php

namespace App\Filament\Resources\Orders\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    public function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('variant.product.name')
                    ->label('Product'),
                TextColumn::make('variant.size')
                    ->label('Size'),
                TextColumn::make('variant.color')
                    ->label('Color'),
                TextColumn::make('quantity'),
                TextColumn::make('price')
                    ->money('USD'),
            ]);
    }
}