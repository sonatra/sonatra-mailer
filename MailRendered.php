<?php

/*
 * This file is part of the Fxp package.
 *
 * (c) François Pluchino <francois.pluchino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fxp\Component\Mailer;

use Fxp\Component\Mailer\Model\TemplateMailInterface;

/**
 * The mail rendered.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class MailRendered implements MailRenderedInterface
{
    /**
     * @var TemplateMailInterface
     */
    protected $template;

    /**
     * @var null|string
     */
    protected $subject;

    /**
     * @var null|string
     */
    protected $htmlBody;

    /**
     * @var null|string
     */
    protected $body;

    /**
     * Constructor.
     *
     * @param TemplateMailInterface $template The mail template
     * @param null|string           $subject  The subject rendered
     * @param null|string           $htmlBody The HTML body rendered
     * @param null|string           $body     The body rendered
     */
    public function __construct(TemplateMailInterface $template, ?string $subject, ?string $htmlBody, ?string $body)
    {
        $this->template = $template;
        $this->subject = $subject;
        $this->htmlBody = $htmlBody;
        $this->body = $body;
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplate(): TemplateMailInterface
    {
        return $this->template;
    }

    /**
     * {@inheritdoc}
     */
    public function setSubject(?string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubject(): ?string
    {
        return $this->subject;
    }

    /**
     * {@inheritdoc}
     */
    public function setHtmlBody(?string $htmlBody): self
    {
        $this->htmlBody = $htmlBody;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getHtmlBody(): ?string
    {
        return $this->htmlBody;
    }

    /**
     * {@inheritdoc}
     */
    public function setBody(?string $body): self
    {
        $this->body = $body;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getBody(): ?string
    {
        return $this->body;
    }
}
