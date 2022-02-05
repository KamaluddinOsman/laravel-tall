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
    public $sortBy = 'id';
    public $sortAsc = true;
    public $item;

    public $confirmingItemDeletion = false;
    public $confirmingItemAdd = false;

    protected $queryString = [
        'active' => ['except' => false],
        'q' => ['except' => ''],
        'sortBy' => ['except' => 'id'],
        'sortAsc' => ['except' => true],
    ];

    protected $rules = [
        'item.name' => 'required|string|min:4',
        'item.price' => 'required|numeric|between:1,100',
        'item.status' => 'boolean'
    ];

    public function render()
    {
        $items = Item::where( 'user_id', auth()->user()->id )
            ->when( $this->q, function( $query ) {
                return $query->where( function( $query ) {
                    $query->where('name', 'like', '%'. $this->q .'%' )
                        ->orWhere('price', 'like', '%'. $this->q .'%' );
                });
            })
            ->when( $this->active, function( $query ){
                return $query->active();
                })
            ->orderBy( $this->sortBy, $this->sortAsc ? 'ASC' : 'DESC' )
            ->paginate(10);

            // $query = $items->toSql();
            // $items = $items->paginate(10);

        return view('livewire.items', [
            'items' => $items
            // 'query' => $query
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

    public function sortBy( $filed ){
        if( $filed == $this->sortBy){
            $this->sortAsc = !$this->sortAsc;
        }
        $this->sortBy = $filed;
    }

    public function confirmItemDeletion ( $id ){
        $this->confirmingItemDeletion = $id;
    }

    public function deleteItem( Item $item){
        $item->delete();
        $this->confirmingItemDeletion = false;
        session()->flash('message', 'Item has been deleted seccussfly');
    }

    public function confirmItemAdd (){
        $this->reset(['item']);
        $this->confirmingItemAdd = true;
    }

    public function confirmItemEdit( Item $item )
    {
        $this->item = $item;
        $this->confirmingItemAdd = true;
    }

    public function saveItem()
    {
        $this->validate();

        if( isset( $this->item->id ) ){
            $this->item->save();
            session()->flash('message', 'Item has been edited seccussfly');
        }else {
            auth()->user()->items()->create([
                'name' => $this->item['name'],
                'price' => $this->item['price'],
                'status' => $this->item['status'] ?? 0,
            ]);
            session()->flash('message', 'Item has been added seccussfly');
        }

        $this->confirmingItemAdd = false;
    }
}
