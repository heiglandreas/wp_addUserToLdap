<?php
/**
 * Copyright (c) 2016-2016} Andreas Heigl<andreas@heigl.org>
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @author    Andreas Heigl<andreas@heigl.org>
 * @copyright 2016-2016 Andreas Heigl
 * @license   http://www.opensource.org/licenses/mit-license.php MIT-License
 * @version   0.0
 * @since     27.04.2016
 * @link      http://github.com/heiglandreas/addUserToLdap
 */

namespace Org_Heigl\Wordpress\Plugins\AddUserToLdap;

use Org_Heigl\Wordpress\Plugins\AddUserToLdap\Options\EmailMapper;
use Org_Heigl\Wordpress\Plugins\AddUserToLdap\Options\HashAlgorithm;
use Org_Heigl\Wordpress\Plugins\AddUserToLdap\Options\Password;
use Org_Heigl\Wordpress\Plugins\AddUserToLdap\Options\PasswordMapper;
use Org_Heigl\Wordpress\Plugins\AddUserToLdap\Options\RealnameMapper;
use Org_Heigl\Wordpress\Plugins\AddUserToLdap\Options\Uri;
use Org_Heigl\Wordpress\Plugins\AddUserToLdap\Options\User;
use Org_Heigl\Wordpress\Plugins\AddUserToLdap\Options\UsernameMapper;
use Org_Heigl\Wordpress\Plugins\AddUserToLdap\Options\UsersDN;
use Org_Heigl\Wordpress\Plugins\AddUserToLdap\Options\WebsiteMapper;

require_once __DIR__. '/Options/AbstractOption.php';
require_once __DIR__. '/Options/EmailMapper.php';
require_once __DIR__. '/Options/HashAlgorithm.php';
require_once __DIR__. '/Options/Password.php';
require_once __DIR__. '/Options/PasswordMapper.php';
require_once __DIR__. '/Options/RealnameMapper.php';
require_once __DIR__. '/Options/Uri.php';
require_once __DIR__. '/Options/User.php';
require_once __DIR__. '/Options/UsernameMapper.php';
require_once __DIR__. '/Options/UsersDN.php';
require_once __DIR__. '/Options/WebsiteMapper.php';

class OptionManagement
{
    public static function addAdminPage()
    {
        add_options_page('Add user to LDAP', 'Add user to LDAP', 'manage_options', 'add-user-to-ldap', ['\Org_Heigl\Wordpress\Plugins\AddUserToLdap\OptionManagement', 'optionsPage']);
    }

    public static function optionsPage()
    {
        ?>
        <div>
            <h2>Add User to LDAP</h2>
            <form action="options.php" method="post">
                <?php settings_fields('add-user-to-ldap'); ?>
                <?php do_settings_sections('add-user-to-ldap'); ?>

                <input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
            </form></div>

        <?php
    }

    public static function factory()
    {
        return new self();
    }

    public function __construct()
    {
        $package = 'add-user-to-ldap';
        add_settings_section('main', 'Main Settings', [$this, 'getOptionText'], $package);
        add_settings_section('mapping', 'Info-Mapping Settings', [$this, 'getInfoMappingText'], $package);

        new Uri($package, 'main');
        new User($package, 'main');
        new Password($package, 'main');
        new UsersDN($package, 'main');
        new HashAlgorithm($package, 'main');
        
        new UsernameMapper($package, 'mapping');
        new RealnameMapper($package, 'mapping');
        new PasswordMapper($package, 'mapping');
        new EmailMapper($package, 'mapping');
        new WebsiteMapper($package, 'mapping');
    }


    public function getOptionText()
    {
        echo '<p>Some info</p>';
    }

    public function getInfoMappingText()
    {
        echo '<p>Which LDAP-Attributes shall contain these informations</p>';
    }

}
