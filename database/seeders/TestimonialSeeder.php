<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $testimonials = [
            [
                'rating' => 5,
                'description' => "Ero convinta fosse la solita app che promette e non mantiene… invece mi sono dovuta ricredere. Allenamenti brevi, piano alimentare semplice e un supporto che ti risponde davvero. Dopo 2 settimane già vedevo la pancia sgonfiarsi.",
                'title' => "Chiara Lombardi, Verona (34 anni)",
                'image' => "/testimonials/29.png",
            ],
            [
                'rating' => 5,
                'description' => "Non sono mai riuscita a seguire diete o programmi complicati. Qui invece è tutto pratico: esercizi rapidi, ricette veloci e la chat di supporto che mi incoraggia. È la prima volta che non mollo dopo la prima settimana.",
                'title' => "Elena Ferraro, Bari (59 anni)",
                'image' => "/testimonials/t2.png",
            ],
            [
                'rating' => 5,
                'description' => "All’inizio avevo paura fosse tempo perso come le altre volte… e invece finalmente qualcosa che funziona davvero! Non devo pensare a nulla: mi dicono cosa mangiare e quali mini esercizi fare. Sto già perdendo i primi chili.",
                'title' => "Roberta Colombo, Genova (41 anni)",
                'image' => "/testimonials/t3.png",
            ],
            [
                'rating' => 5,
                'description' => "Non ho tempo né voglia di spaccarmi in palestra. Con questa app faccio movimento in pochi minuti, seguo i pasti facili suggeriti, e quando ho un dubbio scrivo al supporto. Mi sento seguita, non sola.",
                'title' => "Daniela Romano, Palermo (36 anni)",
                'image' => "/testimonials/31.png",
            ],
            [
                'rating' => 5,
                'description' => "Ero scettica, perché a 57 anni pensavo fosse tardi per rimettermi in forma. Invece con i consigli sull’alimentazione e i piccoli allenamenti guidati mi sento più energica ogni giorno. Non serve forza di volontà infinita, è tutto già pronto.",
                'title' => "Patrizia Greco, Padova (52 anni)",
                'image' => "/testimonials/t5.png",
            ],
            [
                'rating' => 5,
                'description' => "La mia paura era di non riuscire a seguirlo per pigrizia… invece bastano pochi minuti al giorno. Tra ricette facili e mini workout, è diventata la mia nuova routine senza stress.",
                'title' => "Francesca Moretti, Torino (44 anni)",
                'image' => "/testimonials/33.png",
            ],
        ];

        foreach ($testimonials as $testimonial) {
            DB::table('c_reviews')->insert(array_merge($testimonial, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
