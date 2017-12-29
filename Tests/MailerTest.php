<?php

/*
 * This file is part of the Fxp package.
 *
 * (c) François Pluchino <francois.pluchino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fxp\Component\Mailer\Tests;

use Fxp\Component\Mailer\Mailer;
use Fxp\Component\Mailer\MailRenderedInterface;
use Fxp\Component\Mailer\MailTemplaterInterface;
use Fxp\Component\Mailer\MailTypes;
use Fxp\Component\Mailer\Transport\TransportInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Tests for mailer.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class MailerTest extends TestCase
{
    /**
     * @var MailTemplaterInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $templater;

    /**
     * @var TransportInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $transport;

    /**
     * @var EventDispatcherInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $dispatcher;

    /**
     * @var Mailer
     */
    protected $mailer;

    protected function setUp()
    {
        $this->templater = $this->getMockBuilder(MailTemplaterInterface::class)->getMock();
        $this->dispatcher = $this->getMockBuilder(EventDispatcherInterface::class)->getMock();
        $this->transport = $this->getMockBuilder(TransportInterface::class)->getMock();
        $this->transport->expects($this->atLeastOnce())
            ->method('getName')
            ->will($this->returnValue('test'));

        $this->mailer = new Mailer($this->templater, array($this->transport), $this->dispatcher);
    }

    public function testGetTransport()
    {
        $this->assertTrue($this->mailer->hasTransport('test'));
        $this->assertFalse($this->mailer->hasTransport('foo'));

        $this->assertSame($this->transport, $this->mailer->getTransport('test'));
    }

    /**
     * @expectedException \Fxp\Component\Mailer\Exception\InvalidArgumentException
     * @expectedExceptionMessage The "foo" transport does not exist
     */
    public function testGetInvalidTransport()
    {
        $this->mailer->getTransport('foo');
    }

    public function testSend()
    {
        $message = $this->getMockBuilder(\Swift_Message::class)->disableOriginalConstructor()->getMock();
        $mail = $this->getMockBuilder(MailRenderedInterface::class)->getMock();

        $this->templater->expects($this->once())
            ->method('render')
            ->with('template-test', array(), MailTypes::TYPE_ALL)
            ->will($this->returnValue($mail));

        $this->transport->expects($this->once())
            ->method('send')
            ->with($message, $mail)
            ->will($this->returnValue(true));

        $res = $this->mailer->send('test', $message, 'template-test', array(), MailTypes::TYPE_ALL);

        $this->assertTrue($res);
    }

    public function testSendWithoutTemplate()
    {
        $message = $this->getMockBuilder(\Swift_Message::class)->disableOriginalConstructor()->getMock();

        $this->templater->expects($this->never())
            ->method('render');

        $this->transport->expects($this->once())
            ->method('send')
            ->with($message, null)
            ->will($this->returnValue(true));

        $res = $this->mailer->send('test', $message);

        $this->assertTrue($res);
    }
}
