# email-api
This api provides email count by daily, weekly, monthly or yearly

# For vendor packages
composer update

# Create DB
php bin/console doctrine:database:create

# Migrate
php bin/console doctrine:migrations:migrate

# Run
symfony server:start --port=YOUR_PORT -d
