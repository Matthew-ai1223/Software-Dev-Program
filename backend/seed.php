<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
require_once 'databass.php';

try {
    // Sample Assignments
    $assignments = [
        ['Build Personal Portfolio', 'Create a responsive portfolio website using HTML5, CSS Grid, and JavaScript', date('Y-m-d H:i:s', strtotime('+2 days')), 'active', null],
        ['JavaScript Calculator', 'Build a functional calculator with basic operations', date('Y-m-d H:i:s', strtotime('+5 days')), 'active', null],
        ['Semantic Blog Layout', 'Create a blog layout with proper HTML5 semantics', date('Y-m-d H:i:s', strtotime('-5 days')), 'graded', 95],
        ['CSS Art Challenge', 'Create a visual artwork using only CSS', date('Y-m-d H:i:s', strtotime('-10 days')), 'graded', 88]
    ];

    foreach ($assignments as $a) {
        $stmt = $pdo->prepare("INSERT INTO assignments (title, description, due_date, status, grade) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute($a);
    }

    // Sample Curriculum
    $curriculum = [
        ['Month 1: Web Fundamentals', 'Week 1: HTML5 Semantics', 'Master the structure of the web with semantic HTML elements', 'completed'],
        ['Month 1: Web Fundamentals', 'Week 2: CSS3 Styling', 'Learn modern CSS including Flexbox and Grid layouts', 'completed'],
        ['Month 1: Web Fundamentals', 'Week 3: Responsive Design', 'Build mobile-first responsive websites', 'completed'],
        ['Month 2: JavaScript Basics', 'Week 5: ES6+ Features', 'Arrow functions, destructuring, template literals, and modules', 'in_progress'],
        ['Month 2: JavaScript Basics', 'Week 6: DOM Manipulation', 'Create interactive web pages with JavaScript', 'in_progress'],
        ['Month 2: JavaScript Basics', 'Week 7: Async JavaScript', 'Promises, async/await, and fetch API', 'locked'],
        ['Month 3: Advanced Topics', 'Week 9: React Fundamentals', 'Components, props, state, and hooks', 'locked'],
        ['Month 3: Advanced Topics', 'Week 10: State Management', 'Context API and Redux basics', 'locked']
    ];

    foreach ($curriculum as $c) {
        $stmt = $pdo->prepare("INSERT INTO curriculum (module_title, week_title, description, status) VALUES (?, ?, ?, ?)");
        $stmt->execute($c);
    }

    // Sample Community Posts
    $posts = [
        ['Hackathon This Weekend!', 'Join us for a 24-hour coding sprint. Theme: Tech for Good. Great prizes!', 'announcement', 'Instructor Mike', 0],
        ['Welcome to DevProgram!', 'Excited to have you all here. Let\'s build amazing things together!', 'announcement', 'Admin Team', 0],
        ['Best VS Code Extensions?', 'What are your favorite VS Code extensions for web development?', 'discussion', 'Sarah M.', 15],
        ['Help with Flexbox alignment', 'I\'m struggling to center items vertically. Any tips?', 'discussion', 'John D.', 8],
        ['Sharing my portfolio!', 'Just finished my portfolio project. Would love feedback!', 'discussion', 'Emma T.', 23],
        ['Career Fair Next Month', 'Top tech companies will be recruiting. Update your resume!', 'announcement', 'Career Services', 0]
    ];

    foreach ($posts as $p) {
        $stmt = $pdo->prepare("INSERT INTO community_posts (title, content, type, author, replies) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute($p);
    }

    // Sample Schedule Events
    $schedules = [
        ['Live Q&A Session', date('Y-m-d H:i:s'), '04:00 PM - 05:00 PM', 'Ask anything about JavaScript and React'],
        ['Advanced CSS Workshop', date('Y-m-d H:i:s', strtotime('+2 days')), '10:00 AM - 12:00 PM', 'Deep dive into Grid and Flexbox'],
        ['JavaScript Deep Dive', date('Y-m-d H:i:s', strtotime('+4 days')), '02:00 PM - 04:00 PM', 'ES6+ features and best practices'],
        ['Code Review Session', date('Y-m-d H:i:s', strtotime('+6 days')), '03:00 PM - 04:30 PM', 'Live code review of student projects'],
        ['React Fundamentals', date('Y-m-d H:i:s', strtotime('+8 days')), '10:00 AM - 01:00 PM', 'Components, props, and state management']
    ];

    foreach ($schedules as $s) {
        $stmt = $pdo->prepare("INSERT INTO schedule (title, event_date, time_range, description) VALUES (?, ?, ?, ?)");
        $stmt->execute($s);
    }

    echo json_encode([
        'success' => true, 
        'message' => 'Sample data inserted successfully!',
        'counts' => [
            'assignments' => count($assignments),
            'curriculum' => count($curriculum),
            'posts' => count($posts),
            'events' => count($schedules)
        ]
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
