<x-mail::message>
<div style="text-align: center; margin-bottom: 25px; border-bottom: 3px double #c9a84c; padding-bottom: 15px;">
  <h1 style="color: #800000; font-family: 'Outfit', 'Inter', sans-serif; font-size: 26px; font-weight: 800; margin: 0; letter-spacing: 1px;">
    MWIGITO EXCEL
  </h1>
  <p style="color: #c9a84c; font-family: 'Outfit', 'Inter', sans-serif; font-size: 11px; font-weight: 600; text-transform: uppercase; margin: 5px 0 0 0; letter-spacing: 2px;">
    Premium Fleet & Bus Management
  </p>
</div>

# New Trip Assignment Confirmed!

Hello {{ $booking->bus->driver->name }},

You have been successfully assigned a new scheduled trip. Please find the details of your assignment below:

<div style="background-color: #fcfbfa; border-top: 4px solid #800000; border-left: 1px solid #e5e7eb; border-right: 1px solid #e5e7eb; border-bottom: 1px solid #e5e7eb; padding: 20px; border-radius: 8px; margin-bottom: 20px; margin-top: 15px;">
  <h3 style="color: #800000; margin-top: 0; font-family: 'Outfit', sans-serif; font-size: 16px; border-bottom: 1px solid #f1ece4; padding-bottom: 10px; margin-bottom: 15px;">
    <strong>Trip Details:</strong>
  </h3>
  <table style="width: 100%; font-size: 14px; border-collapse: collapse; line-height: 1.6;">
    <tr>
      <td style="padding: 4px 0; color: #4b5563; font-weight: 600; width: 35%;">Booking Ref:</td>
      <td style="padding: 4px 0; color: #111827; font-weight: 700;">#{{ 1000 + $booking->id }}</td>
    </tr>
    <tr>
      <td style="padding: 4px 0; color: #4b5563; font-weight: 600;">Pickup Location:</td>
      <td style="padding: 4px 0; color: #111827;">{{ $booking->pickup_location }}</td>
    </tr>
    <tr>
      <td style="padding: 4px 0; color: #4b5563; font-weight: 600;">Destination:</td>
      <td style="padding: 4px 0; color: #111827;">{{ $booking->destination }}</td>
    </tr>
    <tr>
      <td style="padding: 4px 0; color: #4b5563; font-weight: 600;">Date & Time:</td>
      <td style="padding: 4px 0; color: #800000; font-weight: 700;">{{ $booking->date->format('l, M d, Y') }}</td>
    </tr>
    <tr>
      <td style="padding: 4px 0; color: #4b5563; font-weight: 600;">Bus Assigned:</td>
      <td style="padding: 4px 0; color: #111827;">{{ $booking->bus->plate_number }} ({{ $booking->bus->model }})</td>
    </tr>
    <tr>
      <td style="padding: 4px 0; color: #4b5563; font-weight: 600;">Lead Passenger:</td>
      <td style="padding: 4px 0; color: #111827;">{{ $booking->user->name }}</td>
    </tr>
  </table>
</div>

<div style="background-color: #f9fafb; padding: 15px; border-radius: 6px; border-left: 4px solid #c9a84c; margin-bottom: 20px; font-size: 14px; color: #374151;">
  Please ensure you log into your driver dashboard to review full passenger details, check-in schedules, and route coordination.
</div>

<table role="presentation" border="0" cellpadding="0" cellspacing="0" style="box-sizing: border-box; width: 100%; margin-top: 25px; margin-bottom: 25px;">
  <tbody>
    <tr>
      <td align="center">
        <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="width: auto;">
          <tbody>
            <tr>
              <td style="background-color: #800000; border-radius: 6px; text-align: center;">
                <a href="{{ $url }}" target="_blank" style="background-color: #800000; border: solid 1px #800000; border-radius: 6px; color: #ffffff; display: inline-block; font-size: 14px; font-weight: bold; padding: 12px 30px; text-decoration: none; box-shadow: 0 4px 6px rgba(128,0,0,0.2);">Go to Driver Dashboard</a>
              </td>
            </tr>
          </tbody>
        </table>
      </td>
    </tr>
  </tbody>
</table>

Drive safely and have a premium trip experience!<br>
{{ config('app.name') }}
</x-mail::message>
