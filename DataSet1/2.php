    public function update_answer(Request $request, $id)
    {
        $request->validate([
            'answer' => 'required',
            'points' => ['required', 'numeric'],
        ]);

        //change max_points
        if($request->points > 0)
        {
            $answer = Answer::find($id);
            $test = Test::find($answer->test_id);
            $test->max_points -= $answer->points;
            $test->max_points += $request->points;
            $test->save();
        }

        Answer::updateOrCreate(
            [
                'id' => $id
            ],
            [
                'answer' => $request->answer,
                'points' => $request->points,
            ]
        );

        return redirect()->back()->with('success', 'Uspešno ste ažurirali odgovor.');
    }