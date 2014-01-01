<?php

namespace Asm\TranslationLoaderBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class TranslationAdminController
 *
 * @package Asm\TranslationLoaderBundle\Controller
 * @author marc aschmann <maschmann@gmail.com>
 * @uses Symfony\Bundle\FrameworkBundle\Controller\Controller
 */
class TranslationAdminController extends Controller
{
    /**
     * default page
     */
    public function showAction()
    {
        $translations = $this->get('doctrine')
            ->getManager($this->getContainer()->getParameter('asm_translation_loader.database.entity_manager'))
            ->getRepository('AsmTranslationLoaderBundle:Translation')
            ->findAll();

        return $this->render(
            'AsmTranslationLoaderBundle:TranslationAdmin:show.html.twig',
            array()
        );
    }


    public function createAction()
    {
        return $this->render(
            'AsmTranslationLoaderBundle:TranslationAdmin:create.html.twig',
            array()
        );
    }


    public function editAction()
    {
        return $this->render(
            'AsmTranslationLoaderBundle:TranslationAdmin:edit.html.twig',
            array()
        );
    }


    public function deleteAction()
    {
        return $this->render(
            'AsmTranslationLoaderBundle:TranslationAdmin:delete.html.twig',
            array()
        );
    }
}
