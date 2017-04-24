# REST API for Macis CRM 

This is the only entrypoint between database and the outside world

## Usage

### Database

Database model is a .mwb designed to be used with mysql workbench, please create the database and import the model before anything else

### User creation

You must create users manually into the database

$user = "user";

$hash = password_hash("password", PASSWORD_DEFAULT);

$status = $pdo->exec(
    "INSERT INTO users (user, password) VALUES ('{$user}', '{$hash}')"
);

### Calls

You must use the basic http authorization to authenticate with user and password with every request

