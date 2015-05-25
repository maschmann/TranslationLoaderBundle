<?php
/*
 * This file is part of the TranslationLoaderBundle package.
 *
 * (c) Marc Aschmann <maschmann@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Asm\TranslationLoaderBundle\Tests\Form\Type;

use Asm\TranslationLoaderBundle\Form\Type\TranslationType;
use Asm\TranslationLoaderBundle\Entity\Translation;
use Symfony\Component\Form\Test\TypeTestCase;


/**
 * Class TranslationTypeTest
 *
 * @author Marc Aschmann <maschmann@gmail.com>
 */
class TranslationTypeTest extends TypeTestCase
{
    /**
     * testSubmitValidData
     */
    public function testSubmitValidData()
    {
        $formData    = array(
            'messageDomain' => 'testdomain',
            'transKey'      => 'test.text',
            'translation'   => 'Some test text.',
            'transLocale'   => 'de_DE',
        );

        $translation = $this->createTestTranslation($formData);

        $type = new TranslationType();
        $form = $this->factory->create($type);

        // submit the data to the form directly
        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($translation, $form->getData());

        $view     = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }

    /**
     * @param $data
     * @return Translation
     */
    private function createTestTranslation($data)
    {
        $testObject =  new Translation();
        foreach ($data as $key => $value) {
            $testObject->{'set' . ucfirst($key)}($value);
        }

        return $testObject;
    }
}
