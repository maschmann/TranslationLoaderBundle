<?php
/*
 * This file is part of the <package> package.
 *
 * (c) Marc Aschmann <maschmann@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TranslationLoaderBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TranslationController
 *
 * @package TranslationLoaderBundle\Controller
 * @author Marc Aschmann <maschmann@gmail.com>
 */
class TranslationController extends Controller
{
    public function indexAction(Request $request)
    {
        return $this->render(
            'Hello/Greetings/index.html.twig',
            array(
                'name' => $name,
            )
        );
    }

    public function createAction(Request $request)
    {
    }

    public function readAction($transKey, $transLocale, $messageDomain, Request $request)
    {
    }

    public function updateAction(Request $request)
    {
    }

    public function deleteAction($transKey, $transLocale, $messageDomain, Request $request)
    {
    }
}
