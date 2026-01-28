<?php

namespace App\Filament\Pages;

use App\Models\WidgetSetting;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?int $navigationSort = 99;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $view = 'filament.pages.settings';

    public ?array $data = [];

    public function mount(): void
    {
        $publishedWidgets = WidgetSetting::where('is_published', true)->pluck('widget_class')->all();
        $this->form->fill(['published_widgets' => $publishedWidgets]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Public Dashboard Widgets')
                    ->description('Select the widgets you want to display on the public-facing dashboard.')
                    ->schema([
                        CheckboxList::make('published_widgets')
                            ->label('Published Widgets')
                            ->options($this->getAvailableWidgets())
                            ->columns(2),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        // Reset all widgets to not published
        WidgetSetting::query()->update(['is_published' => false]);

        // Publish the selected widgets
        foreach ($data['published_widgets'] as $widgetClass) {
            WidgetSetting::updateOrCreate(
                ['widget_class' => $widgetClass],
                ['is_published' => true]
            );
        }

        Notification::make()
            ->title('Settings saved successfully!')
            ->success()
            ->send();
    }

    protected function getAvailableWidgets(): array
    {
        $widgetFiles = File::files(app_path('Filament/Widgets'));
        $widgets = [];

        foreach ($widgetFiles as $file) {
            $className = 'App\\Filament\\Widgets\\'.$file->getFilenameWithoutExtension();
            if (class_exists($className)) {
                $widgets[$className] = Str::headline($file->getFilenameWithoutExtension());
            }
        }

        return $widgets;
    }
}
