<?php

namespace App\Filament\Forms\Settings;

use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use App\Enums\EmailProvider;

class EmailFieldsForm
{
    public static function get(): array
    {
        return [
            Forms\Components\Grid::make()
                ->schema([
                    Section::make([
                        Select::make('default_email_provider')
                            ->label(__('default_email_provider'))
                            ->native(false)
                            ->allowHtml()
                            ->preload()
                            ->options(function () {
                                $options = [];
                                foreach (EmailProvider::options() as $key => $value) {
                                    if (file_exists(public_path('backend/general-settings/images/email-providers/' . strtolower($value) . '.svg'))) {
                                        $options[strtolower($value)] = '<div class="flex gap-2">' .
                                            ' <img src="' . asset('backend/general-settings/images/email-providers/' . strtolower($value) . '.svg') . '"  class="h-5">'
                                            . $value
                                            . '</div>';
                                    } else {
                                        $options[strtolower($value)] = $value;
                                    }
                                }

                                return $options;
                            })
                            ->helperText(__('default_email_provider_helper_text'))
                            ->live()
                            ->columnSpanFull(),
                        Forms\Components\Group::make()
                            ->schema([
                                TextInput::make('smtp_host')
                                    ->label(__('host')),
                                TextInput::make('smtp_port')
                                    ->label(__('port')),
                                Select::make('smtp_encryption')
                                    ->label(__('encryption'))
                                    ->options([
                                        'ssl' => 'SSL',
                                        'tls' => 'TLS',
                                    ]),
                                TextInput::make('smtp_timeout')
                                    ->label(__('timeout')),
                                TextInput::make('smtp_username')
                                    ->label(__('username')),
                                TextInput::make('smtp_password')
                                    ->label(__('password')),
                            ])
                            ->columns(2)
                            ->visible(fn ($state) => $state['default_email_provider'] === 'smtp'),
                        Forms\Components\Group::make()
                            ->schema([
                                TextInput::make('mailgun_domain')
                                    ->label(__('mailgun_domain')),
                                TextInput::make('mailgun_secret')
                                    ->label(__('mailgun_secret')),
                                TextInput::make('mailgun_endpoint')
                                    ->label(__('mailgun_endpoint')),
                            ])
                            ->columns(1)
                            ->visible(fn ($state) => $state['default_email_provider'] === 'mailgun'),
                        Forms\Components\Group::make()
                            ->schema([
                                TextInput::make('postmark_token')
                                    ->label(__('postmark_token')),
                            ])
                            ->columns(1)
                            ->visible(fn ($state) => $state['default_email_provider'] === 'postmark'),
                        Forms\Components\Group::make()
                            ->schema([
                                TextInput::make('amazon_ses_key')
                                    ->label(__('amazon_ses_key')),
                                TextInput::make('amazon_ses_secret')
                                    ->label(__('amazon_ses_secret')),
                                TextInput::make('amazon_ses_region')
                                    ->label(__('amazon_ses_region'))
                                    ->default('us-east-1'),
                            ])
                            ->columns(1)
                            ->visible(fn ($state) => $state['default_email_provider'] === 'ses'),
                    ]),
                ])
                ->columnSpan(['lg' => 2]),
            Forms\Components\Grid::make()
                ->schema([
                    Section::make([
                        TextInput::make('email_from_name')
                            ->label(__('email_from_name'))
                            ->helperText(__('email_from_name_helper_text')),
                        TextInput::make('email_from_address')
                            ->label(__('email_from_address'))
                            ->helperText(__('email_from_address_helper_text'))
                            ->email(),
                    ]),
                    Section::make()
                        ->schema([
                            TextInput::make('mail_to')
                                ->label(fn () => __('mail_to'))
                                ->hiddenLabel()
                                ->placeholder(fn () => __('mail_to'))
                                ->reactive(),
                            Forms\Components\Actions::make([
                                Forms\Components\Actions\Action::make('Send Test Mail')
                                    ->label(fn () => __('send_test_email'))
                                    ->disabled(fn ($state) => empty($state['mail_to']))
                                    ->action('sendTestMail')
                                    ->color('warning')
                                    ->icon('heroicon-o-paper-airplane'),
                            ])->fullWidth(),
                        ]),
                ])
                ->columnSpan(['lg' => 1]),
        ];
    }
}
