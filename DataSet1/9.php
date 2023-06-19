    public function follow_course(Request $request, $id)
    {
        $user_id = Auth::id();

        $user = User::find($user_id);
        if($user->index_number == null)
            return redirect('/student/user/edit/'.$user_id)->with('error', 'Molimo Vas unesite broj indeksa!');

        $course_id = (int)$id;

        $course = Course::find($id);
        $course_password = $course->password;

        if($course_password != $request->password)
        {
            return redirect('/student/courses')
                ->with('error', 'Pogrešna šifra, pokušajte ponovo.');
        }

        $input['user_id'] = $user_id;
        $input['course_id'] = $course_id;


        Follow::create($input);

        return redirect('/student/courses')
            ->with('success','Uspešno ste se prijavili na kurs.');

    }