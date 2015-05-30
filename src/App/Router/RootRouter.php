<?php
namespace App\Router;

use Base\Application;
use Base\Router\RouterInterface;
use Silex\ControllerCollection;

class RootRouter implements RouterInterface
{
    public function load(Application $app)
    {

        $app->get(
            '/',
            function () use ($app) {
                return $app->redirect('/hello/visitor');
            }
        );

        /**
         * @var ControllerCollection $backend
         */
        $backend = $app['controllers_factory'];

        $backend->get('/hello/{name}', 'App\Controller\RootController::helloAction');
        $backend->get('/crawl', 'App\Controller\RootController::crawlAction');
        $backend->get('/apecCrawl', 'App\Controller\RootController::apecCrawlAction');
        $backend->get('/scrap', 'App\Controller\RootController::scrapAction');
        $backend->get('/connect', 'App\Controller\RootController::connectAction');
        $backend->get('/getJobAdverts/{limit}', 'App\Controller\RootController::getJobAdvertsAction');
        $backend->get('/populateCities', 'App\Controller\RootController::populateCitiesAction');
        $backend->get('/scrapTechnos', 'App\Controller\RootController::scrapTechnosAction');

        $app->mount('/backend', $backend);

    }
}
