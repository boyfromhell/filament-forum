<?php

namespace IchBin\FilamentForum\Livewire;

use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification as Not;
use IchBin\FilamentForum\Models\Notification;
use IchBin\FilamentForum\Models\UserNotification;
use Livewire\Component;

class Notifications extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public $notification_1_web;

    public $notification_1_email;

    public $notification_2_web;

    public $notification_2_email;

    public $notification_3_web;

    public $notification_3_email;

    public $notification_4_web;

    public $notification_4_email;

    public $notification_5_web;

    public $notification_5_email;

    public $notification_6_web;

    public $notification_6_email;

    public $notification_7_web;

    public $notification_7_email;

    public $notification_8_web;

    public $notification_8_email;

    public $notification_9_web;

    public $notification_9_email;

    public function mount(): void
    {
        $this->initForm();
    }

    public function render()
    {
        return view('filament-forum::livewire.notifications');
    }

    public function getFormSchema(): array
    {
        //        $fields = [];
        //        $notifications = Notification::all();
        //        foreach ($notifications as $notification) {
        //            $fields[] = Grid::make()
        //                ->columns(4)
        //                ->schema([
        //                    Placeholder::make($notification->name)
        //                        ->columnSpan(2),
        //
        //                    Toggle::make('notification_' . $notification->id . '_web')
        //                        ->label('Web'),
        //
        //                    Toggle::make('notification_' . $notification->id . '_email')
        //                        ->label('Email'),
        //                ]);
        //        }
        //        return $form
        //            ->schema([
        //
        //                ])->statePath('data');

        $fields = [];
        $notifications = Notification::all();
        foreach ($notifications as $notification) {
            $fields[] = Grid::make()
                ->columns(4)
                ->schema([
                    Placeholder::make($notification->name)
                        ->columnSpan(2),

                    Toggle::make('notification_' . $notification->id . '_web')
                        ->label('Web'),

                    Toggle::make('notification_' . $notification->id . '_email')
                        ->label('Email'),
                ]);
        }

        return $fields;
    }

    public function perform(): void
    {
        $data = $this->form->getState();
        //dd($data);
        $user = auth()->user();
        foreach ($data as $key => $value) {
            $field = explode('_', $key);
            //dd($field);
            $id = $field[1];
            $column = 'via_' . $field[2];
            $notification = UserNotification::where('user_id', $user->id)
                ->where('notification_id', $id)
                ->first();
            if (! $notification) {
                $notification = new UserNotification();
                $notification->user_id = $user->id;
                $notification->notification_id = $id;
            }
            $notification->{$column} = $value;
            $notification->save();
        }
        $this->initForm();
        Not::make()->success()->title('Your notifications were successfully updated.')->send();
    }

    private function initForm(): void
    {
        //dd(auth()->user()->appNotifications);
        $data = [];
        foreach (auth()->user()->appNotifications as $notification) {
            $data['notification_' . $notification->id . '_web'] = $notification->pivot->via_web;
            $data['notification_' . $notification->id . '_email'] = $notification->pivot->via_email;
        }
        $this->form->fill($data);
    }
}
