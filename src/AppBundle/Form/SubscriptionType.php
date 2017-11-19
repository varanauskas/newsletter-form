<?php

namespace AppBundle\Form;

use AppBundle\Entity\Subscription;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SubscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('email', EmailType::class)
            ->add('categories', ChoiceType::class, [
                'choices' => $options['categoryData']->findAll(),
                'choice_label' => 'name',
                'choice_value' => 'slug',
                'expanded' => true,
                'multiple' => true
            ])
            ->add('save',  SubmitType::class, $options['save']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(['data-class' => Subscription::class])
            ->setRequired('categoryData')
            ->setRequired('save');
    }
}