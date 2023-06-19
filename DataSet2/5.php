    public function calls()
    {
        $callsCollection = collect([]);
        $calls = auth()->user()->account->calls()
            ->get()
            ->reject(function ($call) {
                return $call->contact === null;
            })
            ->take(15);

        foreach ($calls as $call) {
            $data = [
                'id' => $call->id,
                'called_at' => DateHelper::getShortDate($call->called_at),
                'name' => $call->contact->getIncompleteName(),
                'contact_id' => $call->contact->hashID(),
            ];
            $callsCollection->push($data);
        }

        return $callsCollection;
    }