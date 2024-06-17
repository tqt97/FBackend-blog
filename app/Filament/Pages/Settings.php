<?php

namespace App\Filament\Pages;

use Filament\Actions;
use App\Mail\TestMail;
use Filament\Forms\Form;
use Filament\Pages\Page;
use App\Forms\SeoFieldsForm;
use App\Forms\EmailFieldsForm;
use App\Models\GeneralSetting;
use App\Helpers\EmailDataHelper;
use App\Forms\AnalyticsFieldsForm;
use Filament\Forms\Components\Tabs;
use Illuminate\Support\Facades\Log;
use App\Forms\ApplicationFieldsForm;
use Illuminate\Support\Facades\Mail;
use App\Services\MailSettingsService;
use Illuminate\Support\Facades\Cache;
use App\Forms\SocialNetworkFieldsForm;
use Filament\Notifications\Notification;
use Joaopaulolndev\FilamentGeneralSettings\Forms\CustomForms;

class Settings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.settings';

    public static function getNavigationGroup(): ?string
    {
        return __('admin/navigation.groups.settings');
    }
    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-adjustments-horizontal';
    }
    public static function getNavigationSort(): ?int
    {
        return 100;
    }
    public function getTitle(): string
    {
        return __('admin/navigation.settings');
    }
    public static function getNavigationLabel(): string
    {
        return __('admin/navigation.settings');
    }
    public ?array $data = [];

    public function mount(): void
    {
        $this->data = GeneralSetting::first()?->toArray();
        $this->data = $this->data ?: [];
        $this->data['seo_description'] = $this->data['seo_description'] ?? '';
        $this->data['seo_preview'] = $this->data['seo_preview'] ?? '';
        $this->data['theme_color'] = $this->data['theme_color'] ?? '';
        $this->data['seo_metadata'] = $this->data['seo_metadata'] ?? [];
        $this->data = EmailDataHelper::getEmailConfigFromDatabase($this->data);
    }

    public function form(Form $form): Form
    {
        $arrTabs = [];

        if (config('general-settings.show_application_tab')) {
            $arrTabs[] = Tabs\Tab::make('Application Tab')
                ->label(__('admin/setting.application'))
                ->icon('heroicon-o-tv')
                ->schema(ApplicationFieldsForm::get())
                ->columns(3);
        }

        if (config('general-settings.show_analytics_tab')) {
            $arrTabs[] = Tabs\Tab::make('Analytics Tab')
                ->label(__('admin/setting.analytics'))
                ->icon('heroicon-o-globe-alt')
                ->schema(AnalyticsFieldsForm::get());
        }

        if (config('general-settings.show_seo_tab')) {
            $arrTabs[] = Tabs\Tab::make('Seo Tab')
                ->label(__('admin/setting.seo'))
                ->icon('heroicon-o-window')
                ->schema(SeoFieldsForm::get($this->data))
                ->columns(1);
        }

        if (config('general-settings.show_email_tab')) {
            $arrTabs[] = Tabs\Tab::make('Email Tab')
                ->label(__('admin/setting.email'))
                ->icon('heroicon-o-envelope')
                ->schema(EmailFieldsForm::get())
                ->columns(3);
        }

        if (config('general-settings.show_social_networks_tab')) {
            $arrTabs[] = Tabs\Tab::make('Social Network Tab')
                ->label(__('admin/setting.social_networks'))
                ->icon('heroicon-o-heart')
                ->schema(SocialNetworkFieldsForm::get())
                ->columns(2)
                ->statePath('social_network');
        }

        if (config('general-settings.show_custom_tabs')) {
            foreach (config('general-settings.custom_tabs') as $key => $customTab) {
                $arrTabs[] = Tabs\Tab::make($customTab['label'])
                    ->label(__('admin/setting.'.$customTab['label']))
                    ->icon($customTab['icon'])
                    ->schema(CustomForms::get($customTab['fields']))
                    ->columns($customTab['columns'])
                    ->statePath('more_configs');
            }
        }

        return $form
            ->schema([
                Tabs::make('Tabs')
                    ->tabs($arrTabs),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Actions\Action::make('Save')
                ->label(__('admin/setting.save'))
                ->color('primary')
                ->submit('Update'),
        ];
    }

    public function update(): void
    {
        $data = $this->form->getState();
        $data = EmailDataHelper::setEmailConfigToDatabase($data);

        GeneralSetting::updateOrCreate([], $data);
        Cache::forget('general_settings');

        $this->successNotification(__('admin/setting.settings_saved'));
        redirect(request()?->header('Referer'));
    }

    public function sendTestMail(MailSettingsService $mailSettingsService): void
    {
        $data = $this->form->getState();
        $email = $data['mail_to'];

        $settings = $mailSettingsService->loadToConfig($data);

        try {
            Mail::mailer($settings['76default_email_provider'])
                ->to($email)
                ->send(new TestMail([
                    'subject' => 'This is a test email to verify SMTP settings',
                    'body' => 'This is for testing email using smtp.',
                ]));
        } catch (\Exception $e) {
            $this->errorNotification(__('test_email_error'), $e->getMessage());

            return;
        }

        $this->successNotification(__('admin/setting.test_email_success') . $email);
    }

    private function successNotification(string $title): void
    {
        Notification::make()
            ->title($title)
            ->success()
            ->send();
    }

    private function errorNotification(string $title, string $body): void
    {
        Log::error('[EMAIL] ' . $body);

        Notification::make()
            ->title($title)
            ->danger()
            ->body($body)
            ->send();
    }
}
