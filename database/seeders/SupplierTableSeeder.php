<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierTableSeeder extends Seeder
{
    public function run()
    {
        
        
        // Add some well-known fashion brands (optional)
        $premiumBrands = [
            // Philippine-based fashion suppliers
            [
                'brand_name' => 'Bench',
                'email' => 'info@bench.com.ph',
                'phone' => '+63281234567',
                'address' => '3/F Bench Tower, Bonifacio Global City, Taguig, Philippines',
                
            ],
            [
                'brand_name' => 'Penshoppe',
                'email' => 'customercare@penshoppe.com',
                'phone' => '+63282345678',
                'address' => 'Penshoppe Building, Cebu City, Philippines',
                
            ],
            [
                'brand_name' => 'Kamiseta',
                'email' => 'inquiries@kamiseta.com.ph',
                'phone' => '+63283456789',
                'address' => '123 Ayala Avenue, Makati City, Philippines',
                
            ],
            [
                'brand_name' => 'Bayo',
                'email' => 'contact@bayo.com.ph',
                'phone' => '+63284567890',
                'address' => '456 Shaw Boulevard, Mandaluyong City, Philippines',
                
            ],
            [
                'brand_name' => 'Folded & Hung',
                'email' => 'info@foldedandhung.com',
                'phone' => '+63285678901',
                'address' => '789 Ortigas Center, Pasig City, Philippines',
                
            ],
            [
                'brand_name' => 'Human',
                'email' => 'support@human.com.ph',
                'phone' => '+63286789012',
                'address' => '321 Bonifacio High Street, Taguig City, Philippines',
                
            ],
            [
                'brand_name' => 'Freego',
                'email' => 'hello@freego.ph',
                'phone' => '+63287890123',
                'address' => '555 EDSA, Quezon City, Philippines',
                
            ],
            [
                'brand_name' => 'Collezione',
                'email' => 'contact@collezione.com.ph',
                'phone' => '+63288901234',
                'address' => '222 Alabang-Zapote Road, Muntinlupa City, Philippines',
        
            ],
            [
                'brand_name' => 'Team Manila',
                'email' => 'info@teammanila.com.ph',
                'phone' => '+63289012345',
                'address' => '111 Maginhawa Street, Quezon City, Philippines',
      
            ],
            [
                'brand_name' => 'Rusty Lopez',
                'email' => 'customerservice@rustylopez.com',
                'phone' => '+63280123456',
                'address' => '444 Roxas Boulevard, Pasay City, Philippines',
              
            ],

            // International fashion brands
            [
                'brand_name' => 'Zara',
                'email' => 'ph.zara@inditex.com',
                'phone' => '+34912345678',
                'address' => 'Avenida de la Diputación, Arteixo, Spain',
             
            ],
            [
                'brand_name' => 'H&M',
                'email' => 'customer.service@hm.com',
                'phone' => '+4687965500',
                'address' => 'Mäster Samuelsgatan 46, Stockholm, Sweden',
            
            ],
            [
                'brand_name' => 'Uniqlo',
                'email' => 'support@uniqlo.com',
                'phone' => '+81368311111',
                'address' => '7-7-4 Ginza, Chuo-ku, Tokyo, Japan',
              
            ],
            [
                'brand_name' => 'Gucci',
                'email' => 'client.services@gucci.com',
                'phone' => '+3905527591',
                'address' => 'Via de\' Tornabuoni 73/r, Florence, Italy',

            ],
            [
                'brand_name' => 'Louis Vuitton',
                'email' => 'contact@louisvuitton.com',
                'phone' => '+33145568000',
                'address' => '2 Rue du Pont Neuf, Paris, France',
                
            ],
            [
                'brand_name' => 'Nike',
                'email' => 'help@nike.com',
                'phone' => '+15034264400',
                'address' => 'One Bowerman Drive, Beaverton, OR, USA',
               
            ],
            [
                'brand_name' => 'Adidas',
                'email' => 'customer.service@adidas.com',
                'phone' => '+496947940',
                'address' => 'Adi-Dassler-Straße 1, Herzogenaurach, Germany',
              
            ],
            [
                'brand_name' => 'Prada',
                'email' => 'customercare@prada.com',
                'phone' => '+39024765561',
                'address' => 'Via Antonio Fogazzaro 28, Milan, Italy',
                
            ],
            [
                'brand_name' => 'Chanel',
                'email' => 'contact@chanel.com',
                'phone' => '+33144775000',
                'address' => '135 Avenue Charles de Gaulle, Neuilly-sur-Seine, France',
             
            ],
            [
                'brand_name' => 'Calvin Klein',
                'email' => 'customercare@calvinklein.com',
                'phone' => '+12125947000',
                'address' => '205 W 39th St, New York, NY, USA',
                
            ],
        ]; 
        
        foreach ($premiumBrands as $brand) {
            Supplier::firstOrCreate(
                ['email' => $brand['email']],
                $brand
            );
        }
    }
}