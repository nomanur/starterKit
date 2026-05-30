<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Artisan;

class ManageSettings extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string|\UnitEnum|null $navigationGroup = 'System';

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([
                    EmbeddedSchema::make('form'),
                ])
                    ->id('form')
                    ->livewireSubmitHandler('save')
                    ->footer([
                        Actions::make($this->getFormActions())
                            ->alignment('start')
                            ->key('form-actions'),
                    ]),
            ]);
    }

    public function mount(): void
    {
        $env = $this->readEnv();

        $standardKeys = [
            'APP_NAME',
            'APP_ENV',
            'APP_DEBUG',
            'APP_URL',
            'DB_CONNECTION',
            'LOG_CHANNEL',
            'QUEUE_CONNECTION',
        ];

        $formData = [];
        foreach ($standardKeys as $key) {
            $formData[$key] = $env[$key] ?? '';
        }

        // Cast boolean values for the Toggle component
        $formData['APP_DEBUG'] = filter_var($formData['APP_DEBUG'], FILTER_VALIDATE_BOOLEAN);

        $customVars = [];
        foreach ($env as $key => $value) {
            if (! in_array($key, $standardKeys)) {
                $customVars[] = [
                    'key' => $key,
                    'value' => $value,
                ];
            }
        }

        $formData['custom_vars'] = $customVars;

        $formData['maintenance_mode'] = app()->isDownForMaintenance();
        $formData['maintenance_secret'] = $this->getMaintenanceSecret();

        $this->form->fill($formData);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Application Information')
                    ->description('Primary configurations for your Laravel application instance.')
                    ->aside()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('APP_NAME')
                                    ->label('Application Name')
                                    ->required()
                                    ->placeholder('Laravel'),
                                TextInput::make('APP_URL')
                                    ->label('Application URL')
                                    ->required()
                                    ->url()
                                    ->placeholder('http://localhost'),
                                Select::make('APP_ENV')
                                    ->label('Environment')
                                    ->options([
                                        'local' => 'Local (Development)',
                                        'testing' => 'Testing',
                                        'staging' => 'Staging',
                                        'production' => 'Production',
                                    ])
                                    ->required(),
                                Toggle::make('APP_DEBUG')
                                    ->label('Debug Mode')
                                    ->helperText('Enabling debug mode displays verbose error traces to users.')
                                    ->inline(false),
                            ]),
                    ]),

                Section::make('System & Connections')
                    ->description('Configure primary system drivers, logging, and background queue processors.')
                    ->aside()
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('DB_CONNECTION')
                                    ->label('Database Driver')
                                    ->required()
                                    ->placeholder('sqlite'),
                                TextInput::make('LOG_CHANNEL')
                                    ->label('Log Channel')
                                    ->required()
                                    ->placeholder('stack'),
                                TextInput::make('QUEUE_CONNECTION')
                                    ->label('Queue Driver')
                                    ->required()
                                    ->placeholder('sync'),
                            ]),
                    ]),

                Section::make('Custom Environment Variables')
                    ->description('Add, edit, or remove arbitrary .env keys and values dynamically.')
                    ->extraAttributes([
                        'x-data' => "{ searchQuery: '' }",
                        'x-effect' => "
                            const query = searchQuery.toLowerCase().trim();
                            const items = \$el.querySelectorAll('.fi-fo-repeater-item');
                            items.forEach(item => {
                                const inputs = item.querySelectorAll('input');
                                let match = false;
                                inputs.forEach(input => {
                                    if (input.value.toLowerCase().includes(query)) {
                                        match = true;
                                    }
                                });
                                if (match || query === '') {
                                    item.style.display = '';
                                } else {
                                    item.style.display = 'none';
                                }
                            });
                        ",
                    ])
                    ->schema([
                        TextInput::make('search_vars')
                            ->label('Search Custom Variables')
                            ->placeholder('Type to search keys or values...')
                            ->dehydrated(false)
                            ->extraAttributes([
                                'x-model' => 'searchQuery',
                            ]),
                        Repeater::make('custom_vars')
                            ->label('Additional Keys')
                            ->schema([
                                TextInput::make('key')
                                    ->required()
                                    ->placeholder('STRIPE_KEY')
                                    ->regex('/^[A-Z0-9\_]+$/')
                                    ->validationMessages([
                                        'regex' => 'The key must consist of uppercase letters, numbers, and underscores.',
                                    ]),
                                TextInput::make('value')
                                    ->placeholder('value'),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->reorderable(false)
                            ->addActionLabel('Add Custom Env Variable'),
                    ]),

                Section::make('Maintenance Mode')
                    ->description('Place your application into maintenance mode for updates.')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Toggle::make('maintenance_mode')
                                    ->label('Enable Maintenance Mode')
                                    ->helperText('When enabled, the application will return a 503 Service Unavailable response.')
                                    ->live()
                                    ->inline(false),
                                TextInput::make('maintenance_secret')
                                    ->label('Bypass Secret Key')
                                    ->placeholder('e.g., maintenance-bypass-token')
                                    ->helperText(function (callable $get) {
                                        if (! $get('maintenance_mode')) {
                                            return 'Only required when maintenance mode is active.';
                                        }
                                        $secret = $get('maintenance_secret') ?: 'maintenance-bypass';
                                        $url = url($secret);

                                        return "Visit this URL to bypass maintenance mode: {$url}";
                                    })
                                    ->required(fn (callable $get) => $get('maintenance_mode'))
                                    ->visible(fn (callable $get) => $get('maintenance_mode')),
                            ]),
                    ]),
            ])
            ->statePath('data');
    }

    /**
     * @return array<int, Action>
     */
    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save environment settings')
                ->submit('save')
                ->color('primary'),
        ];
    }

    public function save(): void
    {
        $state = $this->form->getState();

        $variables = [];

        $standardKeys = [
            'APP_NAME',
            'APP_ENV',
            'APP_DEBUG',
            'APP_URL',
            'DB_CONNECTION',
            'LOG_CHANNEL',
            'QUEUE_CONNECTION',
        ];

        foreach ($standardKeys as $key) {
            if ($key === 'APP_DEBUG') {
                $variables[$key] = $state[$key] ? 'true' : 'false';
            } else {
                $variables[$key] = $state[$key] ?? '';
            }
        }

        if (isset($state['custom_vars']) && is_array($state['custom_vars'])) {
            foreach ($state['custom_vars'] as $row) {
                $key = strtoupper(trim((string) ($row['key'] ?? '')));
                if (! empty($key)) {
                    $variables[$key] = (string) ($row['value'] ?? '');
                }
            }
        }

        $this->saveEnv($variables);

        // Handle Maintenance Mode
        $maintenanceMode = $state['maintenance_mode'] ?? false;
        $isDown = app()->isDownForMaintenance();

        if ($maintenanceMode && ! $isDown) {
            $secret = trim((string) ($state['maintenance_secret'] ?? ''));
            if (empty($secret)) {
                $secret = 'maintenance-bypass';
            }
            Artisan::call('down', ['--secret' => $secret]);
        } elseif (! $maintenanceMode && $isDown) {
            Artisan::call('up');
        } elseif ($maintenanceMode && $isDown) {
            $currentSecret = $this->getMaintenanceSecret();
            $newSecret = trim((string) ($state['maintenance_secret'] ?? ''));
            if (! empty($newSecret) && $newSecret !== $currentSecret) {
                Artisan::call('down', ['--secret' => $newSecret]);
            }
        }

        // Clear configurations so changes take effect
        Artisan::call('config:clear');

        Notification::make()
            ->title('Environment settings updated successfully!')
            ->success()
            ->send();
    }

    /**
     * @return array<string, string>
     */
    private function readEnv(): array
    {
        $path = base_path('.env');
        if (! file_exists($path)) {
            return [];
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES);
        $variables = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line) || str_starts_with($line, '#')) {
                continue;
            }

            if (str_contains($line, '=')) {
                [$key, $value] = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);

                // Strip surrounding quotes
                if (str_starts_with($value, '"') && str_ends_with($value, '"')) {
                    $value = substr($value, 1, -1);
                } elseif (str_starts_with($value, "'") && str_ends_with($value, "'")) {
                    $value = substr($value, 1, -1);
                }

                $variables[$key] = $value;
            }
        }

        return $variables;
    }

    /**
     * @param  array<string, string>  $variables
     */
    private function saveEnv(array $variables): void
    {
        $path = base_path('.env');
        if (! file_exists($path)) {
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES);
        $newLines = [];
        $processedKeys = [];

        foreach ($lines as $line) {
            $trimmedLine = trim($line);
            if (empty($trimmedLine) || str_starts_with($trimmedLine, '#')) {
                $newLines[] = $line;

                continue;
            }

            if (str_contains($trimmedLine, '=')) {
                [$key] = explode('=', $trimmedLine, 2);
                $key = trim($key);

                if (array_key_exists($key, $variables)) {
                    $value = $variables[$key];
                    if (preg_match('/\s/', $value) || str_contains($value, '#') || str_contains($value, '$')) {
                        $formattedValue = '"'.str_replace('"', '\\"', $value).'"';
                    } else {
                        $formattedValue = $value;
                    }
                    $newLines[] = "{$key}={$formattedValue}";
                    $processedKeys[$key] = true;
                } else {
                    // Skip deleted variables
                    continue;
                }
            } else {
                $newLines[] = $line;
            }
        }

        // Append new custom variables
        foreach ($variables as $key => $value) {
            if (! isset($processedKeys[$key])) {
                if (preg_match('/\s/', $value) || str_contains($value, '#') || str_contains($value, '$')) {
                    $formattedValue = '"'.str_replace('"', '\\"', $value).'"';
                } else {
                    $formattedValue = $value;
                }
                $newLines[] = "{$key}={$formattedValue}";
            }
        }

        file_put_contents($path, implode("\n", $newLines)."\n");
    }

    private function getMaintenanceSecret(): string
    {
        $path = storage_path('framework/down');
        if (file_exists($path)) {
            $data = json_decode((string) file_get_contents($path), true);

            return is_array($data) ? ($data['secret'] ?? '') : '';
        }

        return '';
    }
}
