<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate of Achievement — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/certificate.css') }}">
</head>
<body>

    <div class="print-bar">
        <button class="btn-print btn-print--outline" onclick="history.back()">← Back</button>
        <button class="btn-print btn-print--primary" onclick="window.print()">🖨 Print Certificate</button>
    </div>

    <div class="certificate">
        <div class="cert-frame">
            <div class="cert-frame-inner">

                {{-- Brand --}}
                <div class="cert-brand">
                    <div class="cert-brand-name">{{ config('app.name') }}</div>
                </div>

                <div class="cert-divider">
                    <div class="cert-divider-line"></div>
                    <span class="cert-divider-icon">✦</span>
                    <div class="cert-divider-line"></div>
                </div>

                {{-- Title --}}
                <h1 class="cert-title">Certificate of Achievement</h1>
                <p class="cert-subtitle">MCQ Assessment — Official Recognition</p>

                {{-- Recipient --}}
                <p class="cert-presented">This is to certify that</p>
                <div class="cert-name">{{ $user->name }}</div>
                <p class="cert-body-text">has successfully completed the MCQ assessment</p>
                <div class="cert-test-name">"{{ $testName }}"</div>

                {{-- Stats --}}
                @php
                    $resultValue = $attempt->result_status instanceof \BackedEnum ? $attempt->result_status->value : $attempt->result_status;
                    $dateLabel   = $attempt->submitted_at
                        ? $attempt->submitted_at->format('d M Y')
                        : ($attempt->completed_at ? $attempt->completed_at->format('d M Y') : '—');
                @endphp
                <div class="cert-stats">
                    <div class="cert-stat">
                        <div class="cert-stat-label">Score</div>
                        <div class="cert-stat-value">{{ $attempt->correct_answers }} / {{ $attempt->total_questions }}</div>
                    </div>
                    <div class="cert-stat">
                        <div class="cert-stat-label">Percentage</div>
                        <div class="cert-stat-value">{{ number_format($attempt->percentage, 1) }}%</div>
                    </div>
                    <div class="cert-stat">
                        <div class="cert-stat-label">Result</div>
                        <div class="cert-stat-value {{ $resultValue }}">{{ ucfirst($resultValue ?? '—') }}</div>
                    </div>
                    <div class="cert-stat">
                        <div class="cert-stat-label">Time Spent</div>
                        <div class="cert-stat-value" style="font-size:.85rem">{{ $timeTaken }}</div>
                    </div>
                    <div class="cert-stat">
                        <div class="cert-stat-label">Date</div>
                        <div class="cert-stat-value" style="font-size:.85rem">{{ $dateLabel }}</div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="cert-footer">
                    <div class="cert-footer-block">
                        Issued by
                        <span>{{ config('app.name') }}</span>
                    </div>
                    <div class="cert-seal">
                        <div class="cert-seal-text">
                            Certified<br>Achievement<br>✦
                        </div>
                    </div>
                    <div class="cert-footer-block" style="text-align:right">
                        Date Issued
                        <span>{{ $dateLabel }}</span>
                    </div>
                </div>

                <div class="cert-id">Cert ID: {{ $attempt->uuid }}</div>

            </div>
        </div>
    </div>

</body>
</html>
