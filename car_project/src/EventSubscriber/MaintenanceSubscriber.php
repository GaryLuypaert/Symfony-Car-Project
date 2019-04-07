<?php


namespace App\EventSubscriber;


use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

class MaintenanceSubscriber implements EventSubscriberInterface
{
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function onResponse(FilterResponseEvent $filterResponseEvent) {

        $dotenv = new Dotenv();
        $dotenv->load(dirname(__DIR__).'/../.env');

        $maintenance = filter_var(getenv('MAINTENANCE'), FILTER_VALIDATE_BOOLEAN);

        if ($maintenance) {
            $content = $this->twig->render('maintenance/maintenance.html.twig');

            $response = new Response($content);

            return $filterResponseEvent->setResponse($response);
        }

        return $filterResponseEvent->getResponse()->getContent();
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::RESPONSE => 'onResponse'
        ];
    }

}