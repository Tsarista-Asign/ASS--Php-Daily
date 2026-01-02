<?php
// Config Local
$local = [
    'host' => 'localhost',
    'name' => 'ass',
    'user' => 'root',
    'pass' => 'LongK@170105'
];

// Config InfinityFree
$remote = [
    'host' => 'sql112.infinityfree.com',
    'name' => 'if0_39708432_ass',
    'user' => 'if0_39708432',
    'pass' => 'LongK171'
];

// Thử kết nối Local trước
$conn = @new mysqli($local['host'], $local['user'], $local['pass'], $local['name']);

if ($conn->connect_error) {
    // Nếu Local thất bại thì thử Remote
    $conn = @new mysqli($remote['host'], $remote['user'], $remote['pass'], $remote['name']);
    if ($conn->connect_error) {
        die("❌ Connection failed (Local + Remote): " . $conn->connect_error);
    } else {
        // echo "✅ Đang dùng Remote (InfinityFree)";
    }
} else {
    // echo "✅ Đang dùng Local";
}
?>
