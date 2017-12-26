<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Component\Mailer\Util;

use Sonatra\Component\Mailer\MailTypes;
use Sonatra\Component\Mailer\Model\MailInterface;

/**
 * Utils for mail.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
abstract class MailUtil
{
    /**
     * Check if the mail template is valid.
     *
     * @param MailInterface $mail The mail template
     * @param string        $type The mail type defined in MailTypes::TYPE_*
     *
     * @return bool
     */
    public static function isValid(MailInterface $mail, $type)
    {
        $validTypes = static::getValidTypes($type);

        return $mail->isEnabled() && in_array($mail->getType(), $validTypes);
    }

    /**
     * Get the valid mail types.
     *
     * @param string $type The mail type defined in MailTypes::TYPE_*
     *
     * @return string[]
     */
    public static function getValidTypes($type)
    {
        if (MailTypes::TYPE_PRINT === $type) {
            return array(MailTypes::TYPE_ALL, MailTypes::TYPE_PRINT);
        } elseif (MailTypes::TYPE_SCREEN === $type) {
            return array(MailTypes::TYPE_ALL, MailTypes::TYPE_SCREEN);
        }

        return array(MailTypes::TYPE_ALL, MailTypes::TYPE_PRINT, MailTypes::TYPE_SCREEN);
    }

    /**
     * Check if the html body has already a layout template.
     *
     * @param string $htmlBody The HTML body
     *
     * @return bool
     */
    public static function isRootBody($htmlBody)
    {
        return preg_match('%(<html[^>]*>)%im', $htmlBody, $regs);
    }
}
