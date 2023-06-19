    public function index()
    {
        $meContact = null;

        $search = auth()->user()->first_name.' '.
            auth()->user()->last_name.' '.
            auth()->user()->email;
        $existingContacts = Contact::search($search, auth()->user()->account_id, 'id')
            ->real()
            ->whereNotIn('id', [auth()->user()->me_contact_id])
            ->paginate(20);

        if (auth()->user()->me_contact_id) {
            $meContact = Contact::where('account_id', auth()->user()->account_id)
                ->find(auth()->user()->me_contact_id);
            $existingContacts->prepend($meContact);
        }

        $accountHasLimitations = AccountHelper::hasLimitations(auth()->user()->account);

        return view('settings.index')
                ->withAccountHasLimitations($accountHasLimitations)
                ->withMeContact($meContact ? new ContactResource($meContact) : null)
                ->withExistingContacts(ContactResource::collection($existingContacts))
                ->withNamesOrder(User::NAMES_ORDER)
                ->withLocales(LocaleHelper::getLocaleList()->sortByCollator('name-orig'))
                ->withHours(DateHelper::getListOfHours())
                ->withSelectedTimezone(TimezoneHelper::adjustEquivalentTimezone(DateHelper::getTimezone()))
                ->withTimezones(collect(TimezoneHelper::getListOfTimezones())->map(function (array $timezone): array {
                    return ['id' => $timezone['timezone'], 'name'=>$timezone['name']];
                }));
    }