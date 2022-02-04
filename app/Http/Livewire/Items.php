<?php

namespace App\Http\Livewire;

use App\Models\Item;
use Livewire\Component;
use Livewire\WithPagination;

class Items extends Component
{
    use WithPagination;

    public $active;
    public $q;

    public function render()
    {
        $items = Item::where( 'user_id', auth()->user()->id )
            ->when( $this->q, function( $qurey ) {
                return $qurey->where( function( $qurey ) {
                    $qurey->where('name', 'like', '%'. $this->q .'%' )
                        ->orWhere('price', 'like', '%'. $this->q .'%' );
                });
            })
            ->when( $this->active, function( $qurey ){
                return $qurey->active();
                })
            ->paginate(10);

            // $qurey = $items->toSql();
            // $items = $items->paginate(10);

        return view('livewire.items', [
            'items' => $items,
            // 'qurey' => $qurey
        ]);
    }

    public function updatingActive()
    {
        return $this->resetPage();
    }

    public function updatingQ()
    {
        return $this->resetPage();
    }
}
