    public function destroy_contact(Request $request, Contact $contact)
    {
        if ($contact->account_id != auth()->user()->account_id) {
            return redirect()->route('people.index');
        }

        $data = [
            'account_id' => auth()->user()->account_id,
            'contact_id' => $contact->id,
        ];

        DestroyContact::dispatch($data);

        return redirect()->route('people.index')
            ->with('success', trans('people.people_delete_success'));
    }