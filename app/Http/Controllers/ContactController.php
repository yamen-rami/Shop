<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Contact;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $sort = $request->sort ?? "desc";
        $contacts = Contact::with("user")
        ->where("title" , 'LIKE' , "%" . $request->search . "%")
        ->orderBy("id" , $sort)
        ->paginate(30)->withQueryString();
        return view("contact.index" , [
            "contacts" => $contacts ,
            "sort"=> $sort === "desc" ? $sort = 'asc' : $sort = "desc",
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view("home.contact");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $data= $request->validate([
            "title" => ['required' , 'string' , "min:2"],
            "desc" => ['required' , 'string' , "min:2"],
            "email" => ['required' , 'email'],
        ]);
        $data['user_id'] = auth()->id();
        Contact::create($data);
        flash()->success("The Contact Has Sent Succefully");
        return redirect()->route("home");
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact)
    {
        //
        return view("contact.show" , [
            'contact' => $contact ,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contact $contact)
    {
        //
        return view("contact.edit" , compact("contact"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contact $contact)
    {
        //
        $data= $request->validate([
            "title" => ['required' , 'string' , "min:2"],
            "desc" => ['required' , 'string' , "min:2"],
            "email" => ['required' , 'email'],
        ]);
        $contact->update($data);
        $name = $data['title']; 
        flash()->info("Contact Has Updated Succefully $name  ");
        return redirect()->route("contact.index");
        // ! end Contact
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        
        $contact->delete();
        flash()->error("Contact Has Deleted $contact->title" );
        return redirect()->back();
        //
    }
}
