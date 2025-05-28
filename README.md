# Gemini Chatbot

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![PHP Version](https://img.shields.io/badge/PHP-8.1%2B-blue.svg)](https://php.net/)
[![Laravel Version](https://img.shields.io/badge/Laravel-10%2B-red.svg)](https://laravel.com)

A chatbot implementation using Google's Gemini API with Laravel.

![Chatbot Screenshot](screenshot.png)

## Features

- Real-time chat interface
- Session-based conversation history
- Gemini API integration
- Responsive UI
- Error handling

## Requirements

- PHP 8.1+
- Composer
- Laravel 10+
- Google Gemini API key

## Installation

1. Clone repository:
   ```bash
   git clone https://github.com/shuliaka95/gemini-chatbot.git
   cd gemini-chatbot
   ```

2. Install dependencies:
   ```bash
   composer install
   ```

3. Configure environment:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Add Gemini API key to `.env`:
   ```env
   GEMINI_API_KEY=your_api_key_here
   ```

5. Start development server:
   ```bash
   php artisan serve
   ```

6. Visit `http://localhost:8000` in your browser

## Project Structure

```
├── app/
│   └── Http/         # HTTP controllers
├── config/           # Configuration files
├── public/           # Web root
├── resources/
│   └── views/        # Blade templates
├── routes/           # Application routes
├── storage/          # Storage for logs, cache, etc.
└── tests/            # Automated tests
```

## Environment Variables

| Variable         | Description                     |
|------------------|---------------------------------|
| GEMINI_API_KEY   | Google Gemini API key           |
| APP_ENV          | Application environment         |
| APP_DEBUG        | Debug mode (true/false)         |
| SESSION_DRIVER   | Session driver (cookie)         |

## Contributing

1. Fork the project
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a pull request

## License

This project is open-source software licensed under the [MIT license](LICENSE).
