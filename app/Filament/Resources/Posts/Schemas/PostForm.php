<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Enums\PostStatus;
use App\Enums\PostVisibility;
use App\Enums\UserRole;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true),
                Section::make('Informasi Artikel')
                    ->description('Informasi dasar dan pengaturan publikasi artikel.')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                SpatieMediaLibraryFileUpload::make('featured_image')
                                    ->label('Gambar Utama')
                                    ->collection('featured_image')
                                    ->image()
                                    ->imageEditor()
                                    ->columnSpan(1),
                                Grid::make(1)
                                    ->schema([
                                        Select::make('author_id')
                                            ->label('Penulis')
                                            ->relationship('author', 'name')
                                            ->default(fn() => Auth::id())
                                            ->required()
                                            ->disabled(function (): bool {
                                                /** @var User|null $user */
                                                $user = Auth::user();

                                                return ! $user?->hasRole(UserRole::SuperAdmin->value);
                                            })
                                            ->saved()
                                            ->searchable(),

                                        Select::make('visibility')
                                            ->label('Visibilitas')
                                            ->options(function (): array {
                                                /** @var User|null $user */
                                                $user = Auth::user();

                                                $options = [
                                                    PostVisibility::Public->value =>
                                                    PostVisibility::Public->getLabel(),

                                                    PostVisibility::Authenticated->value =>
                                                    PostVisibility::Authenticated->getLabel(),
                                                ];

                                                if ($user?->hasRole(UserRole::SuperAdmin->value)) {
                                                    $options[PostVisibility::Restricted->value] =
                                                        PostVisibility::Restricted->getLabel();
                                                }

                                                return $options;
                                            })
                                            ->default(PostVisibility::Public->value)
                                            ->required(),
                                    ]),
                            ]),

                        TextInput::make('title')
                            ->label('Judul')
                            ->placeholder('Masukkan judul artikel...')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (?string $state, Set $set) {
                                $set('slug', Str::slug($state));
                            }),

                        Hidden::make('slug')
                            ->required(),

                        Textarea::make('excerpt')
                            ->label('Ringkasan')
                            ->placeholder('Tulis ringkasan singkat artikel...')
                            ->rows(3)
                            ->helperText('Ringkasan akan digunakan sebagai pengantar artikel.')
                            ->columnSpanFull(),
                    ])
                    ->columns(1),

                Section::make('Konten Artikel')
                    ->description('Tulis dan kelola isi artikel.')
                    ->schema([
                        RichEditor::make('content')
                            ->label('Konten')
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
            ]);
    }
}
