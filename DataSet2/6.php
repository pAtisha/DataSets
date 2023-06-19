    public function notes()
    {
        $notesCollection = collect([]);
        $notes = auth()->user()->account->notes()->favorited()->get();

        foreach ($notes as $note) {
            $data = [
                'id' => $note->id,
                'body' => $note->body,
                'created_at' => DateHelper::getShortDate($note->created_at),
                'name' => $note->contact->getIncompleteName(),
                'contact' => [
                    'id' => $note->contact->hashID(),
                    'has_avatar' => $note->contact->has_avatar,
                    'avatar_url' => $note->contact->getAvatarURL(),
                    'initials' => $note->contact->getInitials(),
                    'default_avatar_color' => $note->contact->default_avatar_color,
                    'complete_name' => $note->contact->name,
                ],
            ];
            $notesCollection->push($data);
        }

        return $notesCollection;
    }