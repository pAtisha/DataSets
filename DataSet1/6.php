    public function submit_answers(Request $request)
    {
        //static
        $user_id = Auth::id();
        $test_id = $request->test_id;
        $course_id = Test::find($test_id)->course_id;

        //done
        $get_time = Time::where('user_id', '=', Auth::id())->where('test_id', '=', $test_id)->get();
        $get_time[0]->done = 1;
        $get_time[0]->save();

        //points counter
        $points = 0;

        $questions = Question::where('test_id', '=', $test_id)->get();

        foreach ($questions as $index => $question)
        {
            if($question->type == "single")
            {
                $answer_value = "answer_single" . $index;
                $answer = $request->$answer_value;
                $answer_db = Answer::where('test_id', '=', $test_id)
                    ->where('answer', '=', $answer)
                    ->where('question_id', '=', $question->id)
                    ->get(['answer', 'points'])[0];

                //Add points
                $points += $answer_db->points;

                //store answer in DB
                TestAnswer::create([
                    'user_id' => $user_id,
                    'test_id' => $test_id,
                    'question_id' => $question->id,
                    'course_id' => $course_id,
                    'answer' => $answer_db->answer,
                    'points' => $answer_db->points
                ]);
            }
            else
            {
                $answer_value = "answer_multi" . $index;
                $answers = $request->$answer_value;

                if($answers)
                {
                    foreach ($answers as $answer)
                    {
                        $answer_db = Answer::where('test_id', '=', $test_id)->where('answer', '=', $answer)->get(['answer', 'points'])[0];

                        //add points
                        $points += $answer_db->points;

                        //store answer in DB
                        TestAnswer::create([
                            'user_id' => $user_id,
                            'test_id' => $test_id,
                            'question_id' => $question->id,
                            'course_id' => $course_id,
                            'answer' => $answer_db->answer,
                            'points' => $answer_db->points
                        ]);
                    }
                }
            }
        }

        //store points in times table
        $get_time[0]->points = $points;
        $get_time[0]->save();

        return redirect('/student/courses/'.$course_id)->with('success', 'Test je uspešno završen!');

    }