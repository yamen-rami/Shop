<?php

namespace App\Livewire;

use App\Models\{Catagory, Company, Contact, Offer, Order, Product, Tag};
use Livewire\Attributes\{Locked, Url};
use Livewire\Component;
use Livewire\WithPagination;

class RecordTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    #[Locked]
    public string $resource = '';
    #[Url]
    public string $search = '';
    #[Url(as: 'sort_by')]
    public string $sortBy = 'id';
    #[Url]
    public string $sort = 'desc';
    #[Url(as: 'category')]
    public string $categoryId = '';
    #[Url(as: 'company')]
    public string $companyId = '';
    #[Url]
    public string $featured = '';
    #[Url]
    public string $status = '';

    private const RESOURCES = [
        'products' => [Product::class, 'product', ['name', 'desc'], ['id', 'name', 'price', 'quantity', 'created_at']],
        'companies' => [Company::class, 'company', ['name', 'desc'], ['id', 'name', 'created_at']],
        'tags' => [Tag::class, 'tag', ['name'], ['id', 'name', 'created_at']],
        'offers' => [Offer::class, 'offer', ['name', 'code'], ['id', 'name', 'start_date', 'end_date'], 'global'],
        'product-offers' => [Offer::class, 'offer', ['name', 'code'], ['id', 'name', 'start_date', 'end_date'], 'products'],
        'coupon-offers' => [Offer::class, 'offer', ['name', 'code'], ['id', 'name', 'start_date', 'end_date'], 'coupon'],
        'category-offers' => [Offer::class, 'offer', ['name', 'code'], ['id', 'name', 'start_date', 'end_date'], 'categories'],
        'orders' => [Order::class, 'order', ['name', 'location'], ['id', 'name', 'price', 'quantity', 'created_at']],
        'contacts' => [Contact::class, 'contact', ['title', 'desc', 'email'], ['id', 'title', 'email', 'created_at']],
    ];

    public function mount(string $resource): void
    {
        $this->resource = $resource;
        $this->ensureAllowed();
    }

    public function boot(): void
    {
        if ($this->resource !== '') {
            $this->ensureAllowed();
        }
    }

    private function ensureAllowed(): void
    {
        abort_unless(isset(self::RESOURCES[$this->resource]), 404);
        abort_unless(auth()->check() && (auth()->user()->role === 'admin'
            || in_array($this->resource, ['orders', 'contacts'], true)), 403);
    }

    public function updated($property): void
    {
        if (in_array($property, ['search', 'sortBy', 'sort', 'categoryId', 'companyId', 'featured', 'status'], true)) {
            $this->resetPage();
        }
    }

    public function clearFilters(): void
    {
        $this->reset('search', 'sortBy', 'sort', 'categoryId', 'companyId', 'featured', 'status');
        $this->resetPage();
    }

    public function render()
    {
        $this->ensureAllowed();
        [$model, $route, $searchable, $sortable] = self::RESOURCES[$this->resource];
        $query = $model::query();
        if ($model === Offer::class) {
            $query->with(['categories', 'products'])->where('type', self::RESOURCES[$this->resource][4]);
            if ($this->status === 'active') {
                $query->active();
            } elseif ($this->status === 'inactive') {
                $query->where(fn ($query) => $query->where('is_active', false)
                    ->orWhere('start_date', '>', now())->orWhere('end_date', '<', now()));
            }
        } elseif ($model === Product::class) {
            $query->with(['catagory', 'tags', 'companies']);
            if ($this->categoryId !== '') {
                $query->where('catagory_id', (int) $this->categoryId);
            }
            if ($this->companyId !== '') {
                $query->whereHas('companies', fn ($query) => $query->whereKey((int) $this->companyId));
            }
            if (in_array($this->featured, ['1', '0'], true)) {
                $query->where('featured', $this->featured === '1');
            }
            if ($this->status === 'in-stock') {
                $query->where('quantity', '>', 0);
            } elseif ($this->status === 'out-of-stock') {
                $query->where('quantity', '<=', 0);
            }
        } elseif ($model === Company::class) {
            $query->with('products');
        } elseif ($model === Order::class) {
            $query->with(['products', 'user']);
            if (auth()->user()->role !== 'admin') {
                $query->whereHas('user', fn ($query) => $query->whereKey(auth()->id()));
            }
        } elseif ($model === Contact::class) {
            $query->with('user');
            if (auth()->user()->role !== 'admin') {
                $query->where('user_id', auth()->id());
            }
        }
        $search = trim($this->search);
        if ($search !== '') {
            $query->where(function ($query) use ($searchable, $search) {
                foreach ($searchable as $column) {
                    $query->orWhere($column, 'like', "%{$search}%");
                }
            });
        }
        $sortBy = in_array($this->sortBy, $sortable, true) ? $this->sortBy : 'id';
        $sort = in_array($this->sort, ['asc', 'desc'], true) ? $this->sort : 'desc';
        return view('livewire.record-table', [
            'records' => $query->orderBy($sortBy, $sort)->when($sortBy !== 'id', fn ($query) => $query->orderBy('id', $sort))->paginate(30),
            'routePrefix' => $route, 'sortable' => $sortable,
            'isOffer' => $model === Offer::class,
            'offers' => $model === Product::class ? app(\App\Services\StorefrontData::class)->offers() : collect(),
        ]);
    }
}
