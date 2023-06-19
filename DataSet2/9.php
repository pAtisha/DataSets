    public function storeDay(DaysRequest $request)
    {
        $day = auth()->user()->account->days()->create([
            'date' => now(DateHelper::getTimezone()),
            'rate' => $request->input('rate'),
            'comment' => $request->input('comment'),
        ]);

        // Log a journal entry
        $journalEntry = JournalEntry::add($day);

        return [
            'id' => $journalEntry->id,
            'date' => $journalEntry->date,
            'journalable_id' => $journalEntry->journalable_id,
            'journalable_type' => $journalEntry->journalable_type,
            'object' => $journalEntry->getObjectData(),
            'show_calendar' => true,
        ];
    }