<?php
/**
 * Copyright (c) 2015-2015 Andreas Heigl<andreas@heigl.org>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @author    Andreas Heigl<andreas@heigl.org>
 * @copyright 2015-2015 Andreas Heigl
 * @license   http://www.opensource.org/licenses/mit-license.php MIT-License
 * @version   0.0
 * @since     06.11.2015
 * @link      http://github.com/heiglandreas/wp_talktocomposer
 */
namespace Org_Heigl\Wordpress\Plugins\AddUserToLdap;

use Org_Heigl\Wordpress\Plugins\AddUserToLdap\PersistenceMapper\UserMapper;

class AddUserToLdap
{
    public function __construct()
    {
        add_action('admin_menu', [
            '\Org_Heigl\Wordpress\Plugins\AddUserToLdap\OptionManagement',
            'addAdminPage'
        ]);
        add_action('admin_init', [
            '\Org_Heigl\Wordpress\Plugins\AddUserToLdap\OptionManagement',
            'factory'
        ]);
        add_action('', [$this, 'addUser']);
    }

    public function addUser($userid)
    {
        $newUser = new User();
        $user    = $this->fetchUserFromDatabase($userid);
        $user    = $this->addDataToUser($user, $newUser);
        $this->persistUser($user, $this->getPersistenceLayer());
    }

    /**
     * @param int $userId
     *
     * @throws \UnexpectedValueException
     * @return array
     */
    public function fetchUserFromDatabase($userId)
    {
        /* @var WP_User $user */
        $user = get_user_by('id', $userId);
        if (false === $user) {
            throw new \UnexpectedValueException(sprintf(
                'No User with ID %1$s found',
                $userId
            ));
        }

        return [
            'username'    => $user->user_login,
            'firstname'   => $user->user_firstname,
            'lastname'    => $user->user_lastname,
            'description' => $user->user_description,
            'email'       => $user->user_email,
            'web'         => $user->user_url,
            'realname'    => $user->display_name,
        ];
    }

    /**
     * @param array $userdata The data of the WP-User
     * @param User  $user     The new User-object
     *
     * @return User
     */
    public function addDataToUser($user, $newUser)
    {

    }

    /**
     * @param User                      $user             THe user-object to persist
     * @param PErsistenceLayerInterface $persistenceLayer the Persistence-Layer
     *
     * @return boolean
     */
    public function persistUser(
        User $user,
        PersistenceLayerInterface $persistenceLayer
    )
    {
        return $persistenceLayer->store(new UserMapper($user)))
    }

    /**
     * @return PersistenceLayerInterface
     */
    public function getPersistenceLayer()
    {
        return new PersistenceLayer\Ldap($config);
    }
}
