## About Homeful Contacts

## Usage

### Registration
#### Normal
https://contacts.homeful.ph/register
#### w/ callback
https://contacts.homeful.ph/register?callback=https://google.com
#### show GMI field
https://contacts.homeful.ph/register?showGMI=true
#### w/ callback and show GMI
https://contacts.homeful.ph/register?callback=https://google.com&showGMI=true
#### via API
`curl --location 'https://contacts.homeful.ph/register' \
--header 'Accept: application/json' \
--header 'Content-Type: application/json' \
--data-raw '{
"name": "Lester Hurtado",
"email": "lester@hurtado.ph",
"mobile": "09171234567",
"password": "#Weetypie1",
"password_confirmation": "#Weetypie1",
"middle_name": "Biadora",
"civil_status": "Married",
"sex": "Male",
"nationality": "Filipino",
"date_of_birth": "1970-04-21",
"monthly_gross_income": 0
}'`

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Anaïs Santos via [devops@joy-nostalg.com](mailto:devops@joy-nostalg.com). All security vulnerabilities will be promptly addressed.
