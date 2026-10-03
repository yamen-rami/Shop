<?php

namespace App\Http\Controllers;

use App\Models\{Catagory, Company, Product, Tag};
use Illuminate\Http\Request;

class SelectOptionsController extends Controller
{
    public function index(Request $request, string $resource)
    {
        $model = match ($resource) {
            'categories' => Catagory::class, 'companies' => Company::class,
            'products' => Product::class, 'tags' => Tag::class,
            default => abort(404),
        };
        if ($resource === 'tags') {
            abort_unless($request->user()?->role === 'admin', 403);
        } elseif ($resource === 'products') {
            abort_unless($request->user(), 401);
        }
        $data = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'page' => ['nullable', 'integer', 'min:1'],
            'ids' => ['nullable', 'array', 'max:20'],
            'ids.*' => ['integer', 'min:1'],
        ]);
        $columns = $resource === 'products' ? ['id', 'name', 'price'] : ['id', 'name'];
        $query = $model::query()->select($columns)->orderBy('name')->orderBy('id');
        if (isset($data['ids'])) {
            $records = $query->whereKey($data['ids'])->limit(20)->get();
            $more = false;
        } else {
            $term = trim($data['q'] ?? '');
            $page = $query->when($term !== '', fn ($query) => $query->where('name', 'like', "%{$term}%"))
                ->paginate(20, $columns, 'page', $data['page'] ?? 1);
            $records = $page->getCollection();
            $more = $page->hasMorePages();
        }
        return response()->json([
            'results' => $records->map(fn ($record) => array_filter([
                'id' => $record->id, 'text' => $record->name,
                'price' => $resource === 'products' ? (float) $record->price : null,
            ], fn ($value) => $value !== null))->values(),
            'pagination' => ['more' => $more],
        ]);
    }
}
