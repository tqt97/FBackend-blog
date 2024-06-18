<?php

namespace App\Filament\Pages;

use App\Filament\Forms\Settings\AnalyticsFieldsForm;
use App\Filament\Forms\Settings\ApplicationFieldsForm;
use App\Filament\Forms\Settings\CustomForm;
use App\Filament\Forms\Settings\EmailFieldsForm;
use App\Filament\Forms\Settings\SeoFieldsForm;
use App\Filament\Forms\Settings\SocialNetworkFieldsForm;
use App\Helpers\EmailDataHelper;
use App\Mail\TestMail;
use App\Models\Setting;
use App\Services\MailSettingService;
use Filament\Actions\Action;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SettingPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';

    protected static string $view = 'filament.pages.setting';

    public static function getNavigationGroup(): ?string
    {
        return __('Setting');
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
        return __('Settings');
    }

    public static function getNavigationLabel(): string
    {
        return __('Settings');
    }

    public ?array $data = [];

    public function mount(): void
    {
        $this->data = Setting::first()?->toArray();
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

        if (config('setting.show_application_tab')) {
            $arrTabs[] = Tab::make('Application Tab')
                ->label(__('application'))
                ->icon('heroicon-o-tv')
                ->schema(ApplicationFieldsForm::get())
                ->columns(3);
        }

        if (config('setting.show_analytics_tab')) {
            $arrTabs[] = Tab::make('Analytics Tab')
                ->label(__('analytics'))
                ->icon('heroicon-o-globe-alt')
                ->schema(AnalyticsFieldsForm::get());
        }

        if (config('setting.show_seo_tab')) {
            $arrTabs[] = Tab::make('Seo Tab')
                ->label(__('seo'))
                ->icon('heroicon-o-window')
                ->schema(SeoFieldsForm::get($this->data))
                ->columns(1);
        }

        if (config('setting.show_email_tab')) {
            $arrTabs[] = Tab::make('Email Tab')
                ->label(__('email'))
                ->icon('heroicon-o-envelope')
                ->schema(EmailFieldsForm::get())
                ->columns(3);
        }

        if (config('setting.show_social_networks_tab')) {
            $arrTabs[] = Tab::make('Social Network Tab')
                ->label(__('social_networks'))
                ->icon('heroicon-o-heart')
                ->schema(SocialNetworkFieldsForm::get())
                ->columns(2)
                ->statePath('social_network');
        }

        if (config('setting.show_custom_tabs')) {
            foreach (config('setting.custom_tabs') as $key => $customTab) {
                $arrTabs[] = Tab::make($customTab['label'])
                    ->label(__(''.$customTab['label']))
                    ->icon($customTab['icon'])
                    ->schema(CustomForm::get($customTab['fields']))
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
            Action::make('Save')
                ->label(__('save'))
                ->color('primary')
                ->submit('Update'),
        ];
    }

    public function update(): void
    {
        $data = $this->form->getState();
        $data = EmailDataHelper::setEmailConfigToDatabase($data);

        Setting::updateOrCreate([], $data);
        Cache::forget('general_settings');

        $this->successNotification(__('settings_saved'));
        redirect(request()?->header('Referer'));
    }

    public function sendTestMail(MailSettingService $mailSettingService): void
    {
        $data = $this->form->getState();
        $email = $data['mail_to'];

        $settings = $mailSettingService->loadToConfig($data);

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

        $this->successNotification(__('test_email_success').$email);
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
        Log::error('[EMAIL] '.$body);

        Notification::make()
            ->title($title)
            ->danger()
            ->body($body)
            ->send();
    }
}
