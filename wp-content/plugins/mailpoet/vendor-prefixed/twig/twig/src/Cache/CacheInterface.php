<?php
namespace MailPoetVendor\Twig\Cache;
if (!defined('ABSPATH')) exit;
interface CacheInterface
{
 public function generateKey($name, $className);
 public function write($key, $content);
 public function load($key);
 public function getTimestamp($key);
}
\class_alias('MailPoetVendor\\Twig\\Cache\\CacheInterface', 'MailPoetVendor\\Twig_CacheInterface');
