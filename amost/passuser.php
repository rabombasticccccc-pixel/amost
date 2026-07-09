<?php
// ១. នេះគឺជា Password Hash ដែលអ្នកបានមកពីការ echo កាលពីលើកមុន
$hash_in_db = '$2y$10$YourGeneratedHashGoesHere...';

$user_input_name = "Sosynakry";

// 2. Verify the password
if (password_verify($user_input_name, $hashed_password_from_db)) {
    echo "✅ Password is valid! Logged in.";
    
    // Start a session for the user
    session_start();
    $_SESSION['user_id'] = 6; 
} else {
    echo "❌ Invalid password. Access denied.";
}
?>