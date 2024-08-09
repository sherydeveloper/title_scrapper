<?php
include './conn.php';

function scrapeExample2($url) {
    // Use file_get_contents or cURL to get the HTML content of example2.com
    $html = file_get_contents($url);
    
    if ($html !== false) {
        $dom = new DOMDocument;
        @$dom->loadHTML($html);
        $xpath = new DOMXPath($dom);

        // Example: Extract all text from <h2> tags (you'll need to adjust this based on your needs)
        $headings = $xpath->query('//h2');
        $data = [];
        foreach ($headings as $heading) {
            $data[] = $heading->textContent;
        }
        return $data;
    } else {
        echo "Failed to retrieve data from example2.com";
        return null;
    }
}

$result = scrapeExample2('https://www.thenews.com.pk/');

if ($result) {
    foreach($result as $list) {
        // Escape special characters in the title
        $list = mysqli_real_escape_string($conn, $list);
        
        // Prepare the SQL statement
        $sql = "INSERT INTO `article_titles` (`title`) VALUES ('$list')";
        
        // Execute the SQL statement
        $run = mysqli_query($conn, $sql);
        
        if (!$run) {
            echo "Error inserting title: " . mysqli_error($conn);
        }
    }
} else {
    echo "No data found to insert.";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Title Scrapper</title>
</head>
<body>
    
        <?php
            $sql = "SELECT title, created_at FROM article_titles ORDER BY created_at DESC";
            $result = mysqli_query($conn, $sql);
            
            if ($result->num_rows > 0) {
                // Output the titles
                while($row = mysqli_fetch_assoc($result)) {
                    echo "<h2>" . htmlspecialchars($row["title"]) . " (Added on: " . htmlspecialchars($row["created_at"]) . ")</h2>";
                }
            } else {
                echo "No titles found.";
            }
            
            mysqli_close($conn); // Close the database connection
        ?>
</body>
</html>