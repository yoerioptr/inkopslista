<?php

namespace App\Form;

use App\Entity\BasketItem;
use App\Entity\Product;
use App\Enum\Unit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class BasketItemType extends AbstractType
{
    #[\Override]
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('product', EntityType::class, [
            'class' => Product::class,
            'choice_label' => 'name',
        ]);
        $builder->add('amount', NumberType::class);
        $builder->add('unit', EnumType::class, [
            'class' => Unit::class,
            'choice_label' => fn(Unit $unit) => $unit->label(),
        ]);
    }

    #[\Override]
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BasketItem::class,
        ]);
    }
}
