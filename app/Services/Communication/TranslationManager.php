<?php

namespace App\Services\Communication;

use DeepL\Translator;
use Illuminate\Support\Facades\Log;

class TranslationManager
{
    protected Translator $translator;

    public function __construct()
    {
        $this->translator = new Translator(env('DEEPL_AUTH_KEY'));
    }

    public function translateWithDetection(string $text, string $targetLang): array
    {
        try {
            $result = $this->translator->translateText($text, null, $targetLang);
            return [
                'translated_text' => $result->text,
                'detected_source_lang' => $result->detectedSourceLang,
            ];
        } catch (\Exception $e) {
            Log::error('DeepL Translation Failed: ' . $e->getMessage());
            return [
                'translated_text' => $text, // fallback
                'detected_source_lang' => 'EN',
            ];
        }
    }
}
