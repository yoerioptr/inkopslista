<?php

namespace App\Form;

use App\Entity\BasketItem;
use App\Enum\Unit;
use App\Form\DataTransformer\ProductTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class BasketItemType extends AbstractType
{
    public function __construct(
        private readonly ProductTransformer $productTransformer,
    ) {
        //
    }

    #[\Override]
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('product', TextType::class);
        $builder->add('amount', NumberType::class);
        $builder->add('unit', EnumType::class, [
            'class' => Unit::class,
            'choice_label' => fn(Unit $unit) => $unit->label(),
        ]);

        $builder->get('product')->addModelTransformer($this->productTransformer);
    }

    #[\Override]
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BasketItem::class,
        ]);
    }
}
