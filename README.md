# Backend Banking System

This application provides a simple user banking API.

## Prerequisites:

- [Composer](https://getcomposer.org/download/)
- [PHP](https://www.php.net/manual/en/install.php)
- [Docker & Docker Compose](https://docs.docker.com/get-docker/)
- [Git](https://git-scm.com/downloads)

## Installation:

### Clone the repository:

```bash
git clone https://github.com/Xarlie-Xarlie/transfeera.git
```

```bash
cd transfeera
```

### Install the dependencies:

```bash
composer install --ignore-platform-reqs
```

### Run your containers (this step may take some time to complete):

```bash
./vendor/bin/sail up -d
```

### Set up the database and run migrations:

```bash
./vendor/bin/sail artisan migrate
```

### Seed the database:

```bash
./vendor/bin/sail artisan db:seed
```

### Generate the API documentation:

```bash
./vendor/bin/sail artisan l5-swagger:generate
```

You can now access the API documentation in your browser at:

`http://localhost/api/documentation#/default`

### Interact with the API:

You can use tools such as `curl`, `Postman`, `Insomnia`, or others to make requests to the API.

### Running Automated Tests:

This project includes some unit and integration tests. You can run them with:

```bash
./vendor/bin/sail artisan test
```

## Notes:

1. Ensure that Docker is running before executing the Sail commands.
2. Adjust the `.env` file if needed to match your local environment configuration.
3. For detailed API usage, refer to the generated Swagger documentation.

## Contribution:

Feel free to fork this repository and contribute by submitting pull requests. For major changes, please open an issue first to discuss what you would like to change.
