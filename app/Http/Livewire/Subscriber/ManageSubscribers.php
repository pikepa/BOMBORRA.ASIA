<?php

namespace App\Http\Livewire\Subscriber;

use App\Http\Livewire\Datatable\WithSorting;
use App\Jobs\Subscribers\SendWebUpdate;
use App\Models\Subscriber;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class ManageSubscribers extends Component
{
    use WithPagination, WithSorting;

    public $searchField = 'name';

    public $showAlert = false;

    public $showFilters = false;

    public $filters = [
        'search' => '',
        'status' => '',
        'val-date-min' => null,
        'val-date-max' => null,
        'create-date-min' => null,
        'create-date-max' => null,
    ];

    public $selectPage = false;

    public $selectAll = false;

    public $selected = [];

    protected $queryString = ['sortField', 'sortDirection'];

    public function paginationView()
    {
        return 'pagination';
    }

    public function updatedSelectPage($value)
    {
        $this->selected = $value
        ? $this->subscribers->pluck('id')->map(fn ($id) => (string) $id)
        : [];
    }

    public function updatedSelected()
    {
        $this->selectAll = false;
        $this->selectPage = false;
    }

    public function selectAll()
    {
        $this->selectAll = true;
    }

    public function deleteSelected()
    {
        $this->subscribersQuery
            ->unless($this->selectAll, fn ($query) => $query->whereKey($this->selected))
            ->delete();

        $recs = count($this->selected);
        $this->selected = [];

        session()->flash('message', $recs.' Subscribers successfully deleted.');
        session()->flash('alertType', 'success');
    }

    public function sendEmails()
    {
        foreach ($this->selected as $value) {
            dispatch(new SendWebUpdate($value));
        }

        $recs = count($this->selected);
        $this->selected = [];

        session()->flash('message', $recs.' Subscriber email jobs submitted.');
        session()->flash('alertType', 'success');
    }

    public function cancel()
    {
        $this->resetBanner();
    }

    public function resetBanner()
    {
        $this->showAlert = true;

        session()->flash('message', '');
        session()->flash('alertType', '');
    }

    public function updatedFilters()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset('filters');
    }

    public function getSubscribersQueryProperty()
    {
        $query = Subscriber::query()
            ->when($this->filters['status'], fn ($query, $status) => $status == 'VAL' ? $query->where('validated_at', '<>', null) : $query->whereNull('validated_at'))
            ->when($this->filters['search'], fn ($query, $search) => $query->where('name', 'like', '%'.$search.'%'))
            ->when($this->filters['val-date-min'], fn ($query, $date) => $query->where('validated_at', '>=', Carbon::parse($date)))
            ->when($this->filters['val-date-max'], fn ($query, $date) => $query->where('validated_at', '<=', Carbon::parse($date)))
            ->when($this->filters['create-date-min'], fn ($query, $date) => $query->where('created_at', '>=', Carbon::parse($date)))
            ->when($this->filters['create-date-max'], fn ($query, $date) => $query->where('created_at', '<=', Carbon::parse($date)));

        return $this->applySorting($query);
    }

    public function getSubscribersProperty()
    {
        return $this->subscribersQuery->paginate(10);
    }

    public function mount()
    {
        $this->sortField = 'created_at';
    }

    public function render()
    {
        if ($this->selectAll) {
            $this->selected = $this->subscribers->pluck('id')->map(fn ($id) => (string) $id);
        }

        return view('livewire.subscriber.manage-subscribers', [
            'subscribers' => $this->subscribers,
        ]);

    }
}
