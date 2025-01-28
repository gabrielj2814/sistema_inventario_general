<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Customer;
use App\Models\Order;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{

    protected static ?string $navigationLabel = 'Order';

    // protected static ?string $recordTitleAttribute = "name";

    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("dateOrder")->label("Date")->date("Y-m-d"),
                TextColumn::make("customer.name")->label("Customer"),
                TextColumn::make("user.name")->label("Employee"),
                TextColumn::make("total")->label("Total"),
                TextColumn::make("statu")->label("Status"),
            ])
            ->filters([
                //
                Filter::make("Date")->form([
                    DatePicker::make('from')->default(now()),
                    DatePicker::make('until')->default(now()),
                ])
                ->query(function (Builder $query, array $data):Builder {
                    return $query->whereBetween('dateOrder', [$data['from'], $data['until']]);
                }),
                SelectFilter::make('user_id')->label('Employee')
                ->options(User::all()->pluck('name', 'id'))
                ->searchable()
                ->preload(),
                SelectFilter::make('user_id')->label('Customer')
                ->options(Customer::all()->pluck('name', 'id'))
                ->searchable()
                ->preload(),
                SelectFilter::make("statu")->label("Statu")->options([
                    'Pendiente' => 'pendiente',
                    'Completado' => 'completado',
                    'Cancelado' => 'cancelado',
                ])
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            // 'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
