@php
    /** @var \App\Models\ContactInquiry $inquiry */
    $school = $inquiry->school;
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your inquiry was received</title>
</head>
<body style="font-family: Arial, sans-serif; background-color:#f4f4f5; padding:24px; margin:0;">
<table width="100%" cellpadding="0" cellspacing="0" style="max-width:640px;margin:0 auto;background:#ffffff;border-radius:8px;overflow:hidden;">
    <tr>
        <td style="background:#0f4077;color:#ffffff;padding:18px 24px;">
            <h2 style="margin:0;font-size:20px;">We received your inquiry</h2>
            <p style="margin:6px 0 0 0;font-size:13px;opacity:0.85;">
                {{ $school->name ?? 'Skoolyst' }}
            </p>
        </td>
    </tr>
    <tr>
        <td style="padding:24px;color:#111827;font-size:14px;line-height:1.6;">
            <p style="margin-top:0;margin-bottom:14px;">
                Hi {{ $inquiry->name }},
            </p>
            <p style="margin:0 0 14px 0;">
                Thanks for contacting <strong>{{ $school->name ?? 'the school' }}</strong> through Skoolyst.
                The school has been notified and will reply to you at
                <a href="mailto:{{ $inquiry->email }}" style="color:#0f4077;">{{ $inquiry->email }}</a>
                as soon as possible.
            </p>

            <p style="margin:18px 0 6px 0;color:#6b7280;font-size:13px;">Summary of your message</p>
            <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;margin-bottom:14px;">
                <tr>
                    <td style="padding:6px 0;color:#6b7280;width:120px;">Subject</td>
                    <td style="padding:6px 0;color:#111827;">{{ $inquiry->full_subject }}</td>
                </tr>
                @if($inquiry->phone)
                <tr>
                    <td style="padding:6px 0;color:#6b7280;">Phone</td>
                    <td style="padding:6px 0;color:#111827;">{{ $inquiry->phone }}</td>
                </tr>
                @endif
                <tr>
                    <td style="padding:6px 0;color:#6b7280;">Submitted</td>
                    <td style="padding:6px 0;color:#111827;">{{ $inquiry->created_at?->format('Y-m-d H:i') ?? now()->format('Y-m-d H:i') }}</td>
                </tr>
            </table>

            <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:6px;padding:14px 16px;white-space:pre-wrap;color:#111827;">{{ $inquiry->message }}</div>

            <p style="margin:22px 0 0 0;font-size:13px;color:#6b7280;">
                If you didn't submit this inquiry, you can safely ignore this email.
            </p>
        </td>
    </tr>
    <tr>
        <td style="padding:16px 24px;border-top:1px solid #e5e7eb;font-size:12px;color:#6b7280;">
            You received this email because an inquiry was submitted from SKOOLYST using this address.
        </td>
    </tr>
</table>
</body>
</html>
