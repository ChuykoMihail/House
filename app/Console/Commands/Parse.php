<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\Category;
use Sunra\PhpSimple\HtmlDomParser;
use Symfony\Component\DomCrawler\Crawler;
use Curl\Curl;
use Symfony\Component\CssSelector\CssSelectorConverter;
ini_set('max_execution_time', 600);
class Parse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Parse';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse My Site';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }





    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
      function getHtml(string $url, $isLocal = false)
        {
            $html = null;
            if ($isLocal) {
                try {
                    $html = file_get_contents($url);
                } catch (\Exception $exception) {
                    // error
                    $html = null;
                }
            } else {
                try {
                    $ch = curl_init($url);
                    curl_setopt(
                        $ch, CURLOPT_USERAGENT,
                        "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.105 Safari/537.36"
                    );
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                    $html = curl_exec($ch);
                    curl_close($ch);
                } catch (\Exception $exception) {
                    // error
                    $html = null;
                }
            }
            return $html;
          }
      function getProduct(string $url, $id){
        $h2 = getHtml($url);
        $crawler = new Crawler($h2);
        $name = $crawler->filter('body > main > section > div.col-12.col-lg-7 > h1')->eq(0)->text();
        $cost= $crawler->filter('body > main > section > div.col-12.col-lg-7 > div > div > div > ul > li.row.mb-3.align-items-end > span.col-6.text-nowrap > span.text-danger.lead.price.text-nowrap')->eq(0)->text();
        $crawler = $crawler->filter('#bootstrap-tabs3-tab-description > table')->filter('tr')->filter('td.product-chars-value');
        $description = "/";
        foreach ($crawler as $domElement) {
          $description=$description.$domElement->textContent."/";
        }
        $product = new Product;
        $product->name=$name;
        $product->cost=$cost;
        $product->description=$description;
        // $product->color = $description[0];
        // $product->productivity = $description[1];
        // $product->num_of_speed= $description[2];
        // $product->controll_type= $description[3];
        // $product->height= $description[4];
        // $product->width= $description[5];
        // $product->depth= $description[6];
        // $product->montage= $description[7];
        // $product->range_hood_type= $description[8];
        // $product->lighting_type= $description[9];
        // $product->glass= $description[10];
        // $product->producing_country= $description[10];
        $product->categories_id = $id;
        $product->save();
      }
      function getProductsOfType(string $url, $id){
        $h = getHtml($url);
        if (is_null($h)) { return; }
        $crawler = new Crawler($h);
        $crawler = $crawler->filter('#catalog-products > div figure *>a');
        foreach ($crawler as $domElement) {
          $newhref = "https://elica-store.ru/".$domElement->getAttribute('href');
          getProduct($newhref,$id);
        }
      }
      $CAT_NAME_1 ='https://elica-store.ru/elica-naklonnie-vityazhki';
      $CAT_NAME_2 ='https://elica-store.ru/elica-vityazhki-bez-kupola';
      $CAT_NAME_3 ='https://elica-store.ru/elica-ostrovnie-vityazhki';
      $CAT_NAME_4 ='https://elica-store.ru/elica-kupolnie-vityazhki';
      $CAT_NAME_5 ='https://elica-store.ru/elica-vityazhki-kantri';
      $CAT_NAME_6 ='https://elica-store.ru/elica-podvesnie-vityazhki';
      $CAT_NAME_7 ='https://elica-store.ru/elica-varochnye-paneli';
      $CAT_NAME_8 ='https://elica-store.ru/elica-aksessuari';
      $CAT_NAME_9 ='https://elica-store.ru/elica-vstraivaemie-vityazhki';
      $categories = Category::create([
        'name' => 'Наклонные вытяжки',
      ]);
      $categories = Category::create([
        'name' => 'Вытяжка без купола',
      ]);
      $categories = Category::create([
        'name' => 'Островные вытяжки',
      ]);
      $categories = Category::create([
        'name' => 'Купольные вытяжки',
      ]);
      $categories = Category::create([
        'name' => 'Вытяжка кантри',
      ]);
      $categories = Category::create([
        'name' => 'Подвесные вытяжки',
      ]);
      $categories = Category::create([
        'name' => 'Варочные панели',
      ]);
      $categories = Category::create([
        'name' => 'Аксессуары',
      ]);
      $categories = Category::create([
        'name' => 'Встраиваемые вытяжки',
      ]);

      getProductsOfType($CAT_NAME_1,1);
      getProductsOfType($CAT_NAME_2,2);
      getProductsOfType($CAT_NAME_3,3);
      getProductsOfType($CAT_NAME_4,4);
      getProductsOfType($CAT_NAME_5,5);
      getProductsOfType($CAT_NAME_6,6);
      getProductsOfType($CAT_NAME_7,7);
      getProductsOfType($CAT_NAME_8,8);
      getProductsOfType($CAT_NAME_9,9);

      return 0;
    }
}
