<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Recipe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Validator\Constraints\Image;

class RecipeType extends AbstractType
{
    public function __construct(private FormListenerFactory $ListenerFactory)
    {

    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'empty_data' => ''
            ])
            ->add('slug', TextType::class, [
                'required' => false,
            ])
            ->add('thumbnailFile', FileType::class )

            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',

            ])
            ->add('content', TextareaType::class, [
                'label' => 'Contenu',
                'empty_data' => ''
            ])
            ->add('duration')
            ->addEventListener(FormEvents::PRE_SUBMIT, $this->ListenerFactory->autoSlug('title'))
            ->addEventListener(FormEvents::POST_SUBMIT, $this->ListenerFactory->timestamps());
    }

    public function autoSlug(PreSubmitEvent $event): void
    {
        $data =$event->getData();
        if(empty($data['slug'])){
            $slugger = new AsciiSlugger();
            $data['slug'] = strtolower($slugger->slug($data['title']));
            $event->setData($data);
        }
    }

    public function attachTimestamps(PostSubmitEvent $event): void
    {
        $data = $event->getData();
        if (!($data instanceof Recipe)){
            return;
        }
        $data->getUpdatedAt(new \DateTimeImmutable());
        if (!$data->getId()){
            $data->getCreatedAt( new \DateTimeImmutable());
        }
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class
        ]);
    }
}
