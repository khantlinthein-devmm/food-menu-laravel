<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MenuItemResource\Pages;
use App\Filament\Resources\MenuItemResource\RelationManagers;
use App\Models\MenuItem;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Storage;

class MenuItemResource extends Resource
{
    protected static ?string $model = MenuItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required()->maxLength(255),
                Select::make('category_id')
                ->label('Category')
                ->relationship('category', 'name')
                ->required(),
                Select::make('tab_id')
                ->label('Tab')
                ->relationship('tab', 'name')
                ->required(),
                FileUpload::make('photo')
                ->image()
                ->label('Photo')
                ->disk(config('filament.uploads_disk'))
                ->directory('food-attachments')
                ->visibility('public')
                ->nullable(),
                TextInput::make('price')
                ->numeric(),
                Toggle::make('is_available')->label('Available'),
                TimePicker::make('available_from')->label('Available From'),
                TimePicker::make('available_until')->label('Available until')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                ImageColumn::make('photo')
                ->label('Photo')
                ->getStateUsing(function ($record) {
                    $disk = config('filament.uploads_disk');
                    $path = $record->photo;

                    if ($path && Storage::disk($disk)->exists($path)) {
                        return Storage::disk($disk)->url($path);
                    }

                    return asset('images/default.jpg');
                }),
                TextColumn::make('category.name')->label('Category'),
                TextColumn::make('tab.menu')->label('Tab'),
                TextColumn::make('price')->money('THB'),
                IconColumn::make('is_available')->boolean()->label('Available'),
                TextColumn::make('available_from')->label('From'),
                TextColumn::make('available_until')->label('Until'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMenuItems::route('/'),
            'create' => Pages\CreateMenuItem::route('/create'),
            'edit' => Pages\EditMenuItem::route('/{record}/edit'),
        ];
    }
}
