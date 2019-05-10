<?php

/*
 * This file is part of the Fxp package.
 *
 * (c) François Pluchino <francois.pluchino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fxp\Component\Mailer\Loader;

use Fxp\Component\Mailer\Model\MailInterface;
use Fxp\Component\Mailer\Model\MailTranslationInterface;
use Fxp\Component\Mailer\Model\TwigMail;
use Fxp\Component\Mailer\Model\TwigMailTranslation;
use Fxp\Component\Mailer\Util\ConfigUtil;

/**
 * Twig File mail loader.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class TwigMailLoader extends AbstractFileMailLoader
{
    /**
     * {@inheritdoc}
     */
    protected function createMail(array $config): MailInterface
    {
        /** @var TwigMail $mail */
        $mail = parent::createMail($config);
        $mail->setFile(ConfigUtil::getValue($config, 'file'));

        return $mail;
    }

    /**
     * {@inheritdoc}
     */
    protected function createMailTranslation(MailInterface $mail, array $config): MailTranslationInterface
    {
        /** @var TwigMailTranslation $translation */
        $translation = parent::createMailTranslation($mail, $config);
        $translation->setFile(ConfigUtil::getValue($config, 'file'));

        return $translation;
    }

    /**
     * {@inheritdoc}
     */
    protected function newMailInstance(): MailInterface
    {
        return new TwigMail();
    }

    /**
     * {@inheritdoc}
     */
    protected function newMailTranslationInstance(MailInterface $mail): MailTranslationInterface
    {
        return new TwigMailTranslation($mail);
    }
}
