<?php
/*
 * This file is part of the TranslationLoaderBundle package.
 *
 * (c) Marc Aschmann <maschmann@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asm\TranslationLoaderBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TranslationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (method_exists('Symfony\Component\Form\AbstractType', 'getBlockPrefix')) {
            $textTypeName = 'Symfony\Component\Form\Extension\Core\Type\TextType';
            $textareaTypeName = 'Symfony\Component\Form\Extension\Core\Type\TextareaType';
            $submitTypeName = 'Symfony\Component\Form\Extension\Core\Type\SubmitType';
        } else {
            $textTypeName = 'text';
            $textareaTypeName = 'textarea';
            $submitTypeName = 'submit';
        }

        $builder
            ->add('transLocale', $textTypeName, array('required' => true))
            ->add('messageDomain', $textTypeName, array('required' => true))
            ->add('transKey', $textTypeName, array('required' => true))
            ->add('translation', $textareaTypeName)
            ->add('save', $submitTypeName);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Asm\TranslationLoaderBundle\Entity\Translation',
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'asm_translation';
    }
}
