<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate of Achievement — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --navy:  #1a2e5a;
            --gold:  #c9a84c;
            --gold-light: #e8d08a;
            --white: #ffffff;
            --gray:  #64748b;
            --light: #f8fafc;
        }

        body {
            font-family: 'Lato', sans-serif;
            background: #e8eaf0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding: 2rem 1rem 3rem;
            color: var(--navy);
        }

        /* ── Print button ── */
        .print-bar {
            width: 100%;
            max-width: 860px;
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
            margin-bottom: 1.25rem;
        }
        .btn-print {
            font-family: 'Lato', sans-serif;
            font-size: 0.875rem;
            font-weight: 700;
            padding: 0.55rem 1.4rem;
            border-radius: 6px;
            cursor: pointer;
            border: none;
            transition: opacity .15s;
        }
        .btn-print:hover { opacity: .85; }
        .btn-print--primary { background: var(--navy); color: var(--white); }
        .btn-print--outline { background: transparent; color: var(--navy); border: 2px solid var(--navy); }

        /* ── Certificate card ── */
        .certificate {
            width: 100%;
            max-width: 860px;
            background: var(--white);
            padding: 3px;
            border-radius: 6px;
            box-shadow: 0 12px 48px rgba(26,46,90,.18);
            position: relative;
        }

        /* Triple border frame */
        .cert-frame {
            border: 4px solid var(--navy);
            border-radius: 4px;
            padding: 3px;
        }
        .cert-frame-inner {
            border: 2px solid var(--gold);
            border-radius: 2px;
            padding: 48px 56px 40px;
            position: relative;
            overflow: hidden;
        }

        /* Corner ornaments */
        .cert-frame-inner::before,
        .cert-frame-inner::after {
            content: '✦';
            font-size: 1.5rem;
            color: var(--gold);
            position: absolute;
            line-height: 1;
        }
        .cert-frame-inner::before { top: 10px; left: 14px; }
        .cert-frame-inner::after  { bottom: 10px; right: 14px; }

        /* ── Header ── */
        .cert-brand {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .cert-brand-name {
            font-family: 'Cinzel', serif;
            font-size: 1.05rem;
            font-weight: 600;
            letter-spacing: .25em;
            color: var(--gold);
            text-transform: uppercase;
        }

        .cert-divider {
            display: flex;
            align-items: center;
            gap: .75rem;
            margin: 0 auto 1.75rem;
            max-width: 480px;
        }
        .cert-divider-line {
            flex: 1;
            height: 1px;
            background: linear-gradient(to right, transparent, var(--gold), transparent);
        }
        .cert-divider-icon {
            color: var(--gold);
            font-size: 1.1rem;
        }

        .cert-title {
            font-family: 'Cinzel', serif;
            font-size: 2.25rem;
            font-weight: 700;
            color: var(--navy);
            text-align: center;
            letter-spacing: .04em;
            line-height: 1.2;
            margin-bottom: .5rem;
        }

        .cert-subtitle {
            font-family: 'Lato', sans-serif;
            font-size: 0.85rem;
            font-weight: 300;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: var(--gray);
            text-align: center;
            margin-bottom: 2.25rem;
        }

        /* ── Body ── */
        .cert-presented {
            text-align: center;
            font-size: 1rem;
            color: var(--gray);
            font-weight: 300;
            margin-bottom: .45rem;
            letter-spacing: .05em;
        }

        .cert-name {
            font-family: 'Cinzel', serif;
            font-size: 2rem;
            font-weight: 600;
            color: var(--navy);
            text-align: center;
            margin-bottom: 1.25rem;
            border-bottom: 2px solid var(--gold-light);
            padding-bottom: .75rem;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        .cert-body-text {
            text-align: center;
            font-size: 1rem;
            font-weight: 300;
            color: var(--gray);
            line-height: 1.7;
            margin-bottom: .35rem;
        }

        .cert-test-name {
            font-family: 'Lato', sans-serif;
            font-size: 1.15rem;
            font-weight: 700;
            font-style: italic;
            color: var(--navy);
            text-align: center;
            margin-bottom: 2rem;
        }

        /* ── Stats row ── */
        .cert-stats {
            display: flex;
            justify-content: center;
            gap: 0;
            margin: 0 auto 2rem;
            max-width: 640px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
        }
        .cert-stat {
            flex: 1;
            padding: .9rem .5rem;
            text-align: center;
            border-right: 1px solid #e2e8f0;
        }
        .cert-stat:last-child { border-right: none; }
        .cert-stat-label {
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: .1em;
            color: var(--gray);
            margin-bottom: .25rem;
        }
        .cert-stat-value {
            font-size: 1rem;
            font-weight: 700;
            color: var(--navy);
            line-height: 1.2;
        }
        .cert-stat-value.passed { color: #15803d; }
        .cert-stat-value.failed { color: #dc2626; }

        /* ── Footer ── */
        .cert-footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 2rem;
            padding-top: 1.25rem;
            border-top: 1px dashed #e2e8f0;
        }
        .cert-footer-block { font-size: 0.75rem; color: var(--gray); }
        .cert-footer-block span { display: block; font-weight: 700; color: var(--navy); margin-top: .15rem; font-size: .8rem; }
        .cert-seal {
            width: 68px;
            height: 68px;
            border-radius: 50%;
            border: 3px solid var(--gold);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            background: var(--white);
            box-shadow: 0 0 0 2px var(--gold-light);
        }
        .cert-seal-text {
            font-family: 'Cinzel', serif;
            font-size: .45rem;
            letter-spacing: .06em;
            text-transform: uppercase;
            color: var(--gold);
            text-align: center;
            font-weight: 700;
            line-height: 1.3;
        }
        .cert-id {
            font-size: 0.65rem;
            color: #94a3b8;
            text-align: center;
            margin-top: 1.25rem;
            letter-spacing: .05em;
        }

        /* ── Print styles ── */
        @media print {
            body {
                background: white;
                padding: 0;
                display: block;
            }
            .print-bar { display: none !important; }
            .certificate {
                max-width: 100%;
                box-shadow: none;
                border-radius: 0;
            }
            .cert-frame-inner { padding: 36px 44px 32px; }
            .cert-title { font-size: 1.9rem; }
            .cert-name  { font-size: 1.7rem; }
        }

        @media (max-width: 600px) {
            .cert-frame-inner { padding: 28px 20px 24px; }
            .cert-title { font-size: 1.5rem; }
            .cert-name  { font-size: 1.35rem; }
            .cert-stats { flex-direction: column; }
            .cert-stat  { border-right: none; border-bottom: 1px solid #e2e8f0; }
            .cert-stat:last-child { border-bottom: none; }
            .cert-footer { flex-direction: column; align-items: center; gap: 1rem; text-align: center; }
        }
    </style>
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
