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
 * @since     28.04.2016
 * @link      http://github.com/heiglandreas/addUserToLdap
 */

namespace Org_Heigl\Wordpress\Plugins\AddUserToLdap\Options;

abstract class AbstractOption
{
    protected $input = '<input id="add-user-to-ldap-%1$s" name="add-user-to-ldap-%1$s" size="40" placeholder="%4$s" type="%3$s" value="%2$s" />';

    public function __construct($package, $section)
    {
        register_setting($package, 'add-user-to-ldap-' . $this->getName(), [$this, 'validate']);
        add_settings_field('add-user-to-ldap-' . $this->getName(), $this->getLabel(), [$this, 'echoFormField'], $package, $section);

    }
    public function getOption()
    {
        $option = get_option('add-user-to-ldap-' . $this->getName());
        if (! $option) {
            $option = $this->getDefault();
        }

        return $option;
    }

    /**
     * @return mixed
     */
    abstract public function getDefault();

    /**
     * @return string
     */
    abstract public function getName();

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    abstract public function validate($value);

    /**
     * @return string
     */
    abstract public function getLabel();

    /**
     * @return string
     */
    abstract public function getDescription();
    
    public function echoFormField($type = 'text')
    {
        echo sprintf(
            $this->input,
            $this->getName(),
            get_option('add-user-to-ldap-' . $this->getName()),
            $type,
            $this->getDefault()
        );
    }
}