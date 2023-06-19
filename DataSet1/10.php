    public function show_tests($id)
    {
        //check if user is following test
        $user = Auth::user();
        $course_id = $id;

        $follow = Follow::where('user_id', '=', $user->id)->where('course_id', '=', $course_id)->get();

        if($follow->first())
        {
            $course = Course::find($id);

            $tests = Test::where('course_id', '=', $id)->where('active', '=', 1)->get();

            $done_tests = Time::where('course_id', '=', $id)->where('user_id', '=', $user->id)->where('done', '=', 1)->get();

            $history_tests = array();

            foreach ($tests as $index => $test)
            {
                foreach ($done_tests as $done_test)
                {
                    if($test->id == $done_test->test_id)
                    {
                        $history_tests[] = [
                          'name' => $test->name,
                          'starting_time' => $done_test->created_at->format("d-m-Y H:i:s"),
                          'finishing_time' => $done_test->updated_at->format("d-m-Y H:i:s"),
                          'points' => $done_test->points,
                          'max_points' => $test->max_points,
                        ];
                        unset($tests[$index]);
                    }
                }
            }

            return view('user_pages.tests.show', ['course' => $course,
                'tests' => $tests,
                'history_tests' => $history_tests]);
        }
        else
            return redirect()->back()->with('error', 'Morate se prijaviti na kurs!');
    }