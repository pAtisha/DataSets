    public function stayInTouch(Request $request, Contact $contact)
    {
        $contact->throwInactive();

        $frequency = intval($request->input('frequency'));
        $state = $request->input('state');

        if (AccountHelper::hasLimitations(auth()->user()->account)) {
            throw new \LogicException(trans('people.stay_in_touch_premium'));
        }

        // if not active, set frequency to 0
        if (! $state) {
            $frequency = 0;
        }
        $result = $contact->updateStayInTouchFrequency($frequency);

        if (! $result) {
            throw new \LogicException(trans('people.stay_in_touch_invalid'));
        }

        $contact->setStayInTouchTriggerDate($frequency);

        return [
            'frequency' => $frequency,
            'trigger_date' => $contact->stay_in_touch_trigger_date,
        ];
    }