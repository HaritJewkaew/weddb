<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $ชื่อผู้ป่วย = $_POST['ชื่อผู้ป่วย'] ?? '';
    $พยาบาลที่เรียก = $_POST['พยาบาลที่เรียก'] ?? '';
    $สถานที่รับ = $_POST['สถานที่รับ'] ?? '';
    $สถานที่ส่ง = $_POST['สถานที่ส่ง'] ?? '';
    $ความเร่งด่วน = $_POST['ความเร่งด่วน'] ?? '';

    // Insert data into database
    $sql = "INSERT INTO event(ชื่อผู้ป่วย, พยาบาลที่เรียก, สถานที่รับ, สถานที่ส่ง, ความเร่งด่วน) 
            VALUES ('$ชื่อผู้ป่วย', '$พยาบาลที่เรียก', '$สถานที่รับ', '$สถานที่ส่ง', '$ความเร่งด่วน')";

if (mysqli_query($conn, $sql)) {
    echo "New record created successfully";
    
    // Get the channel access token from environment variable
    $channel_access_token = getenv('access_token');
    
    // Prepare and send the message
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.line.me/v2/bot/message/push');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'to' => 'U66ac9baca3b317d5e08d0ee5b3661682', // Test ID
        'messages' => [
            [
                'type' => 'text',
                'text' => 'รับงานเรียบร้อย',
            ],
            [
                'type' => 'flex',
                'altText' => 'this is a flex message',
                'contents' => [
                    'type' => 'bubble',
                    'body' => [
                        'type' => 'box',
                        'layout' => 'vertical',
                        'contents' => [
                            [
                                'type' => 'text',
                                'text' => 'hello',
                            ],
                            [
                                'type' => 'text',
                                'text' => 'world',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'type' => 'button',
                'action' => [
                    'type' => 'uri',
                    'label' => 'รับงาน',
                    'uri' => 'https://example.com',
                ],
                'style' => 'primary',
                'color' => '#0000ff',
            ],
        ],
    ]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $channel_access_token
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}
}

mysqli_close($conn);
?>