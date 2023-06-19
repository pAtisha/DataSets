    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'entry' => 'required|string',
            'date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return back()
                ->withInput()
                ->withErrors($validator);
        }

        $entry = new Entry;
        $entry->account_id = $request->user()->account_id;
        $entry->post = $request->input('entry');

        if ($request->input('title') != '') {
            $entry->title = $request->input('title');
        }

        $entry->save();

        $entry->date = $request->input('date');
        // Log a journal entry
        JournalEntry::add($entry);

        return redirect()->route('journal.index');
    }