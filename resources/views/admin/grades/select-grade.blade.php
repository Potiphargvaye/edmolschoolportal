@extends('layouts.admin')

@section('content')

<div class="container">
    <h2>Student Grade Entry</h2>

    <form action="{{ route('grades.load') }}" method="GET">

        <div class="mb-3">
            <label>Academic Year</label>
            <input type="text" name="academic_year" class="form-control" placeholder="2025/2026" required>
        </div>

        <div class="mb-3">
            <label>Select Grade</label>
            <select name="grade_level" class="form-control" required>

                <option value="">Select Grade</option>

                <optgroup label="Elementary">
                    <option value="KG">KG</option>
                    <option value="Grade 1">Grade 1</option>
                    <option value="Grade 2">Grade 2</option>
                    <option value="Grade 3">Grade 3</option>
                    <option value="Grade 4">Grade 4</option>
                    <option value="Grade 5">Grade 5</option>
                    <option value="Grade 6">Grade 6</option>
                </optgroup>

                <optgroup label="Junior High">
                    <option value="Grade 7">Grade 7</option>
                    <option value="Grade 8">Grade 8</option>
                    <option value="Grade 9">Grade 9</option>
                </optgroup>

                <optgroup label="Senior High">
                    <option value="Grade 10">Grade 10</option>
                    <option value="Grade 11">Grade 11</option>
                    <option value="Grade 12">Grade 12</option>
                </optgroup>

            </select>
        </div> 

        <button class="btn btn-primary">
            Load Students
        </button>


    </form>
</div>

@endsection