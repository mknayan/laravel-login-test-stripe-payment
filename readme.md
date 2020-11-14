## Configure project

To run the projects, we have to do the following

- Copy .env.example file as .env
- Generate a new app key by running
### `php artisan key:generate`
- Configure database connection
- Configure STRIPE_KEY and STRIPE_SECRET
- To run the scheduler, add the following Cron
### `* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1`

## Completed Tasks
- Login bug fixed
- Registration bug fixed
- Stripe payment integration and payment
- Monthly payment report of every user account with filtering
- Login attempt restrictions
- Cron scheduler to deactivate user after a month payment
