<?php

namespace App\Form;

use App\Entity\ToDo;
use App\Repository\ChecklistRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class TodoType extends AbstractType
{
    private TokenStorageInterface $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('text')
            ->add('checklist', null, [
                'choice_label' => 'title',
                'query_builder' => function (ChecklistRepository $checklistRepository) {
                    return $checklistRepository->selectByUser($this->getUser());
                },
            ])
            ->add('users', null, [
                'choice_label' => 'username',
                'label' => 'Shared to users',
            ])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ToDo::class,
        ]);
    }

    private function getUser(): ?UserInterface
    {
        return $this->tokenStorage->getToken() ? $this->tokenStorage->getToken()->getUser() : null;
    }
}
