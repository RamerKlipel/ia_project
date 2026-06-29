<?php
require_once './vendor/autoload.php';

use LLPhant\Embeddings\DataReader\FileDataReader;
use LLPhant\Embeddings\DocumentSplitter\DocumentSplitter;
use LLPhant\Embeddings\EmbeddingGenerator\Ollama\OllamaEmbeddingGenerator;
use LLPhant\Embeddings\VectorStores\FileSystem\FileSystemVectorStore;
use LLPhant\OllamaConfig;

$embeddingConfig = new OllamaConfig();
$embeddingConfig->model = 'nomic-embed-text';
$embeddingConfig->url = 'http://ollama:11434/api';

// $chatConfig = new OllamaConfig();
// $chatConfig->model = 'qwen2.5-coder:7b';

$fileReader = new FileDataReader(
    'core/functions.php', extensions: ['php']
);
$documents = $fileReader->getDocuments();

$splitter = new DocumentSplitter();
$chunks = $splitter->splitDocuments($documents, 500);

$embeddingGenerator = new OllamaEmbeddingGenerator($embeddingConfig);
$chunksEmbeddings =  $embeddingGenerator->embedDocuments($chunks);

$vectorStore = new FileSystemVectorStore("vault-embeddings.json");
$vectorStore->addDocuments($chunksEmbeddings);
