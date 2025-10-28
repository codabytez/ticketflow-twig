<?php
$autoload = __DIR__ . '/../vendor/autoload.php';
if (!file_exists($autoload)) {
    die('Composer dependencies not found. Run "composer install" in the project root.');
}
require_once $autoload;


session_start();

// Initialize Twig
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');
$twig = new \Twig\Environment($loader, [
    'cache' => false,
    'debug' => true,
]);

// Add global variables
$twig->addGlobal('session', $_SESSION);

// Simple routing
$page = $_GET['page'] ?? 'landing';
$action = $_GET['action'] ?? null;

// Handle actions
if ($action) {
    switch ($action) {
        case 'login':
            handleLogin();
            break;
        case 'signup':
            handleSignup();
            break;
        case 'logout':
            handleLogout();
            break;
        case 'create_ticket':
            handleCreateTicket();
            break;
        case 'update_ticket':
            handleUpdateTicket();
            break;
        case 'delete_ticket':
            handleDeleteTicket();
            break;
    }
}

// Protect routes
if (in_array($page, ['dashboard', 'tickets']) && !isset($_SESSION['user'])) {
    $_SESSION['toast'] = ['message' => 'Please log in to access this page', 'type' => 'error'];
    header('Location: index.php?page=login');
    exit;
}

// Load tickets from JSON file
$ticketsFile = __DIR__ . '/data/tickets.json';
if (!file_exists($ticketsFile)) {
    if (!is_dir(__DIR__ . '/data')) {
        mkdir(__DIR__ . '/data', 0755, true);
    }
    file_put_contents($ticketsFile, json_encode([]));
}
$tickets = json_decode(file_get_contents($ticketsFile), true) ?? [];

// Calculate statistics
$stats = [
    'total' => count($tickets),
    'open' => count(array_filter($tickets, fn($t) => $t['status'] === 'open')),
    'in_progress' => count(array_filter($tickets, fn($t) => $t['status'] === 'in_progress')),
    'closed' => count(array_filter($tickets, fn($t) => $t['status'] === 'closed')),
];

// Get toast message and clear it
$toast = $_SESSION['toast'] ?? null;
unset($_SESSION['toast']);

// Render template
try {
    echo $twig->render($page . '.html.twig', [
        'page' => $page,
        'tickets' => $tickets,
        'stats' => $stats,
        'toast' => $toast,
        'user' => $_SESSION['user'] ?? null,
    ]);
} catch (\Twig\Error\LoaderError $e) {
    echo $twig->render('landing.html.twig', [
        'page' => 'landing',
        'toast' => ['message' => 'Page not found', 'type' => 'error'],
    ]);
}

// Action Handlers
function handleLogin() {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $errors = [];
    if (empty($email)) {
        $errors['email'] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email is invalid';
    }

    if (empty($password)) {
        $errors['password'] = 'Password is required';
    } elseif (strlen($password) < 6) {
        $errors['password'] = 'Password must be at least 6 characters';
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['toast'] = ['message' => 'Please fix the errors', 'type' => 'error'];
        header('Location: index.php?page=login');
        exit;
    }

    $_SESSION['user'] = ['email' => $email];
    $_SESSION['ticketapp_session'] = base64_encode($email . ':' . time());
    $_SESSION['toast'] = ['message' => 'Logged in successfully!', 'type' => 'success'];
    header('Location: index.php?page=dashboard');
    exit;
}

function handleSignup() {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $errors = [];
    if (empty($email)) {
        $errors['email'] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email is invalid';
    }

    if (empty($password)) {
        $errors['password'] = 'Password is required';
    } elseif (strlen($password) < 6) {
        $errors['password'] = 'Password must be at least 6 characters';
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['toast'] = ['message' => 'Please fix the errors', 'type' => 'error'];
        header('Location: index.php?page=signup');
        exit;
    }

    $_SESSION['user'] = ['email' => $email];
    $_SESSION['ticketapp_session'] = base64_encode($email . ':' . time());
    $_SESSION['toast'] = ['message' => 'Signed up successfully!', 'type' => 'success'];
    header('Location: index.php?page=dashboard');
    exit;
}

function handleLogout() {
    session_destroy();
    session_start();
    $_SESSION['toast'] = ['message' => 'Logged out successfully', 'type' => 'success'];
    header('Location: index.php?page=landing');
    exit;
}

function handleCreateTicket() {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $status = $_POST['status'] ?? 'open';
    $priority = $_POST['priority'] ?? 'medium';

    $errors = [];
    if (empty($title)) {
        $errors['title'] = 'Title is required';
    }

    if (!in_array($status, ['open', 'in_progress', 'closed'])) {
        $errors['status'] = 'Invalid status';
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['toast'] = ['message' => 'Please fix the errors', 'type' => 'error'];
        header('Location: index.php?page=tickets');
        exit;
    }

    $ticketsFile = __DIR__ . '/data/tickets.json';
    $tickets = json_decode(file_get_contents($ticketsFile), true) ?? [];

    $newTicket = [
        'id' => uniqid(),
        'title' => $title,
        'description' => $description,
        'status' => $status,
        'priority' => $priority,
        'createdAt' => date('c'),
    ];

    $tickets[] = $newTicket;
    file_put_contents($ticketsFile, json_encode($tickets, JSON_PRETTY_PRINT));

    $_SESSION['toast'] = ['message' => 'Ticket created successfully', 'type' => 'success'];
    header('Location: index.php?page=tickets');
    exit;
}

function handleUpdateTicket() {
    $id = $_POST['id'] ?? '';
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $status = $_POST['status'] ?? 'open';
    $priority = $_POST['priority'] ?? 'medium';

    $errors = [];
    if (empty($title)) {
        $errors['title'] = 'Title is required';
    }

    if (!in_array($status, ['open', 'in_progress', 'closed'])) {
        $errors['status'] = 'Invalid status';
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['toast'] = ['message' => 'Please fix the errors', 'type' => 'error'];
        header('Location: index.php?page=tickets');
        exit;
    }

    $ticketsFile = __DIR__ . '/data/tickets.json';
    $tickets = json_decode(file_get_contents($ticketsFile), true) ?? [];

    foreach ($tickets as &$ticket) {
        if ($ticket['id'] === $id) {
            $ticket['title'] = $title;
            $ticket['description'] = $description;
            $ticket['status'] = $status;
            $ticket['priority'] = $priority;
            break;
        }
    }

    file_put_contents($ticketsFile, json_encode($tickets, JSON_PRETTY_PRINT));

    $_SESSION['toast'] = ['message' => 'Ticket updated successfully', 'type' => 'success'];
    header('Location: index.php?page=tickets');
    exit;
}

function handleDeleteTicket() {
    $id = $_GET['id'] ?? '';

    $ticketsFile = __DIR__ . '/data/tickets.json';
    $tickets = json_decode(file_get_contents($ticketsFile), true) ?? [];

    $tickets = array_filter($tickets, fn($t) => $t['id'] !== $id);
    $tickets = array_values($tickets); // Re-index array

    file_put_contents($ticketsFile, json_encode($tickets, JSON_PRETTY_PRINT));

    $_SESSION['toast'] = ['message' => 'Ticket deleted successfully', 'type' => 'success'];
    header('Location: index.php?page=tickets');
    exit;
}