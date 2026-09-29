@php
    /** @var \App\Models\School $school */
    /** @var string $type */
    $activityLabel = match ($type) {
        'registered' => 'Self-Registered',
        'updated' => 'Updated',
        default => 'Created',
    };
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SKOOLYST School Activity</title>
</head>
<body style="font-family: Arial, sans-serif; background-color:#f4f4f5; padding:24px;">
<table width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;margin:0 auto;background:#ffffff;border-radius:8px;overflow:hidden;">
    <tr>
        <td style="background:#0f4077;color:#ffffff;padding:16px 20px;">
            <h2 style="margin:0;font-size:20px;">
                @if($type === 'registered')
                    New School Self-Registration
                @elseif($type === 'updated')
                    School Profile Updated
                @else
                    New School Created
                @endif
            </h2>
        </td>
    </tr>
    <tr>
        <td style="padding:20px;color:#111827;font-size:14px;line-height:1.6;">
            <p style="margin-top:0;margin-bottom:12px;">
                Hello Admin (skoolyst@gmail.com),
            </p>

            @if($type === 'registered')
                <p style="margin:0 0 12px 0;">
                    A school has just <strong>self-registered</strong> on SKOOLYST via the public sign-up form.
                </p>
            @elseif($type === 'updated')
                <p style="margin:0 0 12px 0;">
                    A school's profile has just been <strong>updated</strong> from the admin dashboard.
                </p>
            @else
                <p style="margin:0 0 12px 0;">
                    A new school has just been <strong>created</strong> from the admin dashboard.
                </p>
            @endif

            <p style="margin:0 0 12px 0;"><strong>School details:</strong></p>
            <ul style="margin:0 0 16px 18px;padding:0;">
                <li><strong>Name:</strong> {{ $school->name ?? 'N/A' }}</li>
                <li><strong>Email:</strong> {{ $school->email ?? 'N/A' }}</li>
                <li><strong>City:</strong> {{ $school->city ?? 'N/A' }}</li>
                <li><strong>Status:</strong> {{ $school->status ?? 'N/A' }}</li>
                <li><strong>School ID:</strong> {{ $school->id ?? 'N/A' }}</li>
            </ul>

            <p style="margin:0 0 8px 0;">
                <strong>Activity:</strong> {{ $activityLabel }}
            </p>

            <p style="margin:0 0 4px 0;">
                <strong>Time:</strong> {{ now()->format('Y-m-d H:i:s') }}
            </p>
        </td>
    </tr>
    <tr>
        <td style="padding:16px 20px;border-top:1px solid #e5e7eb;font-size:12px;color:#6b7280;">
            This is an automatic notification from the SKOOLYST platform.
        </td>
    </tr>
</table>
</body>
</html>
