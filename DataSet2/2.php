    public function edit_contact(Contact $contact)
    {
        $contact->throwInactive();

        $now = now();
        $age = (string) (! is_null($contact->birthdate) ? $contact->birthdate->getAge() : 0);
        $birthdate = ! is_null($contact->birthdate) ? $contact->birthdate->date->toDateString() : $now->toDateString();
        $deceaseddate = ! is_null($contact->deceasedDate) ? $contact->deceasedDate->date->toDateString() : '';
        $day = ! is_null($contact->birthdate) ? $contact->birthdate->date->day : $now->day;
        $month = ! is_null($contact->birthdate) ? $contact->birthdate->date->month : $now->month;

        $hasBirthdayReminder = ! is_null($contact->birthday_reminder_id);
        $hasDeceasedReminder = ! is_null($contact->deceased_reminder_id);

        $accountHasLimitations = AccountHelper::hasLimitations(auth()->user()->account);

        return view('people.edit')
            ->withAccountHasLimitations($accountHasLimitations)
            ->withContact($contact)
            ->withDays(DateHelper::getListOfDays())
            ->withMonths(DateHelper::getListOfMonths())
            ->withBirthdayState($contact->getBirthdayState())
            ->withBirthdate($birthdate)
            ->withDeceaseddate($deceaseddate)
            ->withDay($day)
            ->withMonth($month)
            ->withAge($age)
            ->withHasBirthdayReminder($hasBirthdayReminder)
            ->withHasDeceasedReminder($hasDeceasedReminder)
            ->withGenders(GenderHelper::getGendersInput())
            ->withFormNameOrder(FormHelper::getNameOrderForForms(auth()->user()));
    }