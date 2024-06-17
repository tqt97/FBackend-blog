<?php

// namespace App\Filament\Resources;

// use Filament\Forms;
// use Filament\Tables;
// use App\Models\Setting;
// use Filament\Forms\Form;
// use Filament\Tables\Table;
// use Filament\Resources\Resource;
// use Illuminate\Database\Eloquent\Builder;
// use App\Filament\Resources\SettingResource\Pages;
// use Illuminate\Database\Eloquent\SoftDeletingScope;
// use App\Filament\Resources\SettingResource\RelationManagers;
// use Schmeits\FilamentCharacterCounter\Forms\Components\Textarea;
// use Schmeits\FilamentCharacterCounter\Forms\Components\TextInput;

// class SettingResource extends Resource
// {
//     protected static ?string $model = Setting::class;

//     protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

//     protected static ?string $navigationGroup = 'Setting';

//     protected static ?int $navigationSort = 8;


//     public static function form(Form $form): Form
//     {
//         return $form
//             ->schema([
//                 Forms\Components\Section::make()
//                     ->schema([
//                         Forms\Components\Section::make('General Information')
//                             ->schema([
//                                 TextInput::make('title')
//                                     ->maxLength(155)
//                                     ->required(),
//                                 TextInput::make('organization_name')
//                                     ->required()
//                                     ->maxLength(155)
//                                     ->minLength(3),
//                                 Textarea::make('description')
//                                     ->required()
//                                     ->minLength(10)
//                                     ->maxLength(1000)
//                                     ->columnSpanFull(),
//                                 Forms\Components\FileUpload::make('logo')
//                                     ->hint('Max height 400')
//                                     ->directory('setting/logo')
//                                     ->maxSize(1024 * 1024 * 2)
//                                     ->rules('dimensions:max_height=400')
//                                     ->nullable()->columnSpanFull(),
//                                 Forms\Components\FileUpload::make('favicon')
//                                     ->directory('setting/favicon')
//                                     ->maxSize(50)
//                                     ->nullable()->columnSpanFull()
//                             ])->columns(2),

//                         Forms\Components\Section::make('SEO')
//                             ->description('Place your google analytic and adsense code here. This will be added to the head tag of your blog post only.')
//                             ->schema([
//                                 Textarea::make('google_console_code')
//                                     ->startsWith('<meta')
//                                     ->nullable()
//                                     ->columnSpanFull(),
//                                 Textarea::make('google_analytic_code')
//                                     ->startsWith('<script')
//                                     ->endsWith('</script>')
//                                     ->nullable()
//                                     ->columnSpanFull(),
//                                 Textarea::make('google_adsense_code')
//                                     ->startsWith('<script')
//                                     ->endsWith('</script>')
//                                     ->nullable()
//                                     ->columnSpanFull(),
//                             ])->columns(2),
//                         Forms\Components\Section::make('Quick Links')
//                             ->description('Add your quick links here. This will be displayed in the footer of your blog.')
//                             ->schema([
//                                 Forms\Components\Repeater::make('quick_links')
//                                     ->label('Links')
//                                     ->schema([
//                                         TextInput::make('label')
//                                             ->required()
//                                             ->maxLength(155),
//                                         TextInput::make('url')
//                                             ->label('URL')
//                                             ->helperText('URL should start with http:// or https://')
//                                             ->required()
//                                             ->url()
//                                             ->maxLength(255),
//                                     ])->columns(2),
//                             ])->columnSpanFull(),
//                     ]),
//             ]);
//     }

//     public static function table(Table $table): Table
//     {
//         return $table
//             ->columns([
//                 Tables\Columns\TextColumn::make('title')
//                     ->searchable(),
//                 Tables\Columns\TextColumn::make('logo')
//                     ->searchable(),
//                 Tables\Columns\TextColumn::make('favicon')
//                     ->searchable(),
//                 Tables\Columns\TextColumn::make('organization_name')
//                     ->searchable(),
//                 Tables\Columns\TextColumn::make('created_at')
//                     ->dateTime()
//                     ->sortable()
//                     ->toggleable(isToggledHiddenByDefault: true),
//                 Tables\Columns\TextColumn::make('updated_at')
//                     ->dateTime()
//                     ->sortable()
//                     ->toggleable(isToggledHiddenByDefault: true),
//             ])
//             ->filters([
//                 //
//             ])
//             ->actions([
//                 Tables\Actions\EditAction::make(),
//             ])
//             ->bulkActions([
//                 Tables\Actions\BulkActionGroup::make([
//                     Tables\Actions\DeleteBulkAction::make(),
//                 ]),
//             ]);
//     }

//     public static function getRelations(): array
//     {
//         return [
//             //
//         ];
//     }

//     public static function getPages(): array
//     {
//         return [
//             'index' => Pages\ListSettings::route('/'),
//             'create' => Pages\CreateSetting::route('/create'),
//             'edit' => Pages\EditSetting::route('/{record}/edit'),
//         ];
//     }
// }
