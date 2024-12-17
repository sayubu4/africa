<?php
session_start();
require_once 'db_config.php';


// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: login.php");
    exit();
}
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Admin';
// Function to update events in other PHP files
function updateEventInFile($country, $eventDetails, $action = 'add') {
  $filename = strtolower($country) . '.php';
  
  if (file_exists($filename)) {
      // Read the file content
      $fileContent = file_get_contents($filename);
      
      // Find the upcoming_events array
      preg_match('/\$upcoming_events\s*=\s*array\((.+?)\);/s', $fileContent, $matches);
      
      if (!empty($matches[1])) {
          // Parse existing events
          $existingEvents = eval('return array(' . $matches[1] . ');');
          
          switch($action) {
              case 'add':
                  // Add new event
                  $newEvent = array(
                      'title' => $eventDetails['title'],
                      'date' => $eventDetails['event_date'],
                      'description' => $eventDetails['description']
                  );
                  $existingEvents[] = $newEvent;
                  break;
              
              case 'edit':
                  // Find and update the existing event
                  foreach ($existingEvents as &$event) {
                      if ($event['title'] === $eventDetails['original_title']) {
                          $event['title'] = $eventDetails['title'];
                          $event['date'] = $eventDetails['event_date'];
                          $event['description'] = $eventDetails['description'];
                          break;
                      }
                  }
                  break;
              
              case 'delete':
                  // Remove the event
                  $existingEvents = array_filter($existingEvents, function($event) use ($eventDetails) {
                      return $event['title'] !== $eventDetails['title'];
                  });
                  break;
          }
          
          // Reconstruct the array
          $newEventsArray = var_export($existingEvents, true);
          
          // Replace the old array with the new one
          $updatedContent = preg_replace(
              '/\$upcoming_events\s*=\s*array\((.+?)\);/s', 
              '$upcoming_events = ' . $newEventsArray . ';', 
              $fileContent
          );
          
          // Write back to file
          file_put_contents($filename, $updatedContent);
      }
  }
}

// Database connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $db_username, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch key statistics
    $stats_queries = [
        'total_users' => "SELECT COUNT(*) as count FROM users",
        'total_events' => "SELECT COUNT(*) as count FROM events",
        'total_contributions' => "SELECT COUNT(*) as count FROM contributions",
        'upcoming_events' => "SELECT * FROM events WHERE event_date >= CURDATE() ORDER BY event_date ASC",
        'past_events' => "SELECT * FROM events WHERE event_date < CURDATE() ORDER BY event_date DESC"
    ];

    $stats = [];
    foreach ($stats_queries as $key => $query) {
        $stmt = $pdo->query($query);
        $stats[$key] = $key === 'upcoming_events' || $key === 'past_events' 
            ? $stmt->fetchAll(PDO::FETCH_ASSOC) 
            : $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }

} catch(PDOException $e) {
    die("Database Error: " . $e->getMessage());
}

// Handle event actions
$action_message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        if (isset($_POST['action'])) {
            switch($_POST['action']) {
                case 'add_event':
                  $stmt = $pdo->prepare("INSERT INTO events (title, description, event_date, location, status, country) VALUES (?, ?, ?, ?, ?, ?)");
                  $stmt->execute([
                      $_POST['title'], 
                      $_POST['description'], 
                      $_POST['event_date'], 
                      $_POST['location'], 
                      $_POST['status'],
                      $_POST['country']
                  ]);
                  
                  // Update the corresponding country's PHP file
                  updateEventInFile($_POST['country'], $_POST, 'add');
                  
                  $action_message = "Event added successfully!";
                  break;
            

                case 'edit_event':
                    // First, get the original event details to pass to the update function
                  $stmt = $pdo->prepare("SELECT title FROM events WHERE id = ?");
                  $stmt->execute([$_POST['event_id']]);
                  $originalEvent = $stmt->fetch(PDO::FETCH_ASSOC);
                
                  $stmt = $pdo->prepare("UPDATE events SET title = ?, description = ?, event_date = ?, location = ?, status = ?, country = ? WHERE id = ?");
                  $stmt->execute([
                      $_POST['title'], 
                      $_POST['description'], 
                      $_POST['event_date'], 
                      $_POST['location'], 
                      $_POST['status'],
                      $_POST['country'],
                      $_POST['event_id']
                  ]);
                    // Update the corresponding country's PHP file
                  updateEventInFile($_POST['country'], array_merge($_POST, ['original_title' => $originalEvent['title']]), 'edit');
                    
                  $action_message = "Event updated successfully!";
                  break;

                case 'delete_event':
                    // First, get the event details to pass to the update function
                  $stmt = $pdo->prepare("SELECT title, country FROM events WHERE id = ?");
                  $stmt->execute([$_POST['event_id']]);
                  $eventToDelete = $stmt->fetch(PDO::FETCH_ASSOC);
                
                    // Add error checking
                  if ($eventToDelete === false) {
                      $action_message = "Error: Event not found.";
                    } else {
                        // Proceed with deletion
                        $stmt = $pdo->prepare("DELETE FROM events WHERE id = ?");
                        $stmt->execute([$_POST['event_id']]);
                    
                        // Update the corresponding country's PHP file
                        updateEventInFile($eventToDelete['country'], ['title' => $eventToDelete['title']], 'delete');
                        
                        $action_message = "Event deleted successfully!";
                    }
                  break;
            }
        }
    } catch(PDOException $e) {
        $action_message = "Error: " . $e->getMessage();
    }
}

