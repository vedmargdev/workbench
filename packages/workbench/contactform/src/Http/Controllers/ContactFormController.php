<?php

namespace Workbench\ContactForm\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Workbench\ContactForm\Models\Contact;


class ContactFormController extends Controller
{
    public function create(){
        return view('contactform::create');
    }

      public function store(Request $request)
    {
        $validated = $request->validate([
            'fname'   => 'required|string|max:255',
            'lname'   => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'email'   => 'required|email|max:255',
            'phone'   => 'required|digits:10',
            'message' => 'required|string',
        ]);

        Contact::create($validated);

        return back()->with('success', 'Thank you for contacting us!');
    }
}
  