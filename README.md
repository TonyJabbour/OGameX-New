# OGameX Enhanced

> Modern UI/UX improvements for OGameX browser-based space strategy game

[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)
[![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)](https://laravel.com)

Fork of [OGameX](https://github.com/lanedirt/OGameX) focused on clean, modern interface improvements.

## Quick Start

```powershell
# Start containers
docker compose up -d

# Wait 5-10 minutes for first-time setup

# Access game
http://localhost
```

First account created = automatic admin

## Project Structure

```
OGameX-Enhanced/
â”œâ”€â”€ app/                  # Laravel application
â”œâ”€â”€ resources/views/      # Blade templates (UI)
â”œâ”€â”€ public/              # CSS, JS, images
â”œâ”€â”€ infrastructure/
â”‚   â””â”€â”€ docker/          # Docker configs
â””â”€â”€ .windsurf/rules/     # AI assistant rules
```

## Focus

- Clean, modern UI design
- Responsive (mobile-first)
- Accessibility improvements
- Performance optimizations

## Documentation

- [Development Guide](docs/DEVELOPMENT.md)
- [Original OGameX](docs/original/README-original.md)

## Development

```powershell
# Access app container
docker exec -it ogamex-app bash

# Run tests
docker exec ogamex-app php artisan test

# Clear cache
docker exec ogamex-app php artisan cache:clear
```

## Credits

- Original: [OGameX](https://github.com/lanedirt/OGameX) by @lanedirt
- Game: OGame by GameForge GmbH

## License

MIT - See LICENSE
