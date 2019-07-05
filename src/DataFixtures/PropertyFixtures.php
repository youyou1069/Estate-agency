<?php

namespace App\DataFixtures;

use App\Entity\Property;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class PropertyFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        //Créer  des proprietes
        for ($i = 1; $i <= 100; $i++) {
            $property = new Property();
            $property->setName($faker->sentence())
                ->setPrice($faker->numberBetween(100000, 5000000))
                ->setDescription($faker->paragraph())
                ->setSurface($faker->numberBetween(20, 250))
                ->setSold(0)
                ->setFloor($faker->numberBetween('0', '15'))
                ->setRooms($faker->numberBetween('2', '10'))
                ->setBadrooms($faker->numberBetween('1', '9'))
                ->setAddress($faker->address)
                ->setCity($faker->city)
                ->setHeat($faker->numberBetween(0, count(Property::HEAT)-1))
                ->setPostalCode($faker->postcode);
            $property->setCreatedAt($faker->dateTimeBetween('-5 years'));
            $property->setImage($faker->imageUrl());


            $manager->persist($property);


        }
        $manager->flush();
    }
}
