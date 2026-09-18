<?php

namespace Database\Seeders;

use App\Models\Tools\Amenity;
use Illuminate\Database\Seeder;

class AmenitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $amenities = [
            ['name' => 'Wi-Fi gratuit', 'slug' => 'wifi-gratuit', 'icon' => 'wifi', 'description' => 'Connexion Wi-Fi gratuite pour les visiteurs.'],
            ['name' => 'Parking', 'slug' => 'parking', 'icon' => 'parking', 'description' => 'Places de stationnement disponibles à proximité.'],
            ['name' => 'Piscine', 'slug' => 'piscine', 'icon' => 'swimming-pool', 'description' => 'Piscine accessible aux clients selon les conditions de l’établissement.'],
            ['name' => 'Climatisation', 'slug' => 'climatisation', 'icon' => 'air-conditioning', 'description' => 'Espace équipé de la climatisation.'],
            ['name' => 'Restaurant', 'slug' => 'restaurant', 'icon' => 'restaurant', 'description' => 'Service de restauration sur place.'],
            ['name' => 'Salle de sport', 'slug' => 'salle-de-sport', 'icon' => 'gym', 'description' => 'Équipements disponibles pour la pratique sportive.'],
            ['name' => 'Spa', 'slug' => 'spa', 'icon' => 'spa', 'description' => 'Soins de bien-être, massages ou espace détente.'],
            ['name' => 'Réception 24 h/24', 'slug' => 'reception-24h', 'icon' => 'front-desk', 'description' => 'Accueil disponible à toute heure.'],
            ['name' => 'Service en chambre', 'slug' => 'service-en-chambre', 'icon' => 'room-service', 'description' => 'Plats et boissons servis dans la chambre ou l’espace privé.'],
            ['name' => 'Terrasse', 'slug' => 'terrasse', 'icon' => 'terrace', 'description' => 'Espace extérieur aménagé pour s’installer.'],
            ['name' => 'Accessible aux personnes à mobilité réduite', 'slug' => 'accessible-pmr', 'icon' => 'wheelchair', 'description' => 'Accès et équipements adaptés aux personnes à mobilité réduite.'],
            ['name' => 'Bar', 'slug' => 'bar', 'icon' => 'bar', 'description' => 'Bar proposant des boissons sur place.'],
            ['name' => 'Vue sur l’océan', 'slug' => 'vue-sur-locean', 'icon' => 'ocean-view', 'description' => 'Vue dégagée sur l’océan ou le littoral.'],
            ['name' => 'Places assises en extérieur', 'slug' => 'places-assises-exterieur', 'icon' => 'outdoor-seating', 'description' => 'Tables ou assises disponibles en extérieur.'],
            ['name' => 'Adapté aux familles', 'slug' => 'adapte-aux-familles', 'icon' => 'family-friendly', 'description' => 'Lieu adapté à l’accueil des familles.'],
            ['name' => 'Espace enfants', 'slug' => 'espace-enfants', 'icon' => 'kids-area', 'description' => 'Espace ou équipements prévus pour les enfants.'],
            ['name' => 'Douches', 'slug' => 'douches', 'icon' => 'showers', 'description' => 'Douches disponibles pour les visiteurs ou les pratiquants.'],
            ['name' => 'Casiers', 'slug' => 'casiers', 'icon' => 'lockers', 'description' => 'Casiers disponibles pour ranger les effets personnels.'],
            ['name' => 'Location de matériel', 'slug' => 'location-de-materiel', 'icon' => 'equipment-rental', 'description' => 'Matériel disponible à la location sur place.'],
            ['name' => 'À emporter', 'slug' => 'a-emporter', 'icon' => 'takeaway', 'description' => 'Commandes préparées à emporter.'],
            ['name' => 'Livraison', 'slug' => 'livraison', 'icon' => 'delivery', 'description' => 'Service de livraison disponible selon la zone.'],
        ];

        foreach ($amenities as $amenity) {
            Amenity::query()->updateOrCreate(
                ['slug' => $amenity['slug']],
                array_merge($amenity, ['is_active' => true, 'is_valid' => true]),
            );
        }
    }
}
