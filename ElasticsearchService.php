<?php

namespace App\Services;

use Elastic\Elasticsearch\ClientBuilder;

class ElasticsearchService
{

    /**
     * This Elastic Search service class,
     * by Abeer Elghool
     * Before using this class, you will need to ensure that Elastic Search is installed and running on your machine.
     * Additionally, you will need to install the elasticsearch/elasticsearch package using Composer.
     * Next, set the ELASTIC_HOST environment variable to the URL of your Elastic Search instance. If this variable is not set, the default URL of http://localhost:9200 will be used.
     * Finally, you will need to set the ELASTIC_PASSWORD environment variable to the password for your Elastic Search instance.
     */


    /**
     * The Elastic Search client.
     */
    private $client;

    /**
     * Create a new ElasticsearchService instance.
     *
     * @return void
     */
    function __construct()
    {
        $this->client = ClientBuilder::create()
            ->setHosts([env('ELASTIC_HOST')])
            ->setBasicAuthentication('elastic', env('ELASTIC_PASSWORD'))
            ->build();
    }

    /**
     * Search for documents in the specified indexes that match the given query.
     *
     * @param string $search The search query.
     * @param array $indexes The indexes to search in.
     * @return array The search results.
     */
    function search(string $search, array $indexes): array
    {
        $search = strtolower(trim($search));
        $params = [
            'index' => $indexes,
            'body' => [
                'query' => [
                    'bool' => [
                        'should' => [
                            [
                                'wildcard' => [
                                    'title' => "*$search*"
                                ]
                            ],
                            [
                                'wildcard' => [
                                    'desc' => "*$search*"
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
        $response = $this->client->search($params);
        return $response['hits'];
    }

    /**
     * Check if an index exists, and if not, create it.
     *
     * @param string $index The name of the index.
     * @return bool True if the index exists or is created successfully, false otherwise.
     */
    function checkOrCreateIndex($index): bool
    {
        $params = ['index' => $index];

        $indexExists = $this->client->indices()->exists($params);
        if ($indexExists->getStatusCode() == 404) {
            $response = $this->client->indices()->create($params);

            if (!$response['acknowledged']) {
                return false;
            }
        }
        return true;
    }

    /**
     * Store a new document in the specified index.
     *
     * @param string $index The index to store the document in.
     * @param array $data The data to store in the document.
     * @return bool True if the document is stored successfully, false otherwise.
     */
    function store_document(string $index, array $data): bool
    {
        $indexExists = $this->checkOrCreateIndex($index);
        if (!$indexExists) {
            return false;
        }
        $params = [
            'index' => $index,
            'body'  => $data
        ];
        $this->client->index($params);
        return true;
    }

    /**
     * Delete a document with the specified ID from the specified index.
     *
     * @param string $index The index to delete the document from.
     * @param string $id The ID of the document to delete.
     * @return void
     */
    function delete_document(string $index, string $id): void
    {
        $params = [
            'index' => $index,
            'id' => $id
        ];
        $this->client->delete($params);
    }

    /**
     * Delete the specified index.
     *
     * @param string $index The index to delete.
     * @return void
     */
    function delete_index(string $index): void
    {
        $params = [
            'index' => $index
        ];
        $this->client->delete($params);
    }
}
