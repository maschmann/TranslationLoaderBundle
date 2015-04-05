<?php
/*
 * This file is part of the AsmTranslationLoaderBundle package.
 *
 * (c) Marc Aschmann <maschmann@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asm\TranslationLoaderBundle\Controller;

use Asm\TranslationLoaderBundle\Entity\Translation;
use Asm\TranslationLoaderBundle\Model\TranslationManager;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

/**
 * Class TranslationController
 *
 * @package TranslationLoaderBundle\Controller
 * @author Marc Aschmann <maschmann@gmail.com>
 */
class TranslationController //extends Controller
{
    /**
     * @var FormFactory
     */
    private $formFactory;

    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var TranslationManager
     */
    private $translationManager;

    /**
     * @param EngineInterface $templating
     * @param TranslationManager $translationManager
     * @param FormFactory $formFactory
     */
    public function __construct(
        EngineInterface $templating,
        TranslationManager $translationManager,
        FormFactory $formFactory
    ) {
        $this->formFactory = $formFactory;
        $this->templating = $templating;
        $this->translationManager = $translationManager;
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request)
    {
        $translations = $this
            ->translationManager
            ->getTranslationList(
                $request->query->all()
            );

        if ($request->isXmlHttpRequest()) {
            $template = 'AsmTranslationLoaderBundle:Partial:list-tbl.html.twig';
        } else {
            $template = 'AsmTranslationLoaderBundle:Translation:list.html.twig';
        }

        return $this->templating->renderResponse(
            $template,
            array(
                'translations' => $translations,
            )
        );
    }

    /**
     * @param string $key
     * @param string $locale
     * @param string $domain
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function formAction($key = '', $locale = '', $domain = '')
    {
        $action = 'asm_translation_loader.admin.update';
        $translation = $this->translationManager
            ->findTranslationBy(
                array(
                    'transKey' => $key,
                    'transLocale' => $locale,
                    'messageDomain' => $domain,
                )
            );

        if (empty($translation)) {
            $translation = new Translation();
            $action = 'asm_translation_loader.admin.create';
        }

        $form = $this->formFactory->create('asm_translation', $translation, array());

        return $this->templating->renderResponse(
            'AsmTranslationLoaderBundle:Translation:form.html.twig',
            array(
                'form' => $form->createView(),
                'action' => $action,
            )
        );
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function createAction(Request $request)
    {
        return $this->handleForm('create', $request);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function updateAction(Request $request)
    {
        return $this->handleForm('update', $request);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function deleteAction(Request $request)
    {
        $error = array();
        $status = 200;

        $manager = $this->translationManager;
        $translation = $manager->findTranslationBy(
            array(
                'transKey' => $request->request->get('key'),
                'transLocale' => $request->request->get('locale'),
                'messageDomain' => $request->request->get('domain'),
            )
        );

        if (!empty($translation)) {
            $manager->removeTranslation($translation);
        } else {
            $error['message'] = 'translation not found ' . json_encode($request->request->all());
            $status = 404;
        }

        return new JsonResponse(
            array_merge(
                array(
                    'status' => $status,
                ),
                $error
            ),
            $status
        );
    }

    /**
     * @param string $type
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function handleForm($type, $request)
    {
        $error = array();
        $form = $this->formFactory->create('asm_translation', new Translation(), array());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $status = 200;
            $manager = $this->translationManager;

            if ('update' == $type) {
                /** @var \Asm\TranslationLoaderBundle\Entity\Translation $update */
                $update = $form->getData();
                // get translation from database again to keep date_created
                $translation = $manager->findTranslationBy(
                    array(
                        'transKey' => $update->getTransKey(),
                        'transLocale' => $update->getTransLocale(),
                        'messageDomain' => $update->getMessageDomain(),
                    )
                );

                $translation
                    ->setTransKey($update->getTransKey())
                    ->setTransLocale($update->getTransLocale())
                    ->setMessageDomain($update->getMessageDomain())
                    ->setTranslation($update->getTranslation());

                $manager->updateTranslation($translation);
            } else {
                $translation = $form->getData();
                $translation->setDateCreated(new \DateTime());
                try {
                    $manager->updateTranslation($translation);
                    $error = '';
                } catch (\Exception $e) {
                    $error = $e->getMessage();
                }
            }

            $response = $this->templating->renderResponse(
                'AsmTranslationLoaderBundle:Translation:result.html.twig',
                array(
                    'error' => $error,
                )
            );
        } else {
            $response = $this->templating->renderResponse(
                'AsmTranslationLoaderBundle:Translation:form.html.twig',
                array(
                    'form' => $form->createView(),
                )
            );
        }

        return $response;
    }
}
