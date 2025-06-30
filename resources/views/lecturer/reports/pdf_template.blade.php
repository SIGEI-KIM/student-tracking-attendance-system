<!DOCTYPE html>
<html>
<head>
    <title>Attendance Report - {{ $unit->code }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        /* Basic Styles */
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.5;
            color: #333;
            -webkit-print-color-adjust: exact; /* For better print output */
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid #eee;
        }
        .header img { /* Style for the logo */
            max-width: 150px; /* Adjust as needed */
            height: auto;
            margin-bottom: 15px;
            /* ADDED: Rounded corners styling */
            border-radius: 10px; /* Adjust this value for more or less rounding */
            /* If you want a perfectly circular logo, make width and height equal and set border-radius: 50%; */
            /* Example for perfectly circular if width/height are the same: */
            /* width: 100px; */
            /* height: 100px; */
            /* border-radius: 50%; */
            /* object-fit: cover; /* Useful for circular to prevent image stretching */
            /* You can also add a border if you like */
            /* border: 1px solid #ddd; */
        }
        .header h1 {
            margin: 0;
            font-size: 24pt;
            color: #2c3e50;
            line-height: 1.2;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 11pt;
            color: #555;
        }
        .header p strong {
            color: #1a1a1a;
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
            table-layout: fixed; /* Helps with column width distribution */
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px 8px;
            text-align: left;
            word-wrap: break-word; /* Prevents long text from overflowing */
        }
        th {
            background-color: #f8f8f8;
            font-weight: bold;
            font-size: 9pt;
            color: #444;
            text-transform: uppercase;
        }
        td {
            font-size: 8.5pt;
        }

        /* Status Colors */
        .present {
            color: #28a745; /* Green */
            font-weight: bold;
        }
        .absent {
            color: #dc3545; /* Red */
            font-weight: bold;
        }
        .na {
            color: #6c757d; /* Grey */
            font-style: italic;
        }
        .late { /* Added late status color if you use it in your data */
            color: #007bff; /* Blue */
            font-weight: bold;
        }

        /* Alternating row colors */
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        /* NEW: Styles for percentage display */
        .percentage-good {
            color: #28a745; /* Green */
            font-weight: bold;
        }
        .percentage-warning {
            color: #ffc107; /* Orange/Yellow */
            font-weight: bold;
        }
        .percentage-bad {
            color: #dc3545; /* Red */
            font-weight: bold;
        }

        /* Footer */
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 15px;
            border-top: 1px solid #eee;
            font-size: 8pt;
            color: #777;
        }
        .footer a {
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('images/university_logo.png') }}" alt="University Logo">
        <h1>Attendance Report</h1>
        <p><strong>Unit:</strong> {{ $unit->name }} ({{ $unit->code }})</p>
        <p><strong>Course:</strong> {{ $unit->course->name }}</p>
        <p><strong>Level:</strong> {{ $unit->level->name ?? 'N/A' }}</p>
        {{-- CORRECTED LINE: Access lecturer name directly from $user --}}
        <p><strong>Lecturer:</strong> {{ $user->name ?? 'N/A' }}</p>
        <p><strong>Report Period:</strong> {{ $startDate->format('d M, Y') }} - {{ $endDate->format('d M, Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 25%;">Student Name</th>
                <th style="width: 15%;">Reg. Number</th>
                @foreach($reportDates as $date)
                    {{-- Adjusted width for date columns to accommodate new percentage column --}}
                    <th style="width: {{ (50 / count($reportDates)) }}%;">{{ \Carbon\Carbon::parse($date)->format('D, M d') }}</th>
                @endforeach
                {{-- NEW: Add Percentage column header --}}
                <th style="width: 15%;">Percentage</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reportData as $index => $studentRecord)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $studentRecord['name'] }}</td>
                    <td>{{ $studentRecord['registration_number'] }}</td>
                    @foreach($reportDates as $date)
                        @php
                            $status = $studentRecord['presence'][$date] ?? 'N/A';
                            $class = '';
                            if (strtolower($status) === 'present') {
                                $class = 'present';
                            } elseif (strtolower($status) === 'absent') {
                                $class = 'absent';
                            } elseif (strtolower($status) === 'late') { // Make sure 'late' is handled consistently
                                $class = 'late';
                            } else {
                                $class = 'na'; // For any other unexpected status
                            }
                        @endphp
                        <td class="{{ $class }}">{{ ucfirst($status) }}</td>
                    @endforeach
                    {{-- NEW: Display Percentage with conditional styling --}}
                    @php
                        $percentage = $studentRecord['percentage'];
                        $percentageClass = 'percentage-bad'; // Default to bad
                        if ($percentage >= 85) {
                            $percentageClass = 'percentage-good';
                        } elseif ($percentage >= 70) { // Example: Warning for 70-84%
                            $percentageClass = 'percentage-warning';
                        }
                    @endphp
                    <td class="{{ $percentageClass }}">{{ $percentage }}%</td>
                </tr>
            @empty
                <tr>
                    {{-- Adjusted colspan for the "No data" message --}}
                    <td colspan="{{ 3 + count($reportDates) + 1 }}" style="text-align: center; padding: 20px;">
                        No student data found for this unit and period.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Generated by the Attendance Management System on {{ \Carbon\Carbon::now()->format('d M, Y H:i:s') }} EAT.
        <br>
        &copy; {{ \Carbon\Carbon::now()->format('Y') }} Your University/Institution Name. All rights reserved.
    </div>
</body>
</html>