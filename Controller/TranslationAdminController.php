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
     *
     * @param string $domain
     * @param string $locale
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($domain=null, $locale=null)
    {
        $repository = $this->get('doctrine')
            ->getManager($this->container->getParameter('asm_translation_loader.database.entity_manager'))
            ->getRepository('AsmTranslationLoaderBundle:Translation');

        if (!empty($locale) || !empty($domain)) {
            if ($locale) {
                $params['transLocale'] = $locale;
            }
            if ($domain) {
                $params['messageDomain'] = $domain;
            }
            $translations = $repository->findBy($params);
        } else {
            $translations = $repository->findAll();
        }

        return $this->render(
            'AsmTranslationLoaderBundle:TranslationAdmin:show.html.twig',
            array(
                'translations' => $translations,
            )
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
