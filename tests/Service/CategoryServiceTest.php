<?php

namespace Tests\Service;

use Cherryred5959\ChzzkApi\ApiClient;
use Cherryred5959\ChzzkApi\Service\CategoryService;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class CategoryServiceTest extends TestCase
{
    private CategoryService $categoryService;
    private ApiClient $apiClient;

    protected function setUp(): void
    {
        $this->apiClient = $this->createMock(ApiClient::class);
        $this->categoryService = new CategoryService($this->apiClient);
    }

    public function testSearchCategories(): void
    {
        $query = 'game';
        $size = 10;
        $expectedResponse = [
            'content' => [
                [
                    'categoryType' => 'GAME',
                    'categoryId' => '1',
                    'categoryValue' => 'Test Game',
                    'posterImageUrl' => 'https://example.com/image.jpg'
                ]
            ]
        ];

        $this->apiClient
            ->expects($this->once())
            ->method('makeRequest')
            ->with(
                'GET',
                '/open/v1/categories/search',
                ['query' => ['query' => $query, 'size' => $size]]
            )
            ->willReturn($expectedResponse);

        $result = $this->categoryService->searchCategories($query, $size);

        $this->assertEquals($expectedResponse, $result);
    }

    public function testSearchCategoriesWithInvalidSize(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Size must be between 1 and 50');

        $this->categoryService->searchCategories('game', 51);
    }

    public function testSearchCategoriesWithEmptyQuery(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Query parameter is required');

        $this->categoryService->searchCategories('', 10);
    }
}