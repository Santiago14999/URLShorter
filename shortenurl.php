<?php
$longURL = $_POST['url'];
if (!isset($longURL)) { echo "URL not defined"; exit(); }
$longURLHash = hash("md5", $longURL);

$db = new SQLite3('urls.db');
$result = $db->query("SELECT * FROM links WHERE link_hash = '{$longURLHash}'");
if ($row = $result->fetchArray())
{
	echo dec2link($row['link_id']);
	exit();
}

$result = $db->query('SELECT link_id FROM links ORDER BY link_id DESC LIMIT 1');
$link_id = $result->fetchArray()['link_id'] + 1;
$longURL = SQLite3::escapeString($longURL);
$db->query("INSERT INTO links VALUES({$link_id}, '{$longURLHash}', '{$longURL}')");

echo dec2link($link_id);

function dec2link($id) 
{
    $digits = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $link = '';
    do 
    {
        $dig = $id % 62;
        $link = $digits[$dig].$link;
        $id = floor($id / 62);
    } 
    while ($id != 0);
    return $link;
}
?>