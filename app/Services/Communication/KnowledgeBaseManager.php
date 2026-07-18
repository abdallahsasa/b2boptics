<?php

namespace App\Services\Communication;

use App\Models\KnowledgeDocument;
use Smalot\PdfParser\Parser;

class KnowledgeBaseManager
{
    public function processPdf(string $filePath, string $title, string $category = null): void
    {
        $parser = new Parser();
        $pdf = $parser->parseFile($filePath);
        $text = $pdf->getText();

        $chunks = $this->chunkText($text, 1000); // chunk size 1000 chars

        foreach ($chunks as $chunk) {
            KnowledgeDocument::create([
                'title' => $title,
                'content_chunk' => $chunk,
                'source_type' => 'pdf',
                'source_path' => $filePath,
                'category' => $category,
                'is_active' => true,
            ]);
        }
    }

    protected function chunkText(string $text, int $length): array
    {
        $text = preg_replace('/\s+/', ' ', $text);
        $words = explode(' ', $text);
        $chunks = [];
        $currentChunk = '';

        foreach ($words as $word) {
            if (strlen($currentChunk) + strlen($word) > $length) {
                $chunks[] = trim($currentChunk);
                $currentChunk = '';
            }
            $currentChunk .= $word . ' ';
        }
        if (!empty(trim($currentChunk))) {
            $chunks[] = trim($currentChunk);
        }

        return $chunks;
    }
}
