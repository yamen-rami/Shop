<?php

namespace App\Http\Controllers;

use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ColorController extends Controller
{
    public function index()
    {
        return view('colors.index');
    }

    public function create()
    {
        return view('colors.form', ['color' => new Color]);
    }

    public function store(Request $request)
    {
        Color::create($request->validate([
            'name' => ['required', 'string', 'min:2', 'max:100', 'unique:colors,name'],
        ]));
        flash()->success('Color created successfully!');

        return redirect()->route('color.index');
    }

    public function edit(Color $color)
    {
        return view('colors.form', compact('color'));
    }

    public function update(Request $request, Color $color)
    {
        $color->update($request->validate([
            'name' => ['required', 'string', 'min:2', 'max:100', Rule::unique('colors', 'name')->ignore($color)],
        ]));
        flash()->success('Color updated successfully!');

        return redirect()->route('color.index');
    }

    public function destroy(Color $color)
    {
        if ($color->images()->exists()) {
            return back()->withErrors(['color' => 'This color is used by product images. Assign those images another color before deleting it.']);
        }

        $color->delete();
        flash()->success('Color deleted successfully!');

        return redirect()->route('color.index');
    }

    public function options(Request $request)
    {
        return app(SelectOptionsController::class)->index($request, 'colors');
    }
}
