<?php

namespace App\Filament\Forms\Settings;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use App\Enums\TypeField;

class CustomForm
{
    public static function get(array $customFields): array
    {
        $fields = [];
        foreach ($customFields as $fieldKey => $field) {

            if ($field['type'] === TypeField::Text->value) {

                $fields[] = TextInput::make($fieldKey)
                    ->label(__($field['label']))
                    ->placeholder(__($field['placeholder']))
                    ->required($field['required'])
                    ->rules($field['rules']);

            } elseif ($field['type'] === TypeField::Boolean->value) {

                $fields[] = Checkbox::make($fieldKey)
                    ->label(__($field['label']));

            } elseif ($field['type'] === TypeField::Select->value) {

                $fields[] = Select::make($fieldKey)
                    ->label(__($field['label']))
                    ->placeholder(__($field['placeholder']))
                    ->options($field['options'])
                    ->required($field['required']);

            } elseif ($field['type'] === TypeField::Textarea->value) {

                $fields[] = Textarea::make($fieldKey)
                    ->label(__($field['label']))
                    ->placeholder(__($field['placeholder']))
                    ->rows($field['rows'])
                    ->required($field['required']);
            } elseif ($field['type'] === TypeField::Datetime->value) {

                $fields[] = DateTimePicker::make($fieldKey)
                    ->label(__($field['label']))
                    ->placeholder(__($field['placeholder']))
                    ->seconds($field['seconds']);
            }
        }

        return $fields;
    }
}
