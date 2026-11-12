# Twinfield PHP example

A minimal example of a connection to the Twinfield API with PHP. It logs in once from the command line and lists the offices (companies) you have access to. You do not need a Twinfield client ID of your own.

## Requirements

- PHP 8.3 or newer
- [Composer](https://getcomposer.org/)
- A Twinfield account

## Libraries

| Package | What it does |
|---|---|
| [solvebeam/twinfield](https://php.twinfield.solvebeam.com/) | The Twinfield PHP SDK: offices, customers, transactions and the rest of the Twinfield API. |
| [solvebeam/twinfield-cli](https://github.com/solvebeam/twinfield-cli) | `vendor/bin/twf login` stores your Twinfield login; `Credentials::default()` reads it in PHP. |

Composer installs these packages with them:

- [solvebeam/twinfield-connect](https://github.com/solvebeam/twinfield-connect-php): logs in through Twinfield Connect, so you need no client ID.
- [solvebeam/oauth2-twinfield](https://github.com/solvebeam/twinfield-oauth2-php): the OAuth 2.0 login of Twinfield.
- [Guzzle](https://docs.guzzlephp.org/): the HTTP client.

## Run the example

Install the packages:

```sh
git clone https://github.com/solvebeam/twinfield-php-test.git
cd twinfield-php-test
composer install
```

Log in to Twinfield, once:

```sh
vendor/bin/twf login
```

This opens the Twinfield login in your browser. After you log in, the login is stored in `~/.config/twinfield/credentials.json`; check it with `vendor/bin/twf status`.

Run the example:

```sh
composer example
```

Or without Composer: `php example.php`.

```text
Offices (3):
- 1000 Example B.V.
- 1001 Example Holding B.V.
- 1002 Example Services B.V.
```

## How it works

[example.php](example.php) is all the code there is:

```php
use SolveBeam\Twinfield\Cli\Credentials;
use SolveBeam\Twinfield\Twinfield;

require __DIR__ . '/vendor/autoload.php';

$twinfield = Twinfield::create(Credentials::default());

foreach ($twinfield->offices()->list() as $office) {
    echo $office->code, ' ', $office->name, PHP_EOL;
}
```

- `Credentials::default()` reads the login of `twf login`. It refreshes the access token when it expires and stores the new tokens.
- `Twinfield::create()` creates the client of the SDK.
- `offices()->list()` lists the offices you have access to.

Most other requests are for one office. Choose it with `company()`:

```php
$customer = $twinfield->company('1000')->customers()->get('1000');
```

See the [documentation of the SDK](https://php.twinfield.solvebeam.com/) for everything you can do.

## More than one organisation

Use a profile per organisation:

```sh
vendor/bin/twf login --profile=acme
```

```php
$twinfield = Twinfield::create(Credentials::default('acme'));
```

## CI

In CI you cannot log in with a browser. Set `TWINFIELD_REFRESH_TOKEN`, `TWINFIELD_CONNECT_CLIENT_ID` and `TWINFIELD_CONNECT_CLIENT_SECRET` instead, with the values from `~/.config/twinfield/credentials.json`. See [twinfield-cli](https://github.com/solvebeam/twinfield-cli#ci-and-environment-variables).

## In your own application

This example is for scripts you run yourself. In an application where users log in to Twinfield themselves, use [solvebeam/twinfield-connect](https://github.com/solvebeam/twinfield-connect-php) (or your own client ID with [solvebeam/oauth2-twinfield](https://github.com/solvebeam/twinfield-oauth2-php)) and store the refresh token of each user yourself.

## Code style

```sh
composer phpcs
```

Checks the code against PSR-12.
