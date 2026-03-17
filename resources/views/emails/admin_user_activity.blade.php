@php
    /** @var \App\Models\User $user */
    /** @var string $type */
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SKOOLYST User Activity</title>
</head>
<body style="font-family: Arial, sans-serif; background-color:#f4f4f5; padding:24px;">
<table width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;margin:0 auto;background:#ffffff;border-radius:8px;overflow:hidden;">
    <tr>
        <td style="background:#0f4077;color:#ffffff;padding:16px 20px;">
            <h2 style="margin:0;font-size:20px;">
                @if($type === 'registered')
                    New User Registration
                @else
                    User Login Notification
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
                    A new user has just <strong>registered</strong> on SKOOLYST.
                </p>
            @else
                <p style="margin:0 0 12px 0;">
                    A user has just <strong>logged in</strong> to SKOOLYST.
                </p>
            @endif

            <p style="margin:0 0 12px 0;"><strong>User details:</strong></p>
            <ul style="margin:0 0 16px 18px;padding:0;">
                <li><strong>Name:</strong> {{ $user->name ?? 'N/A' }}</li>
                <li><strong>Email:</strong> {{ $user->email ?? 'N/A' }}</li>
                <li><strong>User ID:</strong> {{ $user->id ?? 'N/A' }}</li>
            </ul>

            <p style="margin:0 0 8px 0;">
                <strong>Activity:</strong>
                @if($type === 'registered')
                    New Registration
                @else
                    Login
                @endif
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