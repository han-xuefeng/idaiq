<?php


namespace App\Event;


class GoogleListener
{
    public function onResponse(ResponseEvent $event)
    {
        echo '#########';
        $response = $event->getResponse();

        if ($response->isRedirection()
            || ($response->headers->has('Content-Type') && false === strpos($response->headers->get('Content-Type'), 'html'))
            || 'html' !== $event->getRequest()->getRequestFormat()
        ) {
            return;
        }

        $response->setContent($response->getContent().'GA CODE');
    }

    public function onRequest(ResponseEvent $event)
    {
        $response = $event->getResponse();
        var_dump($response->getContent());
    }
}