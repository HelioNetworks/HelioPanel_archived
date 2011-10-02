<?php

namespace HelioNetworks\HelioPanelBundle\EventListener;

use Symfony\Component\Routing\RouterInterface;
use HelioNetworks\HelioPanelBundle\Exception\NoAccountsException;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;

class NoAccountsListener
{
	protected $router;

	public function __construct(RouterInterface $router)
	{
		$this->router = $router;
	}

    /**
     * Handles the event when notified or filtered.
     *
     * @param Event $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        if ($event->getException() instanceof NoAccountsException) {
        	$response = new RedirectResponse($this->router->generate('account_add'));
        	$event->setResponse($response);

        	$event->stopPropagation();
        }
    }
}