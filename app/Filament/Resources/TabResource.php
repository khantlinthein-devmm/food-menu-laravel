<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TabResource\Pages;
use App\Filament\Resources\TabResource\RelationManagers;
use App\Models\Tab;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TabResource extends Resource
{
    protected static ?string $model = Tab::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->label('Tab Name')->required()->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                ->label('Tab Name')
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('menu_items_count')
                ->label('Items')
                ->counts('menuItems')
                ->sortable(),
            Tables\Columns\TextColumn::make('created_at')
                ->label('Created')
                ->dateTime()
                ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withCount('menuItems');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTabs::route('/'),
            'create' => Pages\CreateTab::route('/create'),
            'edit' => Pages\EditTab::route('/{record}/edit'),
        ];
    }
}
