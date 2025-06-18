<?php

namespace App\Form;

use App\Entity\Task;
use App\Enum\TaskStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class TaskFormType extends AbstractType
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Tytuł',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Opis',
            ])
            ->add('deadline', DateType::class, [
                'label' => 'Termin zakończenia',
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('anti_duplicate_token', HiddenType::class, [
                'mapped' => false,
                'data' => $options['anti_duplicate_token'],
            ]);
            if ($options['is_edit']) {
                $builder->add('status', ChoiceType::class, [
                    'choices' => array_flip(TaskStatus::getAvailableStatuses($this->translator)),
                    'label' => 'Status',
                    'required' => true,
                ]);
            }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
            'anti_duplicate_token' => null,
            'is_edit' => false,
        ]);
    }
}