<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr as SupportArr;
use Illuminate\Support\Facades\Storage;

use App\Models\{Company, Product};
use App\Http\Requests\{StoreCompanyRequest, UpdateCompanyRequest};

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get all the companies
        $sort = $request->sort ?? "desc";
        $companies = Company::with("products")
            ->orderBy("id", $sort)
            ->where("name", "like", "%" . $request->search . "%")
            ->orWhere("desc", "like", "%" . $request->search . "%")
            ->latest()->paginate(30);
        return view("companies.index", [
            "companies" => $companies,
            "sort" => $sort === "desc" ? $sort = "asc" : $sort = "desc"
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $products = Product::all();
        return view("companies.create", [
            "products" => $products,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompanyRequest $request)
    {
        //
        $data = $request->validated();

        if ($data["image"]) {
            $path = $data["image"]->store("companies", "public");
            $data["image"] = $path;
        }
        $company = Company::create(SupportArr::except($data, "product_id"));
        flash()->success("Company Created Succefully");
        $product = Product::where("id", $data["product_id"])->first();
        $productName = $product->name;
        $company->products()->attachOrFail($data["product_id"]);
        flash()->success("It's Been Linked to the Product : $productName");
        return redirect()->route("company.index");
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        // ? show company 
        return view("companies.show", [
            'company' => $company,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
        $products = Product::all();
        return view('companies.edit', [
            "company" => $company,
            "products" => $products,
        ]);
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompanyRequest $request, Company $company)
    {
        $data = $request->validated();
        if ($request->image) {

            if ($data['image']) {
                if ($company->image) {
                    Storage::disk()->delete($company->image);
                }
                $path = $data['image']->store("companies", "public");
                $data["image"] = $path;
            }
        }
        $c = $company->update(SupportArr::except($data, "product_id"));
        //
        $company->products()->sync($data['product_id']);
        return redirect()->route("company.index");
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        // deleting company
        $company->delete();
        return redirect()->route("product.index");
    }
}
