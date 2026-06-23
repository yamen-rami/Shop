<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\{Product, Tag};

class TagController extends Controller
{
    // deleting Tag    

    public function index(Request $request)
    {
        $sort = $request->sort ?? "desc";

        $tags = Tag::where("name", "LIKE", "%" . $request->search . "%")->orderBy('id', $sort)->paginate(30);
        return view("tags.index", [
            "tags" => $tags,
            "sort" => $sort === "desc" ? $sort = "asc" : $sort = "desc",
        ]);
    }
    public function edit(Tag $tag)
    {
        return view("tags.edit", compact("tag"));
    }
    public function create()
    {
        return view("tags.create");
    }
    public function store(Request $request)
    {
        $validate = $request->validate([
            "name" => ["required", "string", "min:2"]
        ]);
        Tag::create($validate);
        flash()->success("Tag Has Been Created");
        return redirect()->route("tag.index");
    }
    public function update(Tag $tag, Request $request)
    {
        $validate = $request->validate([
            'name' => ["required", "string"]
        ]);
        $tag->update($validate);
        flash()->info("Tag Has Updated");
        return redirect()->route('tag.index');
    }
    public function destroy(Tag $tag)
    {
        $tag->delete();
        $tag->products()->detach($tag->id);
        flash()->error("Tag Has Delete in All Related Products");
        return redirect()->route("tag.index");
    }
}