// Fetch countries for dropdown
$countries_query = "SELECT DISTINCT name FROM countries";
$countries_stmt = $pdo->query($countries_query);
$countries = $countries_stmt->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Advanced Admin Dashboard - East African Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
   <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #F5E6D3;
            color: #2F1B14;
        }

        header {
            background-color: #8B4513;
            color: #F5E6D3;
            text-align: center;
            padding: 0.2em 0;
            font-size: 1em;
        }

        nav ul {
            list-style: none;
            padding: 0;
            display: flex;
            justify-content: center;
            background-color: #8B4513;
        }

        nav ul li {
            margin: 0 0.8em;
        }

        nav ul li a {
            text-decoration: none;
            color: #FFD700;
            padding: 0.3em 0.8em;
            display: block;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        nav ul li a:hover {
            background-color: #FFA500;
        }

        .admin-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2em;
        }

        .dashboard-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            padding: 1.5em;
            margin-bottom: 1.5em;
        }

        .dashboard-card h2 {
            color: #8B4513;
            border-bottom: 2px solid #FFA500;
            padding-bottom: 0.5em;
            margin-bottom: 1em;
        }

        form input, 
        form select, 
        form textarea {
            width: 100%;
            padding: 0.5em;
            margin-bottom: 1em;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .btn {
            background-color: #8B4513;
            color: #F5E6D3;
            border: none;
            padding: 0.7em 1.5em;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #FFA500;
        }

        footer {
            background-color: #8B4513;
            color: #F5E6D3;
            text-align: center;
            padding: 1em 0;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        .event-list {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1em;
        }

        .event-item {
            background-color: #F5E6D3;
            padding: 1em;
            border-radius: 5px;
        }

        .user-info {
            position: absolute;
            top: 1em;
            right: 1em;
            display: flex;
            align-items: center;
            gap: 1em;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6">
<div class="absolute top-6 right-6 flex items-center space-x-4">
    <div style="color: #8B4513;" class="text-xl font-semibold">
    Welcome, <?php echo htmlspecialchars($username); ?>!
</div>
<a href="logout.php" style="background-color: #8B4513; color: #F5E6D3;" class="px-4 py-2 rounded hover:bg-orange-700 transition">
    Logout
</a>
  </div>
    <div class="container mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Statistics Cards -->
            <div class="dashboard-card p-6 text-center hover-lift">
                <h2 class="text-2xl font-bold mb-4 text-orange-600">Platform Statistics</h2>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <h3 class="text-3xl font-bold text-orange-500"><?php echo number_format($stats['total_users']); ?></h3>
                        <p class="text-sm text-gray-600">Total Users</p>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-orange-500"><?php echo number_format($stats['total_events']); ?></h3>
                        <p class="text-sm text-gray-600">Total Events</p>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-orange-500"><?php echo number_format($stats['total_contributions']); ?></h3>
                        <p class="text-sm text-gray-600">Contributions</p>
                    </div>
                </div>
            </div>

            <!-- Event Management Card -->
            <div class="dashboard-card p-6 hover-lift">
                <h2 class="text-2xl font-bold mb-4 text-orange-600">Event Management</h2>
                <form method="post" class="space-y-4">
                    <input type="hidden" name="action" value="add_event" id="form-action">
                    <input type="hidden" name="event_id" id="event-id">
                    
                    <div class="grid grid-cols-2 gap-4">
                        <input type="text" name="title" id="title" placeholder="Event Title" 
                               class="w-full p-2 border rounded bg-white" required>
                        
                        <select name="country" id="country" class="w-full p-2 border rounded bg-white" required>
                            <option value="">Select Country</option>
                            <?php foreach ($countries as $country): ?>
                                <option value="<?php echo htmlspecialchars($country); ?>">
                                    <?php echo htmlspecialchars($country); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <textarea name="description" id="description" placeholder="Event Description" 
                              class="w-full p-2 border rounded bg-white" rows="3" required></textarea>
                    
                    <div class="grid grid-cols-3 gap-4">
                        <input type="date" name="event_date" id="event_date" 
                               class="w-full p-2 border rounded bg-white" required>
                        
                        <input type="text" name="location" id="location" placeholder="Location" 
                               class="w-full p-2 border rounded bg-white" required>
                        
                        <select name="status" id="status" class="w-full p-2 border rounded bg-white" required>
                            <option value="upcoming">Upcoming</option>
                            <option value="past">Past</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    
                    <button type="submit" id="submit-btn" 
                            class="w-full bg-orange-500 text-white p-2 rounded hover:bg-orange-600 transition">
                        Add Event
                    </button>
                </form>
            </div>
        </div>

        <!-- Upcoming and Past Events -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <div class="dashboard-card p-6">
                <h2 class="text-2xl font-bold mb-4 text-orange-600">Upcoming Events</h2>
                <?php foreach ($stats['upcoming_events'] as $event): ?>
                    <div class="bg-white p-4 rounded mb-3 shadow-sm">
                        <h3 class="font-bold"><?php echo htmlspecialchars($event['title']); ?></h3>
                        <p class="text-sm text-gray-600"><?php echo htmlspecialchars($event['event_date']); ?> | <?php echo htmlspecialchars($event['location']); ?></p>
                        <div class="mt-2 flex justify-between">
                            <button onclick="editEvent(
                                '<?php echo $event['id']; ?>',
                                '<?php echo htmlspecialchars($event['title']); ?>',
                                '<?php echo htmlspecialchars($event['description']); ?>',
                                '<?php echo $event['event_date']; ?>',
                                '<?php echo htmlspecialchars($event['location']); ?>',
                                '<?php echo $event['status']; ?>',
                                '<?php echo $event['country']; ?>'
                            )" class="text-blue-500 hover:text-blue-700">Edit</button>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="action" value="delete_event">
                                <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                                <button type="submit" onclick="return confirm('Are you sure?');" 
                                        class="text-red-500 hover:text-red-700">Delete</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="dashboard-card p-6">
                <h2 class="text-2xl font-bold mb-4 text-orange-600">Past Events</h2>
                <?php foreach ($stats['past_events'] as $event): ?>
                    <div class="bg-white p-4 rounded mb-3 shadow-sm">
                        <h3 class="font-bold"><?php echo htmlspecialchars($event['title']); ?></h3>
                        <p class="text-sm text-gray-600"><?php echo htmlspecialchars($event['event_date']); ?> | <?php echo htmlspecialchars($event['location']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script>
    function editEvent(id, title, description, date, location, status, country) {
        document.getElementById('form-action').value = 'edit_event';
        document.getElementById('event-id').value = id;
        document.getElementById('title').value = title;
        document.getElementById('description').value = description;
        document.getElementById('event_date').value = date;
        document.getElementById('location').value = location;
        document.getElementById('status').value = status;
        document.getElementById('country').value = country;
        document.getElementById('submit-btn').textContent = 'Update Event';
    }
    </script>
</body>
</html>