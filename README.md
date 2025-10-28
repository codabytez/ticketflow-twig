# TicketFlow Twig - Ticket Management System

A modern, responsive ticket management web application built with PHP, Twig templating engine, and Tailwind CSS.

## 🚀 Features

### Core Functionality

- **Landing Page**: Beautiful hero section with wavy SVG background and decorative elements
- **Authentication**: Secure login and signup with server-side validation and session management
- **Dashboard**: Overview with ticket statistics and quick actions
- **Ticket Management**: Full CRUD operations (Create, Read, Update, Delete)

### Technical Features

- ✅ PHP 7.4+ with Twig 3.0 templating
- ✅ Tailwind CSS CDN for styling
- ✅ Responsive design (mobile, tablet, desktop)
- ✅ Server-side form validation with inline error messages
- ✅ Toast notifications for user feedback
- ✅ JSON file storage for tickets
- ✅ PHP Session management for authentication
- ✅ Protected routes with authentication checks

## 📋 Requirements Met

- **Max-width**: 1440px centered layout on all pages
- **Wave Background**: SVG wave in hero section
- **Decorative Circles**: Multiple circular blur elements
- **Status Colors**:
  - Open → Green (`bg-green-500`)
  - In Progress → Amber (`bg-amber-500`)
  - Closed → Gray (`bg-gray-500`)
- **Authentication**: Session stored as `ticketapp_session` in PHP $\_SESSION
- **Validation**: Title and status fields are mandatory
- **Error Handling**: Consistent server-side validation and toast notifications

## 🛠️ Tech Stack

- **Backend**: PHP 7.4+
- **Templating**: Twig 3.0
- **Styling**: Tailwind CSS (CDN)
- **Storage**: JSON files
- **Session**: PHP $\_SESSION

## 📦 Installation

### Prerequisites

- PHP 7.4 or higher
- Composer (PHP package manager)
- A web server (Apache, Nginx, or PHP built-in server)

### Setup Instructions

1. **Clone or download the repository**

2. **Navigate to the Twig project directory**

```bash
cd ticketflow-twig
```

3. **Install Composer dependencies**

```bash
composer install
```

4. **Create necessary directories**

```bash
mkdir -p data templates
```

5. **Set proper permissions** (Linux/Mac)

```bash
chmod 755 data
```

6. **Start the PHP development server**

```bash
php -S localhost:3002
```

7. **Open your browser**
   Navigate to `http://localhost:3002`

## 🏗️ Project Structure

```PHP

ticketflow-twig/
├── src/
│   ├── index.php               # Main entry point and router
│   ├── data/
│       └── tickets.json        # JSON file for tickets
├── templates/
│   ├── base.html.twig          # Base template with layout
│   ├── landing.html.twig       # Landing page
│   ├── login.html.twig         # Login page
│   ├── signup.html.twig        # Signup page
│   ├── dashboard.html.twig     # Dashboard
│   └── tickets.html.twig       # Ticket management
(auto-generated)
├── vendor/                     # Composer dependencies
├── index.php                   # Main entry point and router
├── composer.json               # PHP dependencies
└── README.md                   # This file
```

## 🎨 Design System

### Colors

- **Primary**: Indigo (`#4F46E5`)
- **Success**: Green (`#10B981`)
- **Warning**: Amber (`#F59E0B`)
- **Danger**: Red (`#EF4444`)
- **Gray**: Various shades for text and backgrounds

### Typography

- **Headings**: Bold, large sizes for hierarchy
- **Body**: Regular weight, readable sizes
- **Labels**: Medium weight, smaller sizes

### Spacing

- Consistent padding and margins using Tailwind's spacing scale
- Card-based layout with rounded corners and shadows

## 🔐 Authentication

### Session Management

- Sessions are stored in PHP's `$_SESSION` superglobal
- Session key: `ticketapp_session` contains base64 encoded token
- Protected routes check for valid session before rendering
- Unauthorized access redirects to login page

### Test Credentials

You can use any email and password combination:

- **Email**: Any valid email format (e.g., `test@example.com`)
- **Password**: Minimum 6 characters

## 📝 Ticket Structure

Tickets are stored in `data/tickets.json`:

```json
{
  "id": "unique_id",
  "title": "Ticket Title",
  "description": "Optional description",
  "status": "open|in_progress|closed",
  "priority": "low|medium|high",
  "createdAt": "2025-10-28T12:00:00+00:00"
}
```

## ✨ Template Files

### base.html.twig

- Base layout template
- Includes toast notification system
- Contains common HTML structure and styles

### landing.html.twig

- Hero section with wavy SVG background
- Decorative circular elements
- Feature cards
- Footer

### login.html.twig & signup.html.twig

- Email and password forms
- Server-side validation with inline errors
- Links to switch between login/signup

### dashboard.html.twig

- Ticket statistics cards
- Color-coded status indicators
- Quick action buttons

### tickets.html.twig

- Create/Edit ticket form (toggle visibility)
- Ticket cards with status badges
- Edit and delete actions
- JavaScript for form handling

## 🎯 Validation Rules

### Authentication

- **Email**: Must be valid email format (PHP `filter_var`)
- **Password**: Minimum 6 characters

### Tickets

- **Title**: Required, cannot be empty
- **Status**: Must be one of: `open`, `in_progress`, `closed`
- **Description**: Optional, no validation
- **Priority**: Optional, defaults to `medium`

## 🚨 Error Handling

The application handles errors in multiple ways:

1. **Server-side Validation**: Checked in PHP before processing
2. **Inline Error Messages**: Displayed beneath form fields
3. **Toast Notifications**: Shown for actions and errors
4. **Route Protection**: Redirects to login for unauthorized access
5. **Confirmation Dialogs**: JavaScript confirm() for destructive actions

## 📡 Routing

The application uses a simple query-string router:

- `index.php?page=landing` - Landing page
- `index.php?page=login` - Login page
- `index.php?page=signup` - Signup page
- `index.php?page=dashboard` - Dashboard (protected)
- `index.php?page=tickets` - Ticket management (protected)

### Actions (POST requests):

- `index.php?action=login` - Process login
- `index.php?action=signup` - Process signup
- `index.php?action=logout` - Logout user
- `index.php?action=create_ticket` - Create new ticket
- `index.php?action=update_ticket` - Update existing ticket
- `index.php?action=delete_ticket&id=xxx` - Delete ticket

## 📱 Responsive Breakpoints

- **Mobile**: < 640px (sm)
- **Tablet**: 640px - 1024px (md)
- **Desktop**: > 1024px (lg)

## 🔧 Server Requirements

### Recommended

- PHP 7.4 or 8.x
- Apache with mod_rewrite or Nginx
- Write permissions for `data/` directory

### Minimum

- PHP 7.4
- PHP built-in server
- File system write access

## 🐛 Troubleshooting

### Tickets not saving

- Check that `data/` directory exists
- Verify write permissions: `chmod 755 data`

### Session issues

- Ensure PHP session support is enabled
- Check `session_start()` is called at the top of index.php

### Twig errors

- Run `composer install` to ensure Twig is installed
- Check `vendor/` directory exists

### Page not found

- Ensure you're using the correct URL format with query parameters
- Check that `index.php` is the entry point

## Author

### Lisan al Gaib

- GitHub: [@codabytez](https://github.com/codabytez)
- Twitter: [@codabytez](https://x.com/codabytez)
- LinkedIn: [codabytez](https://www.linkedin.com/in/codabytez/)

## Acknowledgments

- Built for HNG Internship Frontend Track Stage 2
- Design inspiration from behance
