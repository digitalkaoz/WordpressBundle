<?php

/**
 * Contains the WordpressCookieAuthenticationProvider class, part of the Symfony2 Wordpress Bundle
 *
 * @author     Miquel Rodríguez Telep / Michael Rodríguez-Torrent <mike@themikecam.com>
 * @author     Ka Yue Yeung
 * @package    Hypebeast\WordpressBundle
 * @subpackage Security\Authentication\Provider
 */

namespace Hypebeast\WordpressBundle\Security\Authentication\Provider;

use Hypebeast\WordpressBundle\Security\Authentication\Token\WordpressCookieToken;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Hypebeast\WordpressBundle\Wordpress\ApiAbstraction;
use Hypebeast\WordpressBundle\Security\User\WordpressUser;

/**
 * WordpressCookieAuthenticationProvider will verify that the current user has been authenticated
 * in Wordpress
 *
 * @package    Hypebeast\WordpressBundle
 * @subpackage Security\Authentication\Provider
 * @author     Miquel Rodríguez Telep / Michael Rodríguez-Torrent <mike@themikecam.com>
 * @author     Ka Yue Yeung
 */
class WordpressCookieAuthenticationProvider implements AuthenticationProviderInterface
{
    /**
     * An abstraction layer for the Wordpress API
     *
     * @var ApiAbstraction
     */
    protected $api;

    /**
     * Constructor
     *
     * @param ApiAbstraction $api 
     */
    public function __construct(ApiAbstraction $api)
    {
        $this->api = $api;
    }

    public function authenticate(TokenInterface $token)
    {
        $wpUser = $this->api->wp_get_current_user();
        if ($wpUser->ID != 0) {
            return new WordpressCookieToken(new WordpressUser($wpUser));
        }

        throw new AuthenticationException('The Wordpress authentication failed.');
    }

    /**
     * Checks whether this provider supports the given token.
     *
     * @param TokenInterface $token A TokenInterface instance
     *
     * @return Boolean true if the implementation supports the Token, false otherwise
     */
    public function supports(TokenInterface $token)
    {
        return $token instanceof WordpressCookieToken;
    }
}