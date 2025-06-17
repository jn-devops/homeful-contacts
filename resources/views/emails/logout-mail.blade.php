<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Email</title>
  <style>
    @media only screen and (max-width: 600px) {
      .container {
        width: 100% !important;
        padding: 16px !important;
      }
      .button {
        width: 100% !important;
      }
      .text-center-mobile {
        text-align: center !important;
      }
    }
  </style>
</head>
<body style="margin: 0; padding: 0; background-color: #f3f4f6; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">

  <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f3f4f6;">
    <tr>
      <td align="center" style="padding: 40px 20px;">
        <table class="container" width="100%" style="max-width: 480px; background-color: #ffffff; border-radius: 16px; padding: 32px; box-shadow: 0 4px 12px rgba(0,0,0,0.06);">

          <!-- Greeting -->
          <tr>
            <td class="text-center-mobile" style="font-size: 22px; color: #111827; font-weight: bold; text-align: left; padding-bottom: 24px;">
              👋 Hello Juan,
            </td>
          </tr>

          <!-- Step 1 -->
          <tr>
            <td style="padding-bottom: 16px;">
              <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="32" valign="top" style="padding-right: 12px;">
                    <img src="https://fonts.gstatic.com/s/i/materialicons/schedule/v1/24px.svg" width="24" height="24" alt="Deadline" style="display: block;">
                  </td>
                  <td style="font-size: 15px; color: #374151; line-height: 1.5;">
                    <strong>Please complete within 7 days.</strong><br>
                    Ensure all required fields are filled. Not completing within 7 days will cancel your application.
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <!-- Step 2 -->
          <tr>
            <td style="padding-bottom: 24px;">
              <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="32" valign="top" style="padding-right: 12px;">
                    <img src="https://fonts.gstatic.com/s/i/materialicons/assignment_turned_in/v1/24px.svg" width="24" height="24" alt="Form" style="display: block;">
                  </td>
                  <td style="font-size: 15px; color: #374151; line-height: 1.5;">
                    Complete your Customer Information Form and upload the Required Documents. Use your credentials below to log in.
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <!-- CTA instruction -->
          <tr>
            <td style="text-align: center; font-size: 14px; color: #6b7280; padding-bottom: 16px;">
              Click the button below to access your account:
            </td>
          </tr>

          <!-- CTA Button -->
          <tr>
            <td align="center" style="padding-bottom: 32px;">
              <a href="{{$link}}" class="button" style="background-color: #1f2937; color: #ffffff; padding: 14px 28px;  font-weight: 600; font-size: 16px; text-decoration: none; display: inline-block;">
                Proceed to Log-In
              </a>
            </td>
          </tr>

          <!-- Credentials -->
          <tr>
            <td style="background-color: #f9fafb; border-radius: 12px; padding: 20px; font-size: 14px; color: #111827;">
              <p style="margin: 0 0 8px;"><strong>Account Details:</strong></p>
              <p style="margin: 0 0 4px;">Email: <span style="background-color: #e5e7eb; padding: 2px 4px;">{{$email}}</span></p>
              <p style="margin: 0;">Homeful ID: <strong>{{$homeful_id}}</strong></p>
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td style="text-align: center; font-size: 14px; color: #6b7280; padding-top: 32px;">
              Thank you,<br>
              <strong>Homeful</strong>
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>

</body>
</html>
