<?php
namespace Controllers;

use Core\core;

use LLPhant\Embeddings\EmbeddingGenerator\Ollama\OllamaEmbeddingGenerator;
use LLPhant\Embeddings\VectorStores\FileSystem\FileSystemVectorStore;
use LLPhant\OllamaConfig;
use LLPhant\Chat\Message;
use LLPhant\Chat\OllamaChat;
use LLPhant\Query\SemanticSearch\QuestionAnswering;
use Core\session;

class ChatOllama extends Core {
    public object $questionAwnsering;
    public $messages = [];
    public function __construct()
    {
        parent::__construct('chat');
        $this->createChat();
        $this->messages = Session::get('ARRCHATHISTORY', []);
    }

    public function main(): void
    {
        $this->addCss('ChatOllama');
        $this->addJs('ChatOllama', ['type' => 'module']);
        $this->callViewFrom("ChatOllama");
        Session::delete('ARRCHATHISTORY');
    }

    private function createChat(): void
    {
        $embeddingConfig = new OllamaConfig();
        $embeddingConfig->model = 'nomic-embed-text';

        $chatConfig = new OllamaConfig();
        $chatConfig->model = 'qwen2.5-coder:3b';
        // $chatConfig->model = 'qwen2.5-coder:7b';

        $vectorStore = new FileSystemVectorStore('vault-embeddings.json');

        $embeddingGenerator = new OllamaEmbeddingGenerator($embeddingConfig);

        $chat = new OllamaChat($chatConfig);

        $chat->setSystemMessage(
            "Você é um assistente especializado no framework PHP interno da empresa.
            Responda apenas com base na documentação fornecida.
            Se a resposta não estiver na documentação, diga explicitamente que não sabe.
            Responda sempre em português.");

        $this->questionAwnsering = new QuestionAnswering($vectorStore, $embeddingGenerator, $chat);
    }

    public function askChatOllama()
    {
        $completeAnswer = "";

        $stream = $this->questionAwnsering->answerQuestionFromChat($this->messages);

        if (ob_get_level()) {
            ob_end_clean();
        }

        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');

        while (!$stream->eof()) {
            $chunk =  $stream->read(100);
            if ($chunk != "") {
                $completeAnswer .= $chunk;
                echo "data: " .json_encode($chunk). "\n\n";
                if (ob_get_length()) {
                    ob_flush();
                }
                flush();
            }
        }

        echo "data: [DATA]\n\n";
        $this->messages[] = Message::assistant($completeAnswer);
        Session::set('ARRCHATHISTORY', $this->messages);
    }

    public function setUserMessagePrompt()
    {
        $this->messages[] = Message::user($this->post["prompt"]);
        Session::set('ARRCHATHISTORY', $this->messages);
    }

    public function render() {}
}
