<?php
namespace MailPoetVendor;
if (!defined('ABSPATH')) exit;
interface Swift_Events_TransportExceptionListener extends Swift_Events_EventListener
{
 public function exceptionThrown(Swift_Events_TransportExceptionEvent $evt);
}
