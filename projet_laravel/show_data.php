<?php

require __DIR__ . '/vendor/autoload.php';

use App\Models\User;
use App\Models\Category;
use App\Models\Book;
use App\Models\Exchange;
use App\Models\Report;
use App\Models\Notification;

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== DATABASE SEEDING SUMMARY ===\n\n";

echo "📊 RECORD COUNTS:\n";
echo "- Users: " . User::count() . "\n";
echo "- Categories: " . Category::count() . "\n";
echo "- Books: " . Book::count() . "\n";
echo "- Exchanges: " . Exchange::count() . "\n";
echo "- Reports: " . Report::count() . "\n";
echo "- Notifications: " . Notification::count() . "\n\n";

echo "👥 SAMPLE USERS:\n";
foreach (User::take(5)->get() as $user) {
    echo "- {$user->name} ({$user->email}) - Role: {$user->role}\n";
}

echo "\n📚 SAMPLE CATEGORIES:\n";
foreach (Category::take(5)->get() as $category) {
    echo "- {$category->name}: {$category->description}\n";
}

echo "\n📖 SAMPLE BOOKS:\n";
foreach (Book::with('category', 'user')->take(5)->get() as $book) {
    echo "- {$book->title} by {$book->author} ({$book->category->name}) - Owner: {$book->user->name}\n";
}

echo "\n🔄 SAMPLE EXCHANGES:\n";
foreach (Exchange::with('initiateur', 'recepteur', 'bookDemande')->take(3)->get() as $exchange) {
    $recepteurName = $exchange->recepteur ? $exchange->recepteur->name : 'N/A';
    echo "- {$exchange->type} ({$exchange->status}) - {$exchange->initiateur->name} ↔ {$recepteurName} - Book: {$exchange->bookDemande->title}\n";
}

echo "\n⚠️ SAMPLE REPORTS:\n";
foreach (Report::with('user', 'reported')->take(3)->get() as $report) {
    $reportedName = $report->reported ? $report->reported->name : 'N/A';
    echo "- {$report->type} by {$report->user->name} against {$reportedName} - Status: {$report->status}\n";
}

echo "\n🔔 SAMPLE NOTIFICATIONS:\n";
foreach (Notification::with('user')->take(3)->get() as $notification) {
    echo "- {$notification->type} for {$notification->user->name}: {$notification->title}\n";
}

echo "\n✅ Database successfully populated with sample data!\n";