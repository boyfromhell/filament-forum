<?php

namespace IchBin\FilamentForum\Filament\Resources;

use Filament\Forms\Form;
use Filament\Tables\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Filament\Tables\Table;
use Guava\FilamentIconPicker\Forms\IconPicker;
use Guava\FilamentIconPicker\Tables\IconColumn;
use IchBin\FilamentForum\Models\Tag;
use IchBin\FilamentForum\Filament\Resources\TagResource\Pages;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class TagResource extends Resource
{
    protected static ?string $model = Tag::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationGroup = 'Forum';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->unique(table: Tag::class, column: 'name', ignorable: function ($livewire) {
                        if ($livewire instanceof EditRecord) {
                            return $livewire->record;
                        } else {
                            return null;
                        }
                    })
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('parent_id')
                    ->relationship('parent','name')
                    ->preload()
                    ->label('Parent'),

                Forms\Components\ColorPicker::make('color')
                    ->label('Color')
                    ->required(),

                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->required()
                    ->columnSpan(2),

                IconPicker::make('icon')
                    ->columns(3)
                    //->label('Icon')
                    //->placeholder('e.g. fa-solid fa-house')
                    //->hint(fn () => new HtmlString('Please refer to <a class="text-blue-500 underline hover:cursor-pointer hover:text-blue-700" href="https://fontawesome.com/search?o=r&m=free" target="_blank">Fontawesome website</a> to choose your icon'))
                    ->required()
                //->columnSpan(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('order')
            ->reorderRecordsTriggerAction(
                fn(Action $action, bool $isReordering) => $action
                    ->button()
                    ->label($isReordering ? 'Disable reordering' : 'Enable reordering'),
            )
            ->columns([
                Tables\Columns\TextColumn::make('parent.name'),
                Tables\Columns\ColorColumn::make('color')
                    ->label('Color'),

                IconColumn::make('icon')
                    ->label('Icon')->default('heroicon-o-tag'),

                Tables\Columns\TextColumn::make('name')
                    ->label('Name'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListTags::route('/'),
            'create' => Pages\CreateTag::route('/create'),
            'view' => Pages\ViewTag::route('/{record}'),
            'edit' => Pages\EditTag::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->orderBy('order','asc');
    }
}
