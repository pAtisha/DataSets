    public function store_contact(Request $request)
    {
        try {
            $contact = app(CreateContact::class)->execute([
                'account_id' => auth()->user()->account_id,
                'author_id' => auth()->user()->id,
                'first_name' => $request->input('first_name'),
                'middle_name' => $request->input('middle_name', null),
                'last_name' => $request->input('last_name', null),
                'nickname' => $request->input('nickname', null),
                'email' => $request->input('email', null),
                'gender_id' => $request->input('gender'),
                'is_birthdate_known' => false,
                'is_deceased' => false,
                'is_deceased_date_known' => false,
            ]);
        } catch (ValidationException $e) {
            return back()
                ->withInput()
                ->withErrors($e->validator);
        }

        // Did the user press "Save" or "Submit and add another person"
        if (! is_null($request->input('save'))) {
            return redirect()->route('people.show', $contact);
        } else {
            return redirect()->route('people.create')
                            ->with('status', trans('people.people_add_success', ['name' => $contact->name]));
        }
    }