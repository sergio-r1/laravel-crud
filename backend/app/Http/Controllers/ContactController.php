<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ContactController extends Controller
{
    public function index()
    {
        return response()->json(Contact::orderBy('id', 'desc')->paginate(10));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:contacts,email'],
            'cpf' => ['required', 'string', 'max:20', 'regex:/^[0-9\.\-\/\s]*$/'],
        ], [
            'cpf.regex' => 'The CPF must contain only digits and optionally punctuation marks.',
        ]);

        $contact = Contact::create($validated);
        return response()->json($contact, 201);
    }

    public function show(Contact $contact)
    {
        return response()->json($contact);
    }

    public function update(Request $request, Contact $contact)
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'email', 'max:255', Rule::unique('contacts', 'email')->ignore($contact->id)],
            'cpf' => ['sometimes', 'required', 'string', 'max:14', 'regex:/^[0-9\.\-\/\s]*$/', Rule::unique('contacts', 'cpf')->ignore($contact->id)],
        ]);

        $contact->update($validated);
        return response()->json($contact);
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();
        return response()->json(null, 204);
    }
}
