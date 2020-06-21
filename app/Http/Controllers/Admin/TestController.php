<?php
namespace App\Http\Controllers\Admin;

use QL\QueryList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class TestController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type', 'base');
        switch ($type)
        {
            case 'base':
                $data = QueryList::get('https://www.baidu.com/s?wd=QueryList', null, [
                    'headers' => [
                        'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36',
                        'Accept-Encoding' => 'gzip, deflate, br',
                    ]
                ])->rules([
                    'title' => ['h3', 'text'],
                    'link' => ['h3>a', 'href']
                ])
                    ->range('.result')
                    ->queryData();
                break;
            case 'article':
                $url = 'https://www.ithome.com/html/discovery/358585.htm';
                // 定义采集规则
                $rules = [
                    // 采集文章标题
                    'title' => ['h1','text'],
                    // 采集文章作者
                    'author' => ['#author_baidu>strong','text'],
                    // 采集文章内容
                    'content' => ['.post_content','html']
                ];
                $data = QueryList::get($url)->rules($rules)->query()->getData();
                break;
        }
        dd($data);
    }
}
