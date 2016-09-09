<?php
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/homie.php";

    session_start();
    if (empty($_SESSION['list_of_homies'])) {
    $_SESSION['list_of_homies'] = array();
    }

    $app = new Silex\Application();
    $app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views'
));

    $app->get("/", function() use ($app) {

      $output = "";

      $all_homies = Homie::getAll();

        if (!empty($all_homies)) {
            $output = $output . "
                <h1>Homies</h1>
                <p>Here are all your homies:</p>
                ";

            foreach ($all_homies as $homie) {
                $output = $output . "<p>" . $homie->getName() . "</p>";
            }

        }


      $output = $output . "
        <form action='/homies' method='post'>
            <label for='homie'>Homie</label>
            <input id='name' name='name' type='text'>

            <label for='homie'>Phone</label>
            <input id='phone' name='phone' type='text'>

            <label for='homie'>Address</label>
            <input id='address' name='address' type='text'>

            <button type='submit'>Add Homie</button>
        </form>
        ";

        $output .= "
            <form action='/delete_homies' method='post'>
                <button type='submit'>Clear</button>
            </form>
        ";

      return $app['twig']->render('homies.html.twig');
    });

    $app->post("/homies", function() {
        $homie = new Homie($name = $_POST['name'],$phone = $_POST['phone'],$address = $_POST['address']);
        $homie->save();
        return "
            <h1>All my homies!</h1>
            <ol>
              <li><h1>" . $homie->getName() . "</h1>
                  <p>" . $homie->getPhone() . "</p>
                  <p>" . $homie->getAddress() . "</p></li>
            </ol>
            <p><a href='/'>View my homies.</a></p>
        ";
    });

    $app->post("/delete_homies", function() {

        Homie::deleteAll();

        return "
            <h1>List cleared!</h1>
            <p><a href='/'>Home</a></p>
        ";
    });

    return $app;
?>
