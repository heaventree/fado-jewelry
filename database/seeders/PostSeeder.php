<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        Post::firstOrCreate(
            ['slug' => 'the-art-of-irish-jewellery-making'],
            [
                'title'        => 'The Art of Irish Jewellery Making',
                'excerpt'      => 'Discover the centuries-old craft traditions behind every piece of FADÓ jewellery.',
                'body'         => "Ireland has a rich tradition of jewellery making that stretches back thousands of years. From the ancient gold torcs of the Bronze Age to the iconic Claddagh ring, Irish craftspeople have always expressed their culture through beautiful metalwork.\n\nAt FADÓ, we honour this tradition by working with skilled artisans who combine time-honoured techniques with contemporary design. Every piece is crafted with care and attention to detail.",
                'author'       => 'FADÓ Team',
                'published_at' => now(),
                'sort_order'   => 1,
            ]
        );

        Post::firstOrCreate(
            ['slug' => 'how-to-choose-the-perfect-claddagh-ring'],
            [
                'title'        => 'How to Choose the Perfect Claddagh Ring',
                'excerpt'      => "The Claddagh ring is one of Ireland's most beloved symbols. Here's what you need to know before choosing yours.",
                'body'         => "The Claddagh ring features three iconic elements — a heart representing love, a crown representing loyalty, and two hands representing friendship. Together they form one of the most meaningful pieces of Irish jewellery.\n\nWhen choosing a Claddagh ring, consider the metal (gold, silver, or rose gold), the gemstone if any, and how you plan to wear it. Tradition holds that wearing it on the right hand with the heart facing out means you are single, while heart facing in means your heart is taken.",
                'author'       => 'FADÓ Team',
                'published_at' => now()->subDays(7),
                'sort_order'   => 2,
            ]
        );
    }
}
