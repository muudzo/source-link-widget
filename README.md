# Source Link Widget

Never forget where your links came from! This browser extension and web application helps you save, organize, and manage your browsing sources.

## Features

- 🚀 **Browser Extension**: Save links directly from any webpage
- 📁 **Categories**: Organize your links with custom categories
- 🔍 **Search & Filter**: Find your saved links quickly
- 🎨 **Modern UI**: Clean, responsive interface
- 💾 **Local Storage**: All data stored in your Laravel application

## Installation

### 1. Laravel Application Setup

1. **Install dependencies:**
   ```bash
   composer install
   npm install
   ```

2. **Set up environment:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Configure database:**
   - Update your `.env` file with database credentials
   - Or use SQLite (already configured): `touch database/database.sqlite`

4. **Run migrations:**
   ```bash
   php artisan migrate
   ```

5. **Start the server:**
   ```bash
   php artisan serve
   ```

### 2. Browser Extension Setup

1. **Open Chrome/Edge:**
   - Go to `chrome://extensions/` (Chrome) or `edge://extensions/` (Edge)
   - Enable "Developer mode"

2. **Load the extension:**
   - Click "Load unpacked"
   - Select the `browser-extension` folder from this project

3. **Configure the extension:**
   - Update the `API_BASE_URL` in `browser-extension/popup.js` if your server runs on a different port
   - The default is `http://localhost:8000`

## Usage

### Browser Extension

1. **Save a link:**
   - Click the extension icon in your browser toolbar
   - The current page title and URL will be automatically filled
   - Add a description and select a category (optional)
   - Click "Save Link"

2. **View all links:**
   - Click "View All Links" in the extension popup
   - This opens the web interface

### Web Interface

1. **Access the interface:**
   - Visit `http://localhost:8000/links`
   - Or click "View All Links" in the extension

2. **Manage links:**
   - Search and filter your saved links
   - Edit or delete existing links
   - Create new categories

3. **Categories:**
   - Visit `http://localhost:8000/categories`
   - Create categories to organize your links

## API Endpoints

The application provides REST API endpoints for the browser extension:

- `GET /api/categories` - List all categories
- `POST /api/categories` - Create a new category
- `GET /api/links` - List all links
- `POST /api/links` - Create a new link
- `GET /api/links/{id}` - Get a specific link
- `PUT /api/links/{id}` - Update a link
- `DELETE /api/links/{id}` - Delete a link

## File Structure

```
source-link-widget/
├── app/
│   ├── Http/Controllers/
│   │   ├── LinkController.php
│   │   └── LinkCategoryController.php
│   └── Models/
│       ├── Link.php
│       └── LinkCategory.php
├── browser-extension/
│   ├── manifest.json
│   ├── popup.html
│   ├── popup.css
│   ├── popup.js
│   ├── content.js
│   ├── background.js
│   └── setup.html
├── database/migrations/
│   └── 2025_04_15_125127_create_link_tables.php
├── resources/views/links/
│   ├── index.blade.php
│   ├── create.blade.php
│   ├── edit.blade.php
│   └── show.blade.php
└── routes/web.php
```

## Development

### Adding New Features

1. **Backend (Laravel):**
   - Add new routes in `routes/web.php`
   - Create controllers and models as needed
   - Update database migrations

2. **Frontend (Browser Extension):**
   - Modify `popup.js` for extension functionality
   - Update `popup.html` and `popup.css` for UI changes
   - Use `content.js` for page interaction features

3. **Web Interface:**
   - Create new Blade templates in `resources/views/`
   - Add JavaScript for interactive features

### Database Schema

**Links Table:**
- `id` - Primary key
- `title` - Link title
- `url` - Link URL
- `description` - Optional description
- `favicon` - Favicon URL
- `is_active` - Active status
- `created_at`, `updated_at` - Timestamps

**Categories Table:**
- `id` - Primary key
- `name` - Category name
- `slug` - URL-friendly name
- `color` - Category color (optional)
- `description` - Category description
- `created_at`, `updated_at` - Timestamps

**Link-Category Pivot Table:**
- `link_id` - Foreign key to links
- `link_category_id` - Foreign key to categories

## Troubleshooting

### Extension Not Working

1. **Check server connection:**
   - Ensure Laravel server is running on `http://localhost:8000`
   - Test API endpoints in browser

2. **Check extension permissions:**
   - Verify manifest.json permissions
   - Check browser console for errors

3. **CORS issues:**
   - Add CORS headers to Laravel if needed
   - Check browser network tab for failed requests

### Database Issues

1. **Migration errors:**
   ```bash
   php artisan migrate:reset
   php artisan migrate
   ```

2. **SQLite issues:**
   - Ensure `database/database.sqlite` file exists
   - Check file permissions

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).