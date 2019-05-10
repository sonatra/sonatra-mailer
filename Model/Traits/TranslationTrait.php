<?php

/*
 * This file is part of the Fxp package.
 *
 * (c) François Pluchino <francois.pluchino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fxp\Component\Mailer\Model\Traits;

use Fxp\Component\Mailer\Model\LayoutInterface;
use Fxp\Component\Mailer\Model\MailInterface;
use Fxp\Component\Mailer\Util\TranslationUtil;

/**
 * Trait for translation model.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
trait TranslationTrait
{
    /**
     * @var array
     */
    protected $cacheTranslation = [];

    /**
     * @var null|string
     */
    protected $translationDomain;

    /**
     * {@inheritdoc}
     */
    public function getTranslation(string $locale): self
    {
        $locale = strtolower($locale);

        if (isset($this->cacheTranslation[$locale])) {
            return $this->cacheTranslation[$locale];
        }

        /** @var LayoutInterface|MailInterface|TranslationTrait $this */
        $self = clone $this;

        if (!TranslationUtil::find($self, $locale) && false !== ($pos = strrpos($locale, '_'))) {
            TranslationUtil::find($self, substr($locale, 0, $pos));
        }

        $this->cacheTranslation[$locale] = $self;

        return $self;
    }

    /**
     * {@inheritdoc}
     */
    public function setTranslationDomain(?string $domain): self
    {
        $this->translationDomain = $domain;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTranslationDomain(): ?string
    {
        return $this->translationDomain;
    }
}
