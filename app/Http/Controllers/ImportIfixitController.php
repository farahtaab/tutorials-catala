<?php

namespace App\Http\Controllers;

use App\Models\Ifixit;
use App\Models\Guide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ImportIfixitController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $ifitxit = Ifixit::whereNotIN(
            'guide_id',
            Guide::select('guide_id')->get()
        )->orderBy('published_date', 'asc')->first();

        $endpoint = 'https://www.ifixit.com/api/2.0/guides/' . $ifitxit->guide_id; // Canvia per l'URL correcte
        $response = Http::get($endpoint);

        if ($response->successful()) {
            $guide = $response->json();

            DB::table('guides')->updateOrInsert(
                ['guide_id' => $guide['guideid']],
                [
                    'title' => $guide['title'],
                    'category' => $guide['category'],
                    'subject' => $guide['subject'],
                    'summary' => $guide['summary'],
                    'introduction_raw' => $guide['introduction_raw'],
                    'introduction_rendered' => $guide['introduction_rendered'],
                    'conclusion_raw' => $guide['conclusion_raw'],
                    'conclusion_rendered' => $guide['conclusion_rendered'],
                    'difficulty' => $guide['difficulty'],
                    'time_required_min' => $guide['time_required_min'] ?? null,
                    'time_required_max' => $guide['time_required_max'] ?? null,
                    'public' => $guide['public'],
                    'locale' => $guide['locale'],
                    'type' => $guide['type'],
                    'url' => $guide['url'],
                    'documents' => json_encode($guide['documents']),
                    'flags' => json_encode($guide['flags']),
                    'image' => json_encode($guide['image']),
                    'prerequisites' => json_encode($guide['prerequisites']),
                    'steps' => json_encode($guide['steps']),
                    'tools' => json_encode($guide['tools']),
                    'author_id' => $guide['author']['userid'],
                    'author_username' => $guide['author']['username'],
                    'author_image' => json_encode($guide['author']['image']),
                    'created_date' => date('Y-m-d H:i:s', $guide['created_date']),
                    'published_date' => isset($guide['published_date']) ? date('Y-m-d H:i:s', $guide['published_date']) : null,
                    'modified_date' => date('Y-m-d H:i:s', $guide['modified_date']),
                    'prereq_modified_date' => isset($guide['prereq_modified_date']) ? date('Y-m-d H:i:s', $guide['prereq_modified_date']) : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
