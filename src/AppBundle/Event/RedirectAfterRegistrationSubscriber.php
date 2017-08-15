<?php

namespace AppBundle\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Routing\RouterInterface;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\FOSUserEvents;

class RedirectAfterRegistrationSubscriber implements EventSubscriberInterface
{
    use TargetPathTrait;

    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function onRegistrationSuccess(FormEvent $event)
    {
        // main is your firewall's name
        $url = $this->getTargetPath($event->getRequest()->getSession(), 'main');

        if (!$url) {
            $url = $this->router->generate('homepage');
        }

        $response = new RedirectResponse($url);
        $event->setResponse($response);
    }

    public static function getSubscribedEvents()
    {
        return [
            FOSUserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess'
        ];
    }
}
