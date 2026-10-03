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
        return view('contact.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view(auth()->user()->role === 'admin' ? 'contact.create' : 'home.contact');
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
        return redirect()->route(auth()->user()->role === 'admin' ? 'contact.index' : 'home');
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact)
    {
        $this->authorizeContact($contact);
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
        $this->authorizeContact($contact);
        //
        return view("contact.edit" , compact("contact"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contact $contact)
    {
        $this->authorizeContact($contact);
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
        $this->authorizeContact($contact);
        
        $contact->delete();
        flash()->error("Contact Has Deleted $contact->title" );
        return redirect()->back();
        //
    }
    private function authorizeContact(Contact $contact): void
    {
        abort_unless(auth()->user()->role === 'admin' || $contact->user_id === auth()->id(), 403);
    }
}
