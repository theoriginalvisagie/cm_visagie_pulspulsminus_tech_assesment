# ++- Laravel Tech Assessment.
## Christiaan Visagie | visagiechristiaan40@gmail.com

# Folder Structure:
There are three folders:
- RESTapi: Contains the code for the api.
- Docker: Contains files for docker container.
- Postman Collection: Contains all the endpoints for postman.

The Docker yaml is contained in `docker-compose.yml`.

Laravel has been configured to work with modules using Nwidart.

All Modules are contained in `RESTapi/Modules`:
- BankAccounts
- BankAccountTransactions
- BankCards
- Users

Additional Info:
- All Unit tests are contained in `RESTapi/Modules/<module-name>/tests/Unit`
- All Routes are contained in `RESTapi/Modules/<module-name>/routes/api.php`
- All Models are contained in `RESTapi/Modules/<module-name>/Entities`
- All Controllers are contained in `RESTapi/Modules/<module-name>/Http/Controllers`
- All Migrations for a module are contained in `RESTapi/Modules/<module-name>/database/migrations`
- All Seeder are contained in `RESTapi/Modules/<module-name>/Http/database/seeders`

# API Documentation:
The documentation for all the endpoints can be found at `http://localhost:8084/api/documentation` once containers are up
and running.

# Setup:
You need to build the docker container before use, make sure you are in the root directory and then run the following commad:
```bash
docker compose up --build
```

Once the container has been built we need to update the `.env` file and run migrations. 

You can copy `.env.exmaple` to `.env`
It is important to not that `DB_HOST=db` needs to be `db`

You will need to enter the container via the terminal to run the migrations.

You can run the following command to list all the containers:
```bash
docker ps
```

We are looking for a container similar to `cm_visagie_plusplusminus_tech_assesment-php82-1`

Once you have found it you can run this command to enter:
```bash
docker exec -it cm_visagie_plusplusminus_tech_assesment-php82-1 bash
```
Once inside the container you can run 
```bash
php artisan migrate.
```

Next we need to run the seeders, you can do so by using this command:
```bash
php artisan db:seed --class="Modules\BankCards\database\seeders\BankCardTypesSeeder"
```

# Seeders
## Bank Cards:
```bash
php artisan db:seed --class="Modules\BankCards\database\seeders\BankCardTypesSeeder"
```

## Transaction Types
```bash
php artisan db:seed --class="Modules\BankAccountTransactions\database\seeders\TransactionsTypeSeeder"
```

# Unit Tests
## Users:
```bash
php artisan test Modules/Users/tests/Unit/UserLoginTest.php
php artisan test Modules/Users/tests/Unit/UserRegistrationTest.php
```

## Bank Cards
```bash
php artisan test Modules/BankCards/tests/Unit/CreateNewDebitCard.php
php artisan test Modules/BankCards/tests/Unit/UpdateCardName.php
php artisan test Modules/BankCards/tests/Unit/DeleteBankCard.php
```

# TODO:
- Use Luhn Algo to generate card details
- Encrypt card details
- Make card name unique
- make combination of card details unique
- Add table for card-type-types i.e. Visa, Master Card etc
- Add table for card_type Groups i.e. Amex etc
- Add "Are you sure" to delete method for bank cards.
- Add 30/60 day period before card gets deleted permanent
- Restrict access via browsers
- Add account number to bank accounts table
- add methods to add funds to bank accounts
- Add check to bank cards that it belongs to a bank account
- Add currency to bank accounts
- Add Validation for when creating a new bank account
- Add check to see if card being used for transaction is valid
- Add location details to transaction types
- Add routes for transactions for specific cards and accounts