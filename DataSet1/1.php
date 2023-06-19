    public function active_answer($id)
    {
        $answer = Answer::find($id);

        if($answer->points > 0 && $answer->active == 1)
        {
            $test = Test::find($answer->test_id);
            $test->max_points -= $answer->points;
            $test->save();
        }

        if($answer->points > 0 && $answer->active == 0)
        {
            $test = Test::find($answer->test_id);
            $test->max_points += $answer->points;
            $test->save();
        }

        $answer->active = !$answer->active;

        $answer->update();

        return redirect()->back()->with('success','Vidljivost odgovora uspešno izmenjena.');
    }