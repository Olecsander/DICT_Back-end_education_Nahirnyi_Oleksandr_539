<?php

$items = [];

if(isset($_GET['search']) && !empty($_GET['search'])){

    $search = $_GET['search'];

    $apiKey = "5fb319198b72d59e13b95aef02caafcc51ada569";

    $data = json_encode([
        "q" => $search
    ]);

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "https://google.serper.dev/search");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'X-API-KEY: '.$apiKey,
        'Content-Type: application/json'
    ]);

    $resultJson = curl_exec($ch);

    curl_close($ch);

    $result = json_decode($resultJson, true);

    if(isset($result['organic'])){
        $items = $result['organic'];
    }

}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>My Browser</title>
</head>

<body>

<h2>My Browser</h2>

<form method="GET">

    <label>Search:</label>
    <input type="text" name="search">

    <input type="submit" value="Submit">

</form>

<hr>

<?php

foreach($items as $item){

    echo "<h3>".$item['title']."</h3>";

    echo "<a href='".$item['link']."'>".$item['link']."</a>";

    echo "<p>".$item['snippet']."</p>";

    echo "<hr>";

}

?>

</body>
</html>
