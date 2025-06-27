<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Notification Email</title>

    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }

        th, td {
            text-align: center;
            vertical-align: middle;
            padding: 8px;
        }

        th {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #f5f5f5; font-family: Arial, sans-serif;">
    <table align="center" cellpadding="0" cellspacing="0" width="600"
           style="background-color: #ffffff; margin-top: 40px; border-radius: 8px;
                  box-shadow: 0 0 10px rgba(0,0,0,0.1); overflow: hidden;">
        <tr>
            <td colspan="2" style="background-color: #004085; color: #ffffff; padding: 20px 30px; font-size: 20px; font-weight: bold;">
                System Notification
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding: 30px;">
                <p style="font-size: 16px; color: #333333; margin: 0 0 20px 0;">
                    Hello {{ $super_admin }},
                </p>

                @if(is_iterable($collection) && count($collection) > 1)
                    <p style="font-size: 15px; color: #555555; margin: 0 0 20px 0;">
                        This is to inform you that the following records have been <strong>deleted</strong> from the system. Below are the details:
                    </p>

                    <table cellpadding="0" cellspacing="0" width="100%" style="margin-top: 10px; font-size: 14px; color: #333;">
                        <thead>
                            <tr>
                                <th>Model</th>
                                <th>Record ID</th>
                                <th>Record Name</th>
                                <th>Deleted By</th>
                                <th>Deleted At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($collection as $record)
                                <tr>
                                    <td>{{ class_basename($record) }}</td>
                                    <td>{{ $record->id }}</td>
                                    <td>{{ $record->name ?? '—' }}</td>
                                    <td>{{ $record->deletedByUser->name ?? 'System' }}</td>
                                    <td>{{ $record->deleted_at }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    @php
                        $record = is_iterable($collection) ? $collection->first() : $collection;
                    @endphp

                    <p style="font-size: 15px; color: #555555; margin: 0 0 20px 0;">
                        This is to inform you that a record has been <strong>deleted</strong> from the system. Below are the details:
                    </p>

                    <table cellpadding="0" cellspacing="0" width="100%" style="margin-top: 10px; font-size: 14px; color: #333;">
                        <tr>
                            <th>Model</th>
                            <td>{{ class_basename($collection) }}</td>
                        </tr>
                        <tr>
                            <th>Record ID</th>
                            <td>{{ $collection->id }}</td>
                        </tr>
                        <tr>
                            <th>Record Name</th>
                            <td>{{ $collection->name ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Deleted By</th>
                            <td>{{ $collection->deletedByUser->name ?? 'System' }}</td>
                        </tr>
                        <tr>
                            <th>Deleted At</th>
                            <td>{{ $collection->deleted_at }}</td>
                        </tr>
                    </table>
                @endif

                <p style="font-size: 15px; color: #555555; margin-top: 30px;">
                    Please review the deletion for auditing or recovery purposes if necessary.
                </p>

                <p style="font-size: 14px; color: #999999; margin-top: 40px;">
                    — This is an automated message. Please do not reply.
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="background-color: #f1f1f1; text-align: center; padding: 15px; font-size: 13px; color: #777;">
                &copy; {{ date('Y') }} School Management. All rights reserved.
            </td>
        </tr>
    </table>
</body>
</html>
