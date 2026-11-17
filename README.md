# Reading List API (webdev-week12-group13)

A small token-protected JSON API built with Laravel 12, Sanctum, and PostgreSQL.
Each user keeps a private list of books with a status (to-read, reading, finished)
and an optional rating from 1 to 5.

## Setup

```
composer install
cp .env.example .env        # then set the PostgreSQL DB_ values
php artisan key:generate
php artisan migrate:fresh --seed
php artisan serve
```

Seeded demo users: alice@example.com and bob@example.com
(demo-only seed password defined in UserFactory).

## Endpoints

| Method | URL | Auth | Token ability | Purpose |
|---|---|---|---|---|
| POST | /api/register | none | - | Create an account, returns a token |
| POST | /api/login | none | - | Log in, returns a token |
| POST | /api/logout | token | any | Delete the current token |
| POST | /api/tokens/read-only | token | any | Create a read-only token |
| GET | /api/books | token | books:read | List own books (10 per page) |
| GET | /api/books/{id} | token | books:read | Show one own book |
| POST | /api/books | token | books:write | Create a book |
| PUT | /api/books/{id} | token | books:write | Update an own book |
| DELETE | /api/books/{id} | token | books:write | Delete an own book |

## Questions

**What is the difference between a 401 and a 403?**
A 401 means the server does not know who you are: the token is missing, invalid,
or was logged out. A 403 means the server knows who you are, but this token is not
allowed to do that action, for example a read-only token trying to create a book.

**What does the database store for a token?**
The `personal_access_tokens` table stores one row per token: which user owns it
(`tokenable_type`, `tokenable_id`), a `name`, the `abilities` as JSON, `last_used_at`,
`expires_at`, and the `token` column, which holds a SHA-256 hash, not the plain text.
The plain-text token is shown only once when it is created, so a leaked database
does not leak usable tokens.

**Why use a Resource instead of returning the model?**
A Resource controls exactly which fields leave the API. It keeps `user_id` and any
other internal column hidden, keeps every response in the same shape, and means a
database change does not silently change the public API.