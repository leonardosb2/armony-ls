<?php
namespace MailPoetVendor;
if (!defined('ABSPATH')) exit;
class Swift_Transport_EsmtpTransport extends Swift_Transport_AbstractSmtpTransport implements Swift_Transport_SmtpAgent
{
 private $handlers = [];
 private $capabilities = [];
 private $params = ['protocol' => 'tcp', 'host' => 'localhost', 'port' => 25, 'timeout' => 30, 'blocking' => 1, 'tls' => \false, 'type' => Swift_Transport_IoBuffer::TYPE_SOCKET, 'stream_context_options' => []];
 public function __construct(Swift_Transport_IoBuffer $buf, array $extensionHandlers, Swift_Events_EventDispatcher $dispatcher, $localDomain = '127.0.0.1', Swift_AddressEncoder $addressEncoder = null)
 {
 parent::__construct($buf, $dispatcher, $localDomain, $addressEncoder);
 $this->setExtensionHandlers($extensionHandlers);
 }
 public function setHost($host)
 {
 $this->params['host'] = $host;
 return $this;
 }
 public function getHost()
 {
 return $this->params['host'];
 }
 public function setPort($port)
 {
 $this->params['port'] = (int) $port;
 return $this;
 }
 public function getPort()
 {
 return $this->params['port'];
 }
 public function setTimeout($timeout)
 {
 $this->params['timeout'] = (int) $timeout;
 $this->buffer->setParam('timeout', (int) $timeout);
 return $this;
 }
 public function getTimeout()
 {
 return $this->params['timeout'];
 }
 public function setEncryption($encryption)
 {
 $encryption = \strtolower($encryption ?? '');
 if ('tls' == $encryption) {
 $this->params['protocol'] = 'tcp';
 $this->params['tls'] = \true;
 } else {
 $this->params['protocol'] = $encryption;
 $this->params['tls'] = \false;
 }
 return $this;
 }
 public function getEncryption()
 {
 return $this->params['tls'] ? 'tls' : $this->params['protocol'];
 }
 public function setStreamOptions($options)
 {
 $this->params['stream_context_options'] = $options;
 return $this;
 }
 public function getStreamOptions()
 {
 return $this->params['stream_context_options'];
 }
 public function setSourceIp($source)
 {
 $this->params['sourceIp'] = $source;
 return $this;
 }
 public function getSourceIp()
 {
 return $this->params['sourceIp'] ?? null;
 }
 public function setPipelining($enabled)
 {
 $this->pipelining = $enabled;
 return $this;
 }
 public function getPipelining()
 {
 return $this->pipelining;
 }
 public function setExtensionHandlers(array $handlers)
 {
 $assoc = [];
 foreach ($handlers as $handler) {
 $assoc[$handler->getHandledKeyword()] = $handler;
 }
 \uasort($assoc, function ($a, $b) {
 return $a->getPriorityOver($b->getHandledKeyword());
 });
 $this->handlers = $assoc;
 $this->setHandlerParams();
 return $this;
 }
 public function getExtensionHandlers()
 {
 return \array_values($this->handlers);
 }
 public function executeCommand($command, $codes = [], &$failures = null, $pipeline = \false, $address = null)
 {
 $failures = (array) $failures;
 $stopSignal = \false;
 $response = null;
 foreach ($this->getActiveHandlers() as $handler) {
 $response = $handler->onCommand($this, $command, $codes, $failures, $stopSignal);
 if ($stopSignal) {
 return $response;
 }
 }
 return parent::executeCommand($command, $codes, $failures, $pipeline, $address);
 }
 public function __call($method, $args)
 {
 foreach ($this->handlers as $handler) {
 if (\in_array(\strtolower($method), \array_map('strtolower', (array) $handler->exposeMixinMethods()))) {
 $return = \call_user_func_array([$handler, $method], $args);
 // Allow fluid method calls
 if (null === $return && 'set' == \substr($method, 0, 3)) {
 return $this;
 } else {
 return $return;
 }
 }
 }
 \trigger_error('Call to undefined method ' . $method, \E_USER_ERROR);
 }
 protected function getBufferParams()
 {
 return $this->params;
 }
 protected function doHeloCommand()
 {
 try {
 $response = $this->executeCommand(\sprintf("EHLO %s\r\n", $this->domain), [250]);
 } catch (Swift_TransportException $e) {
 return parent::doHeloCommand();
 }
 if ($this->params['tls']) {
 try {
 $this->executeCommand("STARTTLS\r\n", [220]);
 if (!$this->buffer->startTLS()) {
 throw new Swift_TransportException('Unable to connect with TLS encryption');
 }
 try {
 $response = $this->executeCommand(\sprintf("EHLO %s\r\n", $this->domain), [250]);
 } catch (Swift_TransportException $e) {
 return parent::doHeloCommand();
 }
 } catch (Swift_TransportException $e) {
 $this->throwException($e);
 }
 }
 $this->capabilities = $this->getCapabilities($response);
 if (!isset($this->pipelining)) {
 $this->pipelining = isset($this->capabilities['PIPELINING']);
 }
 $this->setHandlerParams();
 foreach ($this->getActiveHandlers() as $handler) {
 $handler->afterEhlo($this);
 }
 }
 protected function doMailFromCommand($address)
 {
 $address = $this->addressEncoder->encodeString($address);
 $handlers = $this->getActiveHandlers();
 $params = [];
 foreach ($handlers as $handler) {
 $params = \array_merge($params, (array) $handler->getMailParams());
 }
 $paramStr = !empty($params) ? ' ' . \implode(' ', $params) : '';
 $this->executeCommand(\sprintf("MAIL FROM:<%s>%s\r\n", $address, $paramStr), [250], $failures, \true);
 }
 protected function doRcptToCommand($address)
 {
 $address = $this->addressEncoder->encodeString($address);
 $handlers = $this->getActiveHandlers();
 $params = [];
 foreach ($handlers as $handler) {
 $params = \array_merge($params, (array) $handler->getRcptParams());
 }
 $paramStr = !empty($params) ? ' ' . \implode(' ', $params) : '';
 $this->executeCommand(\sprintf("RCPT TO:<%s>%s\r\n", $address, $paramStr), [250, 251, 252], $failures, \true, $address);
 }
 private function getCapabilities($ehloResponse)
 {
 $capabilities = [];
 $ehloResponse = \trim($ehloResponse ?? '');
 $lines = \explode("\r\n", $ehloResponse);
 \array_shift($lines);
 foreach ($lines as $line) {
 if (\preg_match('/^[0-9]{3}[ -]([A-Z0-9-]+)((?:[ =].*)?)$/Di', $line, $matches)) {
 $keyword = \strtoupper($matches[1]);
 $paramStr = \strtoupper(\ltrim($matches[2], ' ='));
 $params = !empty($paramStr) ? \explode(' ', $paramStr) : [];
 $capabilities[$keyword] = $params;
 }
 }
 return $capabilities;
 }
 private function setHandlerParams()
 {
 foreach ($this->handlers as $keyword => $handler) {
 if (\array_key_exists($keyword, $this->capabilities)) {
 $handler->setKeywordParams($this->capabilities[$keyword]);
 }
 }
 }
 private function getActiveHandlers()
 {
 $handlers = [];
 foreach ($this->handlers as $keyword => $handler) {
 if (\array_key_exists($keyword, $this->capabilities)) {
 $handlers[] = $handler;
 }
 }
 return $handlers;
 }
}
