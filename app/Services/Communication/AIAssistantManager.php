<?php

namespace App\Services\Communication;

use OpenAI\Client;
use OpenAI;
use App\Models\KnowledgeDocument;

class AIAssistantManager
{
    protected Client $client;

    public function __construct()
    {
        $this->client = OpenAI::client(env('OPENAI_API_KEY'));
    }

    public function generateReply(string $question, string $conversationContext = ''): array
    {
        // 1. Retrieve Knowledge from Meilisearch
        $results = KnowledgeDocument::search($question)->take(5)->get();
        
        $context = "";
        foreach ($results as $doc) {
            $context .= "Source: " . $doc->title . "\n" . $doc->content_chunk . "\n\n";
        }

        // 2. Generate Reply using OpenAI
        $prompt = "You are a professional B2B AI Assistant for OpticB2B. Answer the user's question based ONLY on the provided context. If the context does not contain the answer or you are not confident, say 'I do not have enough information to answer this.' Do not hallucinate.\n\nContext:\n{$context}\n\nConversation Context:\n{$conversationContext}\n\nQuestion: {$question}";

        $response = $this->client->chat()->create([
            'model' => 'gpt-4o',
            'messages' => [
                ['role' => 'system', 'content' => 'You are an AI assistant for a B2B platform.'],
                ['role' => 'user', 'content' => $prompt],
            ],
            'temperature' => 0.2,
        ]);

        $reply = $response->choices[0]->message->content;
        
        $confidence = 0.9;
        if (str_contains(strtolower($reply), 'do not have enough information')) {
            $confidence = 0.1;
        }

        return [
            'reply' => $reply,
            'confidence' => $confidence,
            'sources' => $results->pluck('id')->toArray(),
        ];
    }
}
