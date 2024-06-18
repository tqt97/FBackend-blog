<?php

namespace App\Filament\Forms\Settings;

use App\Enums\SocialNetwork;
use Filament\Forms\Components\TextInput;

class SocialNetworkFieldsForm
{
    public static function get(): array
    {
        $fields = [];
        foreach (SocialNetwork::options() as $key => $value) {
            $fields[] = TextInput::make($key)
                ->label(ucfirst(strtolower($value)));
        }

        return $fields;
    }
}
