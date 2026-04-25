@php
    /** @var \App\Models\ContactInquiry $inquiry */
    $school = $inquiry->school;
    $branch = $inquiry->branch;
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Contact Inquiry</title>
</head>
<body style="font-family: Arial, sans-serif; background-color:#f4f4f5; padding:24px; margin:0;">
<table width="100%" cellpadding="0" cellspacing="0" style="max-width:640px;margin:0 auto;background:#ffffff;border-radius:8px;overflow:hidden;">
    <tr>
        <td style="background:#0f4077;color:#ffffff;padding:18px 24px;">
            <h2 style="margin:0;font-size:20px;">New Contact Inquiry</h2>
            <p style="margin:6px 0 0 0;font-size:13px;opacity:0.85;">
                {{ $school->name ?? 'Skoolyst' }}
            </p>
        </td>
    </tr>
    <tr>
        <td style="padding:24px;color:#111827;font-size:14px;line-height:1.6;">
            <p style="margin-top:0;margin-bottom:16px;">
                Hello {{ $school->name ?? 'Team' }},
            </p>
            <p style="margin:0 0 16px 0;">
                You received a new contact inquiry from your Skoolyst school profile.
            </p>

            <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;margin-bottom:20px;">
                <tr>
                    <td style="padding:8px 0;color:#6b7280;width:140px;">Subject</td>
                    <td style="padding:8px 0;color:#111827;font-weight:600;">{{ $inquiry->full_subject }}</td>
                </tr>
                <tr>
                    <td style="padding:8px 0;color:#6b7280;">Name</td>
                    <td style="padding:8px 0;color:#111827;">{{ $inquiry->name }}</td>
                </tr>
                <tr>
                    <td style="padding:8px 0;color:#6b7280;">Email</td>
                    <td style="padding:8px 0;color:#111827;">
                        <a href="mailto:{{ $inquiry->email }}" style="color:#0f4077;text-decoration:none;">
                            {{ $inquiry->email }}
                        </a>
                    </td>
                </tr>
                @if($inquiry->phone)
                <tr>
                    <td style="padding:8px 0;color:#6b7280;">Phone</td>
                    <td style="padding:8px 0;color:#111827;">
                        <a href="tel:{{ $inquiry->phone }}" style="color:#0f4077;text-decoration:none;">
                            {{ $inquiry->phone }}
                        </a>
                    </td>
                </tr>
                @endif
                @if($branch)
                <tr>
                    <td style="padding:8px 0;color:#6b7280;">Branch</td>
                    <td style="padding:8px 0;color:#111827;">{{ $branch->name }}</td>
                </tr>
                @endif
                <tr>
                    <td style="padding:8px 0;color:#6b7280;">Submitted</td>
                    <td style="padding:8px 0;color:#111827;">{{ $inquiry->created_at?->format('Y-m-d H:i') ?? now()->format('Y-m-d H:i') }}</td>
                </tr>
            </table>

            <p style="margin:0 0 6px 0;color:#6b7280;font-size:13px;">Message</p>
            <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:6px;padding:14px 16px;white-space:pre-wrap;color:#111827;">{{ $inquiry->message }}</div>

            <p style="margin:24px 0 0 0;font-size:13px;color:#6b7280;">
                Reply directly to this email to respond to {{ $inquiry->name }}.
            </p>
        </td>
    </tr>
    <tr>
        <td style="padding:16px 24px;border-top:1px solid #e5e7eb;font-size:12px;color:#6b7280;">
            Automatic notification from the SKOOLYST platform.
        </td>
    </tr>
</table>
</body>
</html>
