## About Homeful Contacts

## Usage
### Registration
#### Normal
https://contacts.homeful.ph/register
#### w/ callback
https://contacts.homeful.ph/register?callback=https://google.com
#### show GMI and Birthdate field
https://contacts.homeful.ph/register?showExtra=true
#### hide Password fields
https://contacts.homeful.ph/register?showGMI=true
#### w/ callback and show GMI and hidePassword
https://contacts.homeful.ph/register?callback=https://google.com&showExtra=true&hidePassword=true
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
#### edit default values in .env
CALLBACK=https://google.com\
SHOW_GMI=TRUE

## API
### Register Contact
POST http://contacts.homeful.ph/api/register

sample json payload<p>
`{
    "name": "Mary Cruz",
    "email": "mary.cruz@gmail.com",
    "mobile": "09171234567",
    "password": "#Password1",
    "password_confirmation": "#Password1",
    "middle_name": "Rose",
    "civil_status": "Single",
    "sex": "Female",
    "nationality": "Filipino",
    "date_of_birth": "1999-03-17",
    "monthly_gross_income": 53700
}`

### Get Reference JSON
GET https://contacts.homeful.ph/api/references/{reference}

### Get Contact JSON
GET https://contacts.homeful.ph/api/contacts/{mobile}

### RedeemSellerVoucherCode
Lester, please continue this

### Restore a backup
#### Upload the file
`scp contacts.csv forge@contacts.homeful.ph:/home/forge/contacts.homeful.ph/current/storage/app/private/`
#### Import a file
`php artisan app:import-users`
#### Run address migrations
`php artisan migrate:fresh --database=address-sqlite --path=database/migrations/address`
#### Run address seeds
`php artisan db:seed --class=CountrySeeder`
`php artisan db:seed --class=PhilippineStandardGeographicalCodeSeeder`

## Security Vulnerabilities

If you discover a security vulnerability within Homeful Contacts, please send an e-mail to Anaïs Santos via [devops@joy-nostalg.com](mailto:devops@joy-nostalg.com). All security vulnerabilities will be promptly addressed.
