# Junior Laravel Developer Code Test — Tool Checkout

This repo is a **self-contained** Laravel code challenge that runs with **Docker only**.

- No local PHP/Composer/MySQL/Laravel install needed
- Uses SQLite in-container
- Comes with **failing tests**; your job is to make them pass

## Quick start

### Requirements
- Docker Desktop
- Git

### Run
```bash
git clone <repo-url>
cd <repo-folder>

docker compose up --build -d

docker compose exec app php artisan test
```

(Optional) Run the dev server on your machine at: http://localhost:8080

## The assignment

You will implement a small Tool Checkout feature set.

### Data model
- `tools` has a `status` (`available`, `checked_out`, `maintenance`)
- `tool_checkouts` tracks checkouts; a record is *currently checked out* when `checked_in_at` is `NULL`

### Tasks
1) Create a controller `ToolCheckoutController` with methods to:
   - Fetch all tools currently checked out
   - Check in a tool (set `checked_in_at` and set the related tool status to `available`)
   - Validate that a tool checkout is actually active (not already checked in)

2) Implement a local Eloquent scope `ToolCheckout::currentlyCheckedOut()`

3) Create an Artisan command `tools:status-counts` that outputs counts of tools by status:
   - Available
   - Checked Out
   - Maintenance

### API routes (already defined)
- `GET /api/tool-checkouts/current`
- `PATCH /api/tool-checkouts/{toolCheckout}/check-in`

### What we look for
- Clean, readable Laravel code
- Correctness (tests passing)
- Sensible structure (Request classes, Resources, etc. are welcome)

## Submission
- Provide a GitHub repo link OR a zip of your completed work
- Include a short `NOTES.md` (5–10 lines): approach, tradeoffs, what you’d improve with more time

## Helpful commands
- Run tests: `docker compose exec app php artisan test`
- Format check (optional): `docker compose exec app php -l <file>`
- Tail logs: `docker compose logs -f`

