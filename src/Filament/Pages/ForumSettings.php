<?php

namespace IchBin\FilamentForum\Filament\Pages;

use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use IchBin\FilamentForum\Models\Configuration;

class ForumSettings extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public ?array $data = [];

    public function mount(Configuration $configuration): void
    {
        $this->form->fill($configuration->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()
                    ->columns(1)
                    ->schema([
                        TextInput::make('name')
                            ->label('Configuration name')
                            ->required()
                            ->maxLength(255),

                        Toggle::make('is_enabled')
                            ->label('Enabled?')
                            ->required(),
                    ]),
            ])->statePath('data');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Configuration::query())
            ->columns([
                TextColumn::make('name')
                    ->label('Configuration name'),
                ToggleColumn::make('is_enabled')
                    ->label('Configuration enabled?'),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->label('Last update'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Add new')
                    ->createAnother(false)
                    ->model(Configuration::class)
                    ->form([
                        TextInput::make('name')
                            ->label('Configuration name')
                            ->required()
                            ->maxLength(255),

                        Toggle::make('is_enabled')
                            ->label('Enabled?')
                            ->required(),
                    ]),
            ])
            ->actions([
                DeleteAction::make(),
            ]);
    }

    public function submit()
    {
        $this->form->getState();

        Configuration::create($this->form->getState());

        Notification::make()
            ->title('Saved')
            ->toDatabase();

        $this->form->fill();
    }

    public function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
        ];
    }

    public function getSaveFormAction(): Action
    {
        return Action::make('save')
            ->label(__('Save'))
            ->submit('save')
            ->keyBindings(['mod+s']);
    }

    public function getSubmitFormAction(): Action
    {
        return $this->getSaveFormAction();
    }

    protected static ?string $navigationIcon = 'heroicon-o-cog';

    public static function getNavigationLabel(): string
    {
        return __('Settings');
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Forum';
    }

    public static ?int $navigationSort = 5;

    protected static string $view = 'filament-forum::pages.forum-settings';
}
