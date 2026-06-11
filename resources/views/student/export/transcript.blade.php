<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academic Transcript - {{ $student->student_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', serif;
            font-size: 11pt;
            line-height: 1.4;
            padding: 20mm;
            background: white;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px double #000;
            padding-bottom: 15px;
        }

        .header h1 {
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .header h2 {
            font-size: 14pt;
            font-weight: normal;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 10pt;
            color: #333;
        }

        .title {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            margin: 20px 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .info-table td {
            padding: 3px 0;
            vertical-align: top;
        }

        .info-table .label {
            width: 150px;
        }

        .info-table .separator {
            width: 20px;
            text-align: center;
        }

        table.grade {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table.grade th,
        table.grade td {
            border: 1px solid #000;
            padding: 6px 8px;
        }

        table.grade th {
            background: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }

        table.grade td.center {
            text-align: center;
        }

        table.grade td.right {
            text-align: right;
        }

        table.grade tfoot td {
            font-weight: bold;
            background: #f9f9f9;
        }

        .summary {
            margin-top: 20px;
        }

        .summary-grid {
            display: flex;
            justify-content: space-between;
        }

        .summary-box {
            flex: 1;
            text-align: center;
            padding: 15px;
            border: 1px solid #000;
            margin: 0 5px;
        }

        .summary-box:first-child {
            margin-left: 0;
        }

        .summary-box:last-child {
            margin-right: 0;
        }

        .summary-box .value {
            font-size: 24pt;
            font-weight: bold;
        }

        .summary-box .label {
            font-size: 10pt;
            color: #666;
        }

        .footer {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }

        .footer .signature {
            text-align: center;
            width: 200px;
        }

        .footer .signature .line {
            border-top: 1px solid #000;
            margin-top: 60px;
            padding-top: 5px;
        }

        .print-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 10px 20px;
            background: #4f46e5;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
        }

        .print-btn:hover {
            background: #4338ca;
        }

        @media print {
            body {
                padding: 10mm;
            }

            .print-btn {
                display: none;
            }
        }
    </style>
</head>

<body>
    <button class="print-btn" onclick="window.print()">{{ __('Print / PDF') }}</button>

    <div class="header">
        <h1>{{ __('System University') }}</h1>
        <h2>{{ $student->studyProgram->faculty->name ?? __('FACULTY') }}</h2>
        <p>{{ __('University Address 2') }}</p>
    </div>

    <div class="title">{{ __('Academic Transcript') }}</div>

    <table class="info-table">
        <tr>
            <td class="label">{{ __('Student Name') }}</td>
            <td class="separator">:</td>
            <td><strong>{{ $student->user->name }}</strong></td>
            <td class="label">{{ __('Study Program') }}</td>
            <td class="separator">:</td>
            <td>{{ $student->studyProgram->name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">{{ __('Student ID') }}</td>
            <td class="separator">:</td>
            <td><strong>{{ $student->student_number }}</strong></td>
            <td class="label">{{ __('Faculty') }}</td>
            <td class="separator">:</td>
            <td>{{ $student->studyProgram->faculty->name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">{{ __('Date Printed') }}</td>
            <td class="separator">:</td>
            <td>{{ now()->format('d F Y') }}</td>
            <td class="label">{{ __('Academic Advisor') }}</td>
            <td class="separator">:</td>
            <td>{{ $student->academicAdvisor->user->name ?? '-' }}</td>
        </tr>
    </table>

    <table class="grade">
        <thead>
            <tr>
                <th style="width: 40px;">{{ __('No.') }}</th>
                <th style="width: 80px;">{{ __('Course Code') }}</th>
                <th>{{ __('Course Name') }}</th>
                <th style="width: 50px;">{{ __('Credits') }}</th>
                <th style="width: 60px;">{{ __('Grade') }}</th>
                <th style="width: 60px;">{{ __('Weight') }}</th>
            </tr>
        </thead>
        <tbody>
            @php $totalCredits = 0; $totalBobot = 0; @endphp
            @foreach($gradeList as $index => $grade)
            @php
            $course = $grade->academicClass->course;
            $weight = match($grade->letter_grade) {
            'A' => 4.0,
            'B+' => 3.5,
            'B' => 3.0,
            'C+' => 2.5,
            'C' => 2.0,
            'D' => 1.0,
            default => 0
            };
            $gradeWeight = $weight * $course->credits;
            $totalCredits += $course->credits;
            $totalBobot += $gradeWeight;
            @endphp
            <tr>
                <td class="center">{{ $index + 1 }}</td>
                <td class="center">{{ $course->course_code }}</td>
                <td>{{ $course->course_name }}</td>
                <td class="center">{{ $course->credits }}</td>
                <td class="center"><strong>{{ $grade->letter_grade ?? '-' }}</strong></td>
                <td class="center">{{ number_format($gradeWeight, 1) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="right">{{ __('Total') }}</td>
                <td class="center">{{ $totalCredits }}</td>
                <td class="center">-</td>
                <td class="center">{{ number_format($totalBobot, 1) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="summary">
        <div class="summary-grid">
            <div class="summary-box">
                <div class="value">{{ number_format($cgpaData['gpa'], 2) }}</div>
                <div class="label">{{ __('Cumulative GPA') }}</div>
            </div>
            <div class="summary-box">
                <div class="value">{{ $cgpaData['total_credits'] }}</div>
                <div class="label">{{ __('Total Credits') }}</div>
            </div>
            <div class="summary-box">
                <div class="value">{{ $gradeList->count() }}</div>
                <div class="label">{{ __('Courses') }}</div>
            </div>
        </div>
    </div>

    <div class="footer">
        <div></div>
        <div class="signature">
            Kota Akademik, {{ now()->format('d F Y') }}<br>
            {{ __('Dean,') }}
            <div class="line">
                <strong>_______________________</strong><br>
                {{ __('NIP.') }} ___________________
            </div>
        </div>
    </div>
</body>

</html>