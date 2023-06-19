    public function delete_answer($id)
    {
        $answer = Answer::find($id);

        if ($answer) {
            $test = Test::find($answer->test_id);
            if($answer->points > 0)
            {
                $test->max_points -= $answer->points;
                $test->save();
            }
        }

        $answer->delete();

        return redirect()->back()->with('success', 'Odgovor uspešno obrisan.');
    }