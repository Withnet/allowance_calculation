<?php
require_once("vendor/autoload.php");
use Symfony\Component\DomCrawler\Crawler as Crawler;
ini_set('max_execution_time', 0);

/**
 * Класс, содержащий логику парсинга сайта https://saratov.metal100.ru
 * Class Parser
 */
class Parser
{
    private string $url = "https://saratov.metal100.ru";

    /**
     * @return array
     * Получаем все категории товаров
     */
    public function getCategories(): array
    {
        $html = file_get_contents($this->url . "/prodazha/Truboprovodnaya-armatura/");
        $crawler = new Crawler($html);

        return $crawler->filter('.Categories')->filter('.subCategories')->filter('li')->each(function ($node) {
            return [
                'href' => $this->url . $node->filter('a')->attr('href'),
            ];
        });
    }

    /**
     * @param string $url
     * @return array
     * Получаем все ссылки на товары в категории
     */
    public function getProductsLinks(string $url): array
    {
        $html = file_get_contents($url);
        $crawler = new Crawler($html);

        return $crawler->filter('#sizesList')->filter('div.margin-bottom-20.buttonSet')->filter('span')->each(function ($node) {
            return [
                'url' => $this->url . $node->attr('url'),
            ];
        });
    }

    /**
     * @param string $url
     * @return array
     * Получаем всю нужную информацию по каждому товару
     */
    public function getInfo(string $url): array
    {
        $html = file_get_contents($url);
        $crawler = new Crawler($html);

        return $crawler->filter('#priceTable')->filter('tbody')->filter('tr.priceRow')->each(function ($node) {
            return [
                'name' => $node->filter('td:nth-child(1)')->text(),
                'price' => $node->filter('td:nth-child(6)')->filter('span.hidden')->text(),
                'company' => $node->filter('td.nowrap.companyCell ')->filter('a')->text(),
            ];
        });
    }

    /**
     * @return array
     * Логика добавления информации в БД
     */
    public function dump(): array
    {
        $categories = $this->getCategories();
        $products = [];
        $info = [];

        foreach ($categories as $category) {
            foreach ($this->getProductsLinks($category['href']) as $item) {
                $products[] = $item;
            }
        }

        foreach ($products as $product) {
            foreach ($this->getInfo($product['url']) as $item) {
                $info[] = $item;
            }
        }

        return $info;
    }
}
