<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class AgriculturalInputSeeder extends Seeder
{
    //"FERTILIZER", "HERBICIDE", "FUNGICIDE", "OTHER"
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('agricultural_inputs')->insert([
            [
                'name' => "Ácido bórico e bórax",
                'type' => "FERTILIZER",
                'rules' => "Permitidos somente em biofertilizantes na concentração máxima de 8 g por litro, desde que autorizado pelo OAC ou pela OCS."
            ],
            [
                'name' => "Ácidos naturais não sintéticos",
                'type' => "FERTILIZER",
                'rules' => "Permitido o uso como acidificante no preparo de biofertilizantes."
            ],
            [
                'name' => "Adubos verdes",
                'type' => "FERTILIZER",
                'rules' => ""
            ],
            [
                'name' => "Carbonatos, hidróxidos e óxidos de cálcio e magnésio (calcários e cal)",
                'type' => "FERTILIZER",
                'rules' => ""
            ],
            [
                'name' => "Fosfatos de rocha, hiperfosfatos e termofosfatos",
                'type' => "FERTILIZER",
                'rules' => ""
            ],
            [
                'name' => "Micronutrientes - Boro (B), Cobre (Cu), Cloro (Cl), Cobalto (Co), Ferro (Fe), Manganês (Mn), Molibdênico (Mo) e Zinco (Zn)",
                'type' => "FERTILIZER",
                'rules' => "Desde que o produto seja constituído somente por substâncias autorizadas."
            ],
            [
                'name' => "Sulfato de cálcio (gesso)",
                'type' => "FERTILIZER",
                'rules' => "Desde que o nível de radioatividade não ultrapasse o limite máximo regulamentado. Gipsita (gesso mineral) sem restrição."
            ],
            [
                'name' => "Sulfato de magnésio ou sulfato de magnésio monohidratado (Kieserita)",
                'type' => "FERTILIZER",
                'rules' => "Sais de extração mineral. Permitido desde que de origem natural."
            ],
            [
                'name' => "Sulfato de potássio e sulfato duplo de potássio e magnésio",
                'type' => "FERTILIZER",
                'rules' => "Desde que obtidos por procedimentos físicos, não enriquecidos por processo químico e não tratados quimicamente para o aumento da solubilidade. Permitidos somente com a autorização do OAC ou da OCS."
            ],
            [
                'name' => "Ácido bórico",
                'type' => "HERBICIDE",
                'rules' => "Autorizado somente em formulações de caldas na concentração máxima de 0,1%"
            ],
            [
                'name' => "Ácido bórico e seus sais (octaborato de sódio tetrahidratado e tetraborato de sódio decahidratado - boráx",
                'type' => "HERBICIDE",
                'rules' => "Uso para tratamento de madeira."
            ],
            [
                'name' => "Ácido pelargônico",
                'type' => "HERBICIDE",
                'rules' => "Autorizado na condição de herbicida, desde que obtido de fontes naturais ou por síntese através da clivagem oxidativa do ácido oleico. Permitido somente com a autorização do OAC ou da OCS."
            ],
            [
                'name' => "Ácido peracético",
                'type' => "HERBICIDE",
                'rules' => "Autorizado na concentração máxima de 2% na formulação, com diluições que não excedam concentração de 0,005% para tratamentos preventivos e de 0,4% para tratamentos curativos. Permitido somente com a autorização do OAC ou da OCS."
            ],
            [
                'name' => "Ácidos naturais (acético, ascórbico, cítrico, lático e outros)",
                'type' => "HERBICIDE",
                'rules' => "Permitido somente com a autorização do OAC ou da OCS."
            ],
            [
                'name' => "Cobre nas formas de hidróxido, oxicloreto, sulfato, óxido e octanoato e, as diferentes formas de apresentação da calda bordalesa",
                'type' => "FUNGICIDE",
                'rules' => "Uso proibido em pós-colheita. Uso como fungicida e para tratamento de madeira. Permitido somente com a autorização do OAC ou da OCS, de forma a minimizar o acúmulo de cobre no solo. Quantidade máxima a ser aplicada: 6 kg de cobre/ha/ano."
            ],
            [
                'name' => "Acetato de amônio",
                'type' => "OTHER",
                'rules' => "Concentração máxima de 2,5% (dois vírgula cinco por cento) no produto formulado."
            ],
        ]);
    }
}
