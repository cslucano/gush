<?php

/*
 * This file is part of Gush.
 *
 * (c) Luis Cordova <cordoval@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Gush\Tests\Helper;

use Gush\Helper\TemplateHelper;

class TemplateHelperTest extends \PHPUnit_Framework_TestCase
{
    protected $helper;

    public function setUp()
    {
        $this->dialog = $this->getMock('Symfony\Component\Console\Helper\DialogHelper');
        $this->helper = new TemplateHelper($this->dialog);
    }


    public function provideGetHelper()
    {
        return [
            [ 'pull-request', 'symfony' ],
        ];
    }

    /**
     * @dataProvider provideGetHelper
     */
    public function testGetHelper($domain, $name)
    {
        $res = $this->helper->getTemplate($domain, $name);
        $this->assertNotNull($res);
        $this->assertInstanceof('Gush\Template\TemplateInterface', $res);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage has not been registered
     */
    public function testGetHelperInvalid()
    {
        $res = $this->helper->getTemplate('foobar', 'barfoo');
    }
}
