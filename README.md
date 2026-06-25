## Dependências externas

Antes de executar a aplicação, instale e configure:

### PHP

* PHP 8.2 ou superior

### Composer

* Instalar o Composer
* Executar `composer install`

### Ollama

* Instalar o Ollama
* Iniciar o serviço

Verificar funcionamento:

```bash
curl http://localhost:11434/api/tags
```

### Modelos

Baixar os modelos necessários:

```bash
ollama pull nomic-embed-text
ollama pull qwen2.5
```

### Embeddings

Gerar os embeddings antes de acessar o módulo de chat.

```bash
php vaultindex.php
```
