<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Senior High Report Card</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12px;
            margin: 20px;
        }

        .school-header {
            text-align: center;
        }

        .school-header h2 {
            margin: 0;
            font-weight: bold;
        }

        .school-header p {
            margin: 2px 0;
        }

        .school-header a {
            color: blue;
            text-decoration: underline;
        }

        h3.report-title {
            text-align: center;
            color: navy;
            font-weight: bold;
            margin: 10px 0;
        }

        .student-info {
            margin: 15px 0;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 2px solid black;
        }

        th, td {
            padding: 4px;
            text-align: center;
        }

        th {
            font-weight: bold;
        }

        .signature {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
        }

        .signature div {
            text-align: center;
            width: 200px;
        }

        .print-button {
            margin-bottom: 20px;
            padding: 8px 16px;
            background-color: #002966;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
        }

        .print-button:hover {
            background-color: #001a4d;
        }

        @media print {
            .print-button {
                display: none;
            }
        }

        .red-grade {
            color: rgb(236, 3, 3);
            font-weight: bold;
        }
        
        
    </style>
</head>
<body>

<button class="print-button" onclick="window.print()">🖨 Print Report Card</button>

<div class="school-header">
    <h2>ED MOL MEMORIAL MATADI BAPTIST HIGH SCHOOL</h2>
    <p>New Matadi Estate Drive, Opposite Don Bosco Youth Center</p>
    <p>P.O. Box: 4330 - Monrovia, Liberia</p>
    <p><a href="mailto:emmmbhs@gmail.com">emmmbhs@gmail.com</a> - 0778127778 / 0886566869</p>
</div>

<h3 class="report-title">SENIOR HIGH REPORT CARD</h3>

<div class="student-info">
    <span>STUDENT'S NAME: {{ $student->name }}</span>
    <span style="float: right;">GRADE: {{ $student->class_applying_for }}</span>
</div>

<div class="student-info">
    <span>ACADEMIC YEAR: {{ $grades->first()->academic_year ?? 'N/A' }}</span>
</div>

<table>
    <thead>
        <tr>
            <th>Subject</th>
            <th>1st Period</th>
            <th>2nd Period</th>
            <th>3rd Period</th>
            <th>1st Sem. Exam</th>
            <th>1st Sem. Average</th>
            <th>4th Period</th>
            <th>5th Period</th>
            <th>6th Period</th>
            <th>2nd Sem. Exam</th>
            <th>2nd Sem. Average</th>
            <th>Yearly Average</th>
        </tr>
    </thead>
    <tbody>
        @php
            $firstSemTotal = 0;
            $secondSemTotal = 0;
            $subjectCount = $grades->count();
        @endphp

        @foreach($grades as $grade)
            @php
                $firstSemAvg = round(($grade->period1 + $grade->period2 + $grade->period3 + $grade->exam1) / 2, 2);

               $secondSemAvg = round(($grade->period4 + $grade->period5 + $grade->period6 + $grade->exam2) / 2, 2);

               $yearAvg = round(($firstSemAvg + $secondSemAvg) / 2, 2);

                $firstSemTotal += $firstSemAvg;
                $secondSemTotal += $secondSemAvg;

                // Function to display red for grades < 65-69
              
           $color = fn($val) => ($val <= 69 && $val !== null) ? 'red-grade' : '';

            @endphp
            <tr>
                <td>{{ $grade->subject->name }}</td>
<td class="{{ $color($grade->period1) }}"><strong>{{ $grade->period1 }}</strong></td>
<td class="{{ $color($grade->period2) }}"><strong>{{ $grade->period2 }}</strong></td>
<td class="{{ $color($grade->period3) }}"><strong>{{ $grade->period3 }}</strong></td>
<td class="{{ $color($grade->exam1) }}"><strong>{{ $grade->exam1 }}</strong></td>
<td class="{{ $color($firstSemAvg) }}"><strong>{{ $firstSemAvg }}</strong></td>

<td class="{{ $color($grade->period4) }}"><strong>{{ $grade->period4 }}</strong></td>
<td class="{{ $color($grade->period5) }}"><strong>{{ $grade->period5 }}</strong></td>
<td class="{{ $color($grade->period6) }}"><strong>{{ $grade->period6 }}</strong></td>
<td class="{{ $color($grade->exam2) }}"><strong>{{ $grade->exam2 }}</strong></td>
<td class="{{ $color($secondSemAvg) }}"><strong>{{ $secondSemAvg }}</strong></td>

<td class="{{ $color($yearAvg) }}"><strong>{{ $yearAvg }}</strong></td>
            </tr>
        @endforeach

        @php
            $overallAverage = round(($firstSemTotal + $secondSemTotal)/($subjectCount*2),2);
        @endphp

        <tr>
<td><strong>Average</strong></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td><strong>{{ $overallAverage }}</strong></td>
</tr>

<tr>
<td><strong>Rank</strong></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td><strong>{{ $rank }} / {{ $totalStudents }}</strong></td>
</tr>

<tr>
<td><strong>Conduct</strong></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
</tr>
    </tbody>
</table>

<div class="signature">
    <div>
        Signed: ______________________<br>
        Class Sponsor
    </div>
    <div>
        Approved: ______________________<br>
        Principal
    </div>
</div>

</body>
</html>