<?php
ini_set('display_errors', 1);
require_once __DIR__.'/vendor/autoload.php';
use Lokhman\Silex\Provider as ToolsProviders;

$app = new Silex\Application();

$app['debug']=true;
$app->register(new ToolsProviders\ConfigServiceProvider(), array('config.dir' => __DIR__ . '/config'));
$app->boot();
$app->register(new Silex\Provider\DoctrineServiceProvider(), array('db.options' => array(
        'driver' =>  $app['database']['driver'],
        'dbhost' => $app['database']['host'],
        'dbname' => $app['database']['dbname'],
        'user' => $app['database']['user'],
        'charset' => $app['database']['charset'],
        'password' =>$app['database']['password'],),));

$app->register(new Silex\Provider\TwigServiceProvider(), array('twig.path' => __DIR__.'/views'));

$app->register(new Silex\Provider\AssetServiceProvider(), array(
    'assets.version' => 'v1',
    'assets.version_format' => '%s?version=%s',
    'assets.named_packages' => array(
    'public' => array('base_path' => '/public'),
    ),
));

//Route qui renvoie un tableau des plages en base et les instancie dans la vue
$app->get('/', function () use ($app) {
    //la requête va renvoyer toute les plages de la vue 'vue_local_plage' dans 
    //la variable $plages , qui serat un tableaux
    $plages = $app['db']->fetchAll("SELECT * FROM vue_local_plage ORDER BY UMID");
    //on retourne un rendue avec deux paramètre : 1. le nom du fichier twig pour 
    //le squelette de la vue & un tableaux avec un index utilisée dans le fichier 
    //et la variable associé
    return $app['twig']->render('index.twig', array(
        'plages' => $plages,
    ));
    //on précise le nom de cette route , c'est ce nom qui est utilisé dans la 
    //fonction path() de twig
})->bind('index');

//Route qui renvoie un tableau des plages en base et les instancie dans la vue 
//pour avoir une liste des plages
$app->get('/les_plages/', function() use($app) {
   $plages = $app['db']->fetchAll("SELECT * FROM vue_local_plage ORDER BY UMID");          
   return $app['twig']->render('lesplages.twig', array(
       'plages' => $plages
   ));
})->bind('les-plages');

//Route qui renvoie le resultat de la requete en json pour être utilisé en ajax
$app->get('/les-plages-json/', function() use($app) {
   $plages = $app['db']->fetchAll("SELECT * FROM vue_local_plage ORDER BY UMID");          
   return $app->json($plages, 200);
})->bind('les-plages-json');

//Route qui renvoie les évenement dans une vue spécifié
$app->get('/evenements/', function () use ($app) {
    $plages = $app['db']->fetchAll("SELECT * FROM vue_local_plage ORDER BY UMID");
    return $app['twig']->render('index.twig', array(
        'plages' => $plages
    ));
})->bind('evenements');

//Route qui renvoie une plage en spécifiant son UMID qui est dans la vue
$app->get('/une-plage/{plage}', function () use ($app) {
    $plage = $app['db']->fetchAll("SELECT * FROM vue_local_plage WHERE UMID={plage} ORDER BY UMID");
    return $app['twig']->render('une-plage.twig', array(
        'plage' => $plage
    ));
})->bind('une-plage');

//Route qui renvoie des information sur la météo des plages
$app->get('/meteo/', function () use ($app) {
    $plages = $app['db']->fetchAll("SELECT * FROM vue_local_plage ORDER BY UMID");
    return $app['twig']->render('index.twig', array(
        'plages' => $plages
    ));
})->bind('meteo');

//Route qui renvoie des information sur une plage au hasard
$app->get('/aventure/', function () use ($app) {
    $plages = $app['db']->fetchAll("SELECT * FROM vue_local_plage ORDER BY UMID");
    return $app['twig']->render('index.twig', array(
        'plages' => $plages
    ));
})->bind('aventure');

//Route qui permet de se connecter a son compte pour enrichir les données du site
$app->get('/conn/', function () use ($app) {
    $plages = $app['db']->fetchAll("SELECT * FROM vue_local_plage ORDER BY UMID");
    return $app['twig']->render('index.twig', array(
        'plages' => $plages
    ));
})->bind('conn');


//Route qui renvoie a la page des mentions légale
$app->get('/mentions-legales/', function () use ($app) {
    return $app['twig']->render('legal.twig', array(
        'name' => $name,
    ));
})->bind('legal-mention');

$app->run();
