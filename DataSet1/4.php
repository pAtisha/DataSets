    public function create_answer(Request $request)
    {
        $request->validate([
            'answer' => 'required',
            'points' => ['required', 'numeric'],
        ]);


        $input = $request->all();

        if($request->has('active'))
        {
            if($input['active'] == "on")
                $input['active'] = 1;
            else
                $input['active'] = 0;
        }
        else
            $input['active'] = 0;

        $input['question_id'] = $request->question_id;
        $input['user_id'] = Auth::id();
        $question = Question::find($request->question_id);
        $input['test_id'] = $question->test_id;
        $input['course_id'] = $question->course_id;
        $position = Answer::where('course_id', '=', $request->course_id)
            ->where('test_id', '=', $request->test_id)
            ->where('question_id', '=', $request->question_id)
            ->max('position');
        $input['position'] = $position + 1;

        //change max_points
        if($request->points > 0)
        {
            $test = Test::find($question->test_id);
            $test->max_points += $request->points;
            $test->save();
        }

        Answer::create($input);

        return back()
            ->with('success','Odgovor uspešno dodat.');
    }