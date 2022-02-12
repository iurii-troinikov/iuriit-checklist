<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\ToDo;
use App\Entity\User;
use App\Enum\RolesEnum;
use App\Repository\ChecklistRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PreSetDataEvent;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

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
            ]);
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            [$this, 'onPreSetData']
        );
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ToDo::class,
            'empty_data' => static function (FormInterface $form) {
                return new ToDo(
                    $form->get('text')->getData(),
                    $form->get('checklist')->getData(),
                );
            },
        ]);
    }
    private function getUser(): ?User
    {
        return $this->tokenStorage->getToken() ? $this->tokenStorage->getToken()->getUser() : null;
    }
    public function onPreSetData(PreSetDataEvent $event): void
    {
        $form =  $event->getForm();

        if (!$this->getUser()->hasRole(RolesEnum::ADMIN)) {
            $form->remove('users');
        }

        /** @var ToDo $data */
        if (!$data = $event->getData()) {
            return;
        }

        if ($data->getOwner() !== $this->getUser()) {
            $form->remove('checklist');
        }
    }
}
