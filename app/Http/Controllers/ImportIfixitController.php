<?php

namespace App\Http\Controllers;

use App\Models\Ifixit;
use App\Models\Guide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Illuminate\Support\Facades\Log;

class ImportIfixitController extends Controller
{
    /**
     * Importar TODAS las guías desde iFixit, traducirlas completamente y guardarlas en guides.
     */
    public function __invoke(Request $request)
    {
        // 📌 1. Obtener todas las guías en Ifixit que no están en Guides
        $ifixitGuides = Ifixit::whereNotIn('guide_id', Guide::pluck('guide_id'))->get();

        if ($ifixitGuides->isEmpty()) {
            return response()->json(['message' => 'No hay nuevas guías para importar.'], 200);
        }

        // 📌 2. Inicializar el traductor
        $tr = new GoogleTranslate();
        $tr->setSource('en');
        $tr->setTarget('ca');

        Log::info("Total de guías a importar: " . $ifixitGuides->count());

        // 📌 3. Iterar sobre todas las guías y procesarlas
        foreach ($ifixitGuides as $ifixitGuide) {
            Log::info("Importando guía ID: " . $ifixitGuide->guide_id);

            // Obtener los detalles completos desde la API
            $endpoint = 'https://www.ifixit.com/api/2.0/guides/' . $ifixitGuide->guide_id;
            $response = Http::get($endpoint);

            if (!$response->successful()) {
                Log::error("Error al obtener la guía ID: " . $ifixitGuide->guide_id);
                continue; // Saltar esta guía y continuar con la siguiente
            }

            $guideData = $response->json();

            // 📌 4. Traducir TODOS los datos de la guía
            $translatedData = [
                'title' => $this->translateText($guideData['title'], $tr),
                'category' => $this->translateText($guideData['category'], $tr),
                'subject' => $this->translateText($guideData['subject'], $tr),
                'summary' => $this->translateText($guideData['summary'], $tr),
                'introduction_raw' => $this->translateText($guideData['introduction_raw'], $tr),
                'introduction_rendered' => $this->translateText($guideData['introduction_rendered'], $tr),
                'conclusion_raw' => $this->translateText($guideData['conclusion_raw'], $tr),
                'conclusion_rendered' => $this->translateText($guideData['conclusion_rendered'], $tr),
                'steps' => isset($guideData['steps']) ? $this->translateSteps($guideData['steps'], $tr) : [],
                'documents' => json_encode($guideData['documents'] ?? []),
                'flags' => json_encode($guideData['flags'] ?? []),
                'image' => json_encode($guideData['image'] ?? []),
                'prerequisites' => json_encode($guideData['prerequisites'] ?? []),
                'tools' => json_encode($guideData['tools'] ?? []),
            ];

            // 📌 5. Guardar en Guides
            Guide::updateOrInsert(
                ['guide_id' => $guideData['guideid']],
                [
                    'guide_id' => $guideData['guideid'],
                    'title' => $translatedData['title'],
                    'category' => $translatedData['category'],
                    'subject' => $translatedData['subject'],
                    'summary' => $translatedData['summary'],
                    'introduction_raw' => $translatedData['introduction_raw'],
                    'introduction_rendered' => $translatedData['introduction_rendered'],
                    'conclusion_raw' => $translatedData['conclusion_raw'],
                    'conclusion_rendered' => $translatedData['conclusion_rendered'],
                    'difficulty' => $this->translateText($guideData['difficulty'] ?? 'Desconeguda', $tr),
                    'time_required_min' => $guideData['time_required_min'] ?? 0,
                    'time_required_max' => $guideData['time_required_max'] ?? 0,
                    'public' => $guideData['public'] ?? false,
                    'locale' => 'ca',
                    'type' => $this->translateText($guideData['type'] ?? 'Desconegut', $tr),
                    'url' => $guideData['url'] ?? '',
                    'documents' => $translatedData['documents'],
                    'flags' => $translatedData['flags'],
                    'image' => $translatedData['image'],
                    'prerequisites' => $translatedData['prerequisites'],
                    'steps' => json_encode($translatedData['steps']), // 📌 Guardamos los pasos traducidos
                    'tools' => $translatedData['tools'],
                    'author_id' => $guideData['author']['userid'] ?? 0,
                    'author_username' => $this->translateText($guideData['author']['username'] ?? 'Desconegut', $tr),
                    'author_image' => json_encode($guideData['author']['image'] ?? []),
                    'created_date' => isset($guideData['created_date']) ? date('Y-m-d H:i:s', $guideData['created_date']) : now(),
                    'published_date' => isset($guideData['published_date']) ? date('Y-m-d H:i:s', $guideData['published_date']) : null,
                    'modified_date' => isset($guideData['modified_date']) ? date('Y-m-d H:i:s', $guideData['modified_date']) : now(),
                    'prereq_modified_date' => isset($guideData['prereq_modified_date']) ? date('Y-m-d H:i:s', $guideData['prereq_modified_date']) : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        return response()->json(['message' => 'Todas las guías han sido importadas y traducidas.'], 200);
    }

    /**
     * 📌 Traducir los pasos de la guía.
     */
    /**
 * 📌 Traducir los pasos de la guía, incluyendo títulos, textos y comentarios.
 */
private function translateSteps($steps, $tr)
{
    foreach ($steps as &$step) {
        $step['title'] = $this->translateText($step['title'] ?? '', $tr);
        $step['summary'] = $this->translateText($step['summary'] ?? '', $tr);

        // Traducir líneas dentro de cada paso
        if (isset($step['lines']) && is_array($step['lines'])) {
            foreach ($step['lines'] as &$line) {
                if (isset($line['text_raw'])) {
                    $line['text_raw'] = $this->translateText($line['text_raw'], $tr);
                }
                if (isset($line['text_rendered'])) {
                    $line['text_rendered'] = $this->translateText($line['text_rendered'], $tr);
                }
            }
        }

        // Traducir comentarios dentro de los pasos (si existen)
        if (isset($step['comments']) && is_array($step['comments'])) {
            foreach ($step['comments'] as &$comment) {
                if (is_string($comment)) {
                    $comment = $this->translateText($comment, $tr);
                }
            }
        }

        // Traducir subtítulos de imágenes si existen
        if (isset($step['media']['data']) && is_array($step['media']['data'])) {
            foreach ($step['media']['data'] as &$media) {
                if (isset($media['caption'])) {
                    $media['caption'] = $this->translateText($media['caption'], $tr);
                }
            }
        }
    }
    return $steps;
}



  /**
 * 📌 Función auxiliar para traducir texto (evita errores con `null` y arrays).
 */
private function translateText($text, $tr)
{
    // Si $text no es una cadena, lo convertimos a una vacía
    if (!is_string($text)) {
        return '';
    }

    return !empty(trim($text)) ? $tr->translate($text) : '';
}


}