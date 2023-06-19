# Elastic Search Service Class

This Elastic Search service class, authored by Abeer Elghool, provides a streamlined interface for working with Elastic Search.

## Prerequisites

Before using this class, you will need to ensure that Elastic Search is installed and running on your machine.

Additionally, you will need to install the `elasticsearch/elasticsearch` package using Composer.

## Configuration

To use this class, you will need to set the following environment variables:

- `ELASTIC_HOST`: The URL of your Elastic Search instance. If this variable is not set, the default URL of `http://localhost:9200` will be used.
- `ELASTIC_PASSWORD`: The password for your Elastic Search instance.

## Usage

To use this class, simply create a new instance of the `ElasticsearchService` class:

```php
use App\Services\ElasticsearchService;

$elasticsearchService = new ElasticsearchService();
```
Once you have an instance of the ElasticsearchService class, you can call its methods to perform various operations on your Elastic Search instance, such as searching for documents, storing new documents, and deleting documents or indexes.

## Methods

The following methods are available in the `ElasticsearchService` class:

```php
public function search(string $search, array $indexes): array;
public function checkOrCreateIndex(string $index): bool;
public function storeDocument(string $index, array $data): bool;
public function deleteDocument(string $index, string $id): void;
public function deleteIndex(string $index): void;
```
### `search(string $search, array $indexes): array`

Search for documents in the specified indexes that match the given query.

- `$search`: The search query.
- `$indexes`: The indexes to search in.
- Returns: The search results.

### `checkOrCreateIndex(string $index): bool`

Check if an index exists, and if not, create it.

- `$index`: The name of the index.
