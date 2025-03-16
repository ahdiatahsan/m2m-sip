<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeacherResource\Pages;
use App\Filament\Resources\TeacherResource\RelationManagers\TeacherRelationManager;
use App\Models\Teacher;
use App\Models\User;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section as InfolistSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class TeacherResource extends Resource
{
    protected static ?string $model = Teacher::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'Guru';

    protected static ?string $navigationGroup = 'Pengguna';

    protected static ?string $modelLabel = 'Guru';

    protected static ?string $pluralModelLabel = 'Guru';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    TextInput::make('name')
                        ->label('Nama')
                        ->required()
                        ->columnSpanFull()
                        ->autocomplete(false),
                    TextInput::make('code')
                        ->label('Kode')
                        ->unique(ignoreRecord: true)
                        ->maxLength(2)
                        ->required()
                        ->autocomplete(false),
                    TextInput::make('nip')
                        ->label('NIP')
                        ->unique(ignoreRecord: true)
                        ->maxLength(20)
                        ->numeric()
                        ->nullable()
                        ->autocomplete(false),
                    TextInput::make('phone')
                        ->label('Telepon')
                        ->tel()
                        ->nullable()
                        ->autocomplete(false),
                    Select::make('lesson_id')
                        ->label('Mata Pelajaran')
                        ->required()
                        ->relationship('lesson', 'name')
                        ->native(false)
                        ->searchable(),
                ])
                    ->columns(2),
                Section::make()
                    ->relationship('user')
                    ->schema([
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->columnSpanFull()
                            ->autocomplete(false),
                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->revealable()
                            ->confirmed()
                            ->minLength(8)
                            ->nullable()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create'),
                        TextInput::make('password_confirmation')
                            ->label('Konfirmasi Password')
                            ->minLength(8)
                            ->password()
                            ->revealable(),
                    ])
                    ->columns(2)
                    ->hiddenOn('create'),
                Section::make()
                    ->schema([
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(table: User::class)
                            ->columnSpanFull()
                            ->autocomplete(false),
                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->revealable()
                            ->confirmed()
                            ->minLength(8)
                            ->nullable()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create'),
                        TextInput::make('password_confirmation')
                            ->label('Konfirmasi Password')
                            ->minLength(8)
                            ->password()
                            ->revealable(),
                    ])
                    ->columns(2)
                    ->hiddenOn('edit'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),
                TextColumn::make('nip')
                    ->label('Nomor Induk Pegawai')
                    ->searchable(),
            ])
            ->defaultSort('name', 'asc')
            ->filters([
                SelectFilter::make('lesson')
                    ->relationship('lesson', 'name')
                    ->label('Mata Pelajaran')
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                InfolistSection::make('Informasi Guru')
                    ->schema([
                        TextEntry::make('name')
                            ->label('Nama Lengkap'),
                        TextEntry::make('code')
                            ->label('Kode Guru'),
                        TextEntry::make('nip')
                            ->label('Nomor Induk Pegawai')
                            ->default('-'),
                        TextEntry::make('lesson.name')
                            ->label('Mata Pelajaran'),
                        TextEntry::make('phone')
                            ->label('Nomor Telepon')
                            ->default('-'),
                        TextEntry::make('user.email')
                            ->label('Alamat Email'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            TeacherRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTeachers::route('/'),
            'create' => Pages\CreateTeacher::route('/create'),
            'view' => Pages\ViewTeacher::route('/{record}'),
            'edit' => Pages\EditTeacher::route('/{record}/edit'),
        ];
    }
}
