<?php 

function Decode()
{
    $content = file_get_contents("password.txt");
    $offsets = array(5,-14,31,-9,3);

    $result = "";

    foreach(explode("\n", $content) as $line)
    {
        if (strlen($line) == 0)
            continue;

        for($i = 0; $i < strlen($line); $i++)
        {
            $result .= chr(ord($line[$i]) - $offsets[$i % count($offsets)]);
        }
        $result .= "\n";
    }   

    return $result;
}


function Get_Color($LogInUser)
{

    $sname= "sql110.infinityfree.com";
    $uname= "if0_36079592";
    $password = "rROUdUQ7Su7c";
    $db_name = "if0_36079592_slaki";

    $conn = mysqli_connect($sname, $uname, $password, $db_name);
    if (!$conn)
    {
        echo "Connection failed!";
    }

    $sql = "SELECT titkos FROM `felhasznalok` WHERE username='$LogInUser'";

    $color = mysqli_query($conn, $sql);

    $color = mysqli_fetch_assoc($color);

    $color = $color['titkos'];

    $colors =array("piros" => "red", "zold" => "green","sarga" => "yellow", "kek" => "blue",  "fekete" => "black", "feher" => "white");

    $color = $colors[$color];

    return $color;
}



$LogInUser = $_POST['user'];
$LogInPassword = $_POST['password'];

$passwords = Decode();

$validUser = false;
$correctPassword = false;

foreach(explode("\n", $passwords) as $line)
{
    if (strlen($line) == 0)
        continue;

    $line = explode("*", $line);

    if ($line[0] == $LogInUser)
    {
        $validUser = true;
        if ($line[1] == $LogInPassword)
        {
            $correctPassword = true;
            break;
        }
    }
}

if ($validUser == false){
    header("Location: index.php?response=Invalid user!");
}
else if ($correctPassword == false){
    header("Location: index.php?response=Invalid password!&redirect=police.hu&delay=3");

}else{
    $color = Get_Color($LogInUser);
    header("Location: index.php?response=We are glad to have you here again!&color=$color&email=$LogInUser");

}

?>