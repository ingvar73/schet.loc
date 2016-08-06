<?
    require "cookies.php";
    require "rb.php";
        R::setup( 'mysql:host=localhost; dbname=howdytest', 'root', '' ); //for both mysql or mariaDB

    $cookie_key = 'online-cache';
    $ip = $_SERVER['REMOTE_ADDR'];
    $online = R::findOne('online', 'ip = ?', array($ip));

    if($online)
    {   
        $do_update = false;
        // Update
        if (CookieManager::stored($cookie_key)){
            // Via cookies
            $c = (array) @json_decode(CookieManager::read($cookie_key), true);
            if ($c){
                if ($c['lastvisit'] < (time() - (60*5))){
                    $do_update = true;
                }
            } else {
                // Without cookies
                    $do_update = true;
            }
            
        } else {
            // Without cookies
                    $do_update = true;
        }
        
        if ($do_update){
            // Update if required
        
//        exit ('Обновляю данные!');
        $time = time();
        $online->lastvisit = $time;
        R::store($online);
        CookieManager::store($cookie_key, json_encode(array('id' => $online->id, 'lastvisit' => $time)));
        }
        
    } else {
        // Create
        $time = time();
        $online = R::dispense('online');
        $online->lastvisit = $time;
        $online->ip = $ip;
        R::store($online);
        CookieManager::store($cookie_key, json_encode(array('id' => $online->id, 'lastvisit' => $time)));
    }

    $online_count = R::count('online', "lastvisit >" . (time() - (3600)));

    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Счетчик онлайн</title>
</head>
<body>
    Счетчик онлайн: <?php echo $online_count; ?>
</body>
</html>