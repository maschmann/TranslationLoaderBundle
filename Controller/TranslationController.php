<?php
/*
 * This file is part of the <package> package.
 *
 * (c) Marc Aschmann <maschmann@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asm\TranslationLoaderBundle\Controller;

use Asm\TranslationLoaderBundle\Entity\Translation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TranslationController
 *
 * @package TranslationLoaderBundle\Controller
 * @author Marc Aschmann <maschmann@gmail.com>
 */
class TranslationController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request)
    {
        $translations = $this->get('asm_translation_loader.translation_manager')
            ->findAllTranslations();

        if ($request->isXmlHttpRequest()) {
            $data = array();
            /** @var \Asm\TranslationLoaderBundle\Model\TranslationInterface $translation */
            foreach ($translations as $translation) {
                $tmp = $translation->toArray();
                $tmp['dateCreated'] = date('Y-m-d', $tmp['dateCreated']);
                $tmp['dateUpdated'] = date('Y-m-d', $tmp['dateUpdated']);
                $data[] = $tmp;
            }

            $response = new JsonResponse(
                array(
                    'translations' => $data,
                    'status' => 200,
                )
            );
        } else {
            $response = $this->render(
                'AsmTranslationLoaderBundle:Translation:list.html.twig',
                array(
                    'translations' => $translations,
                )
            );
        }

        return $response;
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
        $translation = $this->get('asm_translation_loader.translation_manager')
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

        $form = $this->createForm('asm_translation', $translation);

        return $this->render(
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

        $manager = $this->get('asm_translation_loader.translation_manager');
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
        $form = $this->createForm('asm_translation', new Translation());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $status = 200;
            $manager = $this->get('asm_translation_loader.translation_manager');

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
                $manager->updateTranslation($translation);
            }

            $response = $this->render(
                'AsmTranslationLoaderBundle:Translation:success.html.twig',
                array()
            );
        } else {
            $response = $this->render(
                'AsmTranslationLoaderBundle:Translation:form.html.twig',
                array(
                    'form' => $form->createView(),
                )
            );
        }

        return $response;
    }
}
