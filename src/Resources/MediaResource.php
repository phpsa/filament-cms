<?php

namespace Phpsa\FilamentCms\Resources;

use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Phpsa\FilamentCms\Models\CmsMedia;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\Placeholder;
use Phpsa\FilamentCms\Components\Fields\MediaUpload;
use Phpsa\FilamentCms\Components\DateTimePlaceholder;
use Phpsa\FilamentCms\Resources\MediaResource\EditMedia;
use Phpsa\FilamentCms\Resources\MediaResource\ListMedia;
use Phpsa\FilamentCms\Resources\MediaResource\CreateMedia;
use Johncarter\FilamentFocalPointPicker\Fields\FocalPointPicker;
use Phpsa\FilamentCms\Components\Filters\CreationFilter;

class MediaResource extends Resource
{
    protected static ?string $model = CmsMedia::class;

    protected static ?string $navigationIcon = 'heroicon-o-photograph';

    protected static function getNavigationGroup(): ?string
    {
        return strval(__('filament-cms::filament-cms.section.group'));
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make('File')
                            ->hidden(function ($livewire) {
                                return $livewire instanceof EditMedia;
                            })
                            ->schema([
                                MediaUpload::make('filename')
                            ]),
                        Section::make('Preview')
                            ->hidden(function ($livewire) {
                                return $livewire instanceof CreateMedia;
                            })
                            ->schema([
                                ViewField::make('preview')
                                    ->view('filament-cms::filament.components.media-preview')
                                    ->disableLabel()
                                    ->afterStateHydrated(function ($component, $state, $record) {
                                        $component->state($record);
                                    }),
                            ]),
                        Section::make('Details')
                            ->hidden(function ($livewire) {
                                    return $livewire instanceof CreateMedia;
                            })
                            ->schema([
                                DateTimePlaceholder::make('created_at')
                                    ->label('Uploaded on'),
                                Placeholder::make('file_type')
                                    ->label('File Type')
                                    ->content(fn ($record): string => $record->type),
                                Placeholder::make('file_size')
                                    ->label('File Size')
                                    ->content(fn ($record): string => $record->humanSize()),
                                Placeholder::make('dimensions')
                                    ->label('Dimensions')
                                    ->content(fn ($record): string => $record->width . ' x ' . $record->height),
                                Placeholder::make('disk')
                                    ->label('Disk')
                                    ->content(fn ($record): string => $record->disk),
                                Placeholder::make('directory')
                                    ->label('Directory')
                                    ->content(fn ($record): string => $record->directory),
                                Placeholder::make('file_name')
                                    ->label('File Name')
                                    ->content(fn ($record): string => $record->filename)->columnSpan(['lg' => 3]),
                            ])->columns(['lg' => 3]),
                    ])
                    ->columnSpan([
                        'lg' => 'full',
                        'xl' => 2
                    ]),
                Group::make()
                    ->schema([
                        Section::make('Meta')
                            ->schema([
                                TextInput::make('alt')
                                    ->helperText('<span class="block -mt-1 text-xs"><a href="https://www.w3.org/WAI/tutorials/images/decision-tree" target="_blank" rel="noopener" class="underline text-primary-500 hover:text-primary-600 focus:text-primary-600">Learn how to describe the purpose of the image</a>. Leave empty if the image is purely decorative.</span>'),
                                TextInput::make('title'),
                                Textarea::make('caption')
                                    ->rows(2),
                                Textarea::make('description')
                                    ->rows(2),
                                FocalPointPicker::make('focal_point')
                                ->default(config('filament-cms.media.focal'))
                                        ->imageField('filename')
                            ])
                    ])->columnSpan([
                        'lg' => 'full',
                        'xl' => 1,
                    ]),
            ])->columns([
                'lg' => 3,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('thumbnail_url')->height(50)->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('filename')
                    ->searchable()
                     ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),
                TextColumn::make('size')
                    ->formatStateUsing(fn($record) => $record->humanSize())
                    ->searchable()
                     ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),
                IconColumn::make('disk')
                  ->toggleable(isToggledHiddenByDefault: false)
                    ->options([
                        'heroicon-o-cloud',
                        'heroicon-o-server' => fn ($state): bool => config("filesystems.disks.{$state}.driver") === 'local',
                    ])
                    ->colors([
                        'secondary',
                        'primary' => fn ($state): bool =>  config("filesystems.disks.{$state}.driver") === 'local',
                    ])->tooltip(fn($record) => $record->disk),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
            ])
            ->filters([
                CreationFilter::make()
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListMedia::route('/'),
            'create' => CreateMedia::route('/create'),
            'edit'   => EditMedia::route('/{record}/edit'),
        ];
    }
}
