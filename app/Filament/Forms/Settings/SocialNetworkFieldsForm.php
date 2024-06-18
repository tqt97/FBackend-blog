<?php

namespace App\Filament\Forms\Settings;

use Filament\Forms\Components\TextInput;
use App\Enums\SocialNetwork;

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
