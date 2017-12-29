<?php

/*
 * This file is part of the Fxp package.
 *
 * (c) François Pluchino <francois.pluchino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fxp\Component\Mailer\Tests\Filter;

use Fxp\Component\Mailer\Event\FilterPostRenderEvent;
use Fxp\Component\Mailer\Event\FilterPreSendEvent;
use Fxp\Component\Mailer\Filter\FilterRegistryInterface;
use Fxp\Component\Mailer\Filter\TemplateFilterInterface;
use Fxp\Component\Mailer\Filter\TransportFilterInterface;
use Fxp\Component\Mailer\Listener\FilterSubscriber;
use Fxp\Component\Mailer\MailerEvents;
use Fxp\Component\Mailer\MailRenderedInterface;
use PHPUnit\Framework\TestCase;

/**
 * Tests for filter subscriber.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class FilterSubscriberTest extends TestCase
{
    /**
     * @var FilterRegistryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $registry;

    /**
     * @var FilterSubscriber
     */
    protected $listener;

    protected function setUp()
    {
        $this->registry = $this->getMockBuilder(FilterRegistryInterface::class)->getMock();
        $this->listener = new FilterSubscriber($this->registry);
    }

    public function testGetSubscribedEvents()
    {
        $events = $this->listener->getSubscribedEvents();
        $valid = array(
            MailerEvents::TEMPLATE_POST_RENDER,
            MailerEvents::TRANSPORT_PRE_SEND,
        );

        $this->assertSame($valid, array_keys($events));
    }

    public function testTemplateFilters()
    {
        /* @var MailRenderedInterface|\PHPUnit_Framework_MockObject_MockObject $mailRendered */
        $mailRendered = $this->getMockBuilder(MailRenderedInterface::class)->getMock();

        /* @var FilterPostRenderEvent|\PHPUnit_Framework_MockObject_MockObject $event */
        $event = $this->getMockBuilder(FilterPostRenderEvent::class)->disableOriginalConstructor()->getMock();
        $event->expects($this->once())
            ->method('getMailRendered')
            ->will($this->returnValue($mailRendered));

        /* @var TemplateFilterInterface|\PHPUnit_Framework_MockObject_MockObject $templateFilter */
        $templateFilter = $this->getMockBuilder(TemplateFilterInterface::class)->getMock();
        $templateFilter->expects($this->once())
            ->method('supports')
            ->with($mailRendered)
            ->will($this->returnValue(true));

        $templateFilter->expects($this->once())
            ->method('filter')
            ->with($mailRendered);

        $this->registry->expects($this->once())
            ->method('getTemplateFilters')
            ->will($this->returnValue(array($templateFilter)));

        $this->listener->onPostRender($event);
    }

    public function testTransportFilters()
    {
        $transport = 'transport_test';
        $message = new \stdClass();

        /* @var MailRenderedInterface|\PHPUnit_Framework_MockObject_MockObject $mailRendered */
        $mailRendered = $this->getMockBuilder(MailRenderedInterface::class)->getMock();

        /* @var FilterPreSendEvent|\PHPUnit_Framework_MockObject_MockObject $event */
        $event = $this->getMockBuilder(FilterPreSendEvent::class)->disableOriginalConstructor()->getMock();
        $event->expects($this->atLeastOnce())
            ->method('getTransport')
            ->will($this->returnValue($transport));
        $event->expects($this->atLeastOnce())
            ->method('getMessage')
            ->will($this->returnValue($message));
        $event->expects($this->atLeastOnce())
            ->method('getMailRendered')
            ->will($this->returnValue($mailRendered));

        /* @var TransportFilterInterface|\PHPUnit_Framework_MockObject_MockObject $transportFilter */
        $transportFilter = $this->getMockBuilder(TransportFilterInterface::class)->getMock();
        $transportFilter->expects($this->once())
            ->method('supports')
            ->with($transport, $message, $mailRendered)
            ->will($this->returnValue(true));

        $transportFilter->expects($this->once())
            ->method('filter')
            ->with($transport, $message, $mailRendered);

        $this->registry->expects($this->once())
            ->method('getTransportFilters')
            ->will($this->returnValue(array($transportFilter)));

        $this->listener->onPreSend($event);
    }
}
