<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\CommonHelper;
// use App\Models\CoinDeposit;

class getRank extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:getrank';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return mixed
     */
    public function handle()
    {
      // $url = 'https://pokeapi.co/api/v2/pokemon/1';
      // $res = CommonHelper::curl($url);
      // $battle_env_url = 'https://api.battle.pokemon-home.com/cbd/competition/rankmatch/list';
      // $battle_env = CommonHelper::curl_battle_env($battle_env_url);

      // 対戦環境をcurlで取得
      $cmd = "curl https://api.battle.pokemon-home.com/cbd/competition/rankmatch/list \
              -H 'accept: application/json, text/javascript, */*; q=0.01' \
              -H 'countrycode: 304' \
              -H 'authorization: Bearer' \
              -H 'langcode: 1' \
              -H 'user-agent: Mozilla/5.0 (Linux; Android 8.0; Pixel 2 Build/OPD3.170816.012) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.61 Mobile Safari/537.36' \
              -H 'content-type: application/json' \
              -d '{\"soft\":\"Sw\"}' \
              -o JSON/SEASON/season.json";
      echo exec($cmd);

      // シーズン・タームのID情報
      $fp         = fopen('../backend/JSON/SEASON/season.json', 'r');
      $json       = fgets($fp);
      // trueにして連想配列にする
      $battle_env = json_decode($json, true);
      fclose($fp);
      // dd($battle_env);
      # タームごとに必要な情報だけを配列にまとめる
      $terms = array();
      // dd($season);
      // dump($season['list']);
      foreach ($battle_env['list'] as $data) {
        $id_num = array_keys($data)[0];
        foreach ($data as $id) {

          $season = $id['season'];
          $rule   = $id['rule'];
          $rst    = $id['rst'];
          $ts1    = $id['ts1'];
          $ts2    = $id['ts2'];

          $terms[] = array('id' => $id_num, 'season' => $season, 'rule' => $rule, 'rst' => $rst, 'ts1' => $ts1, 'ts2' => $ts2);
        }
      }

      // 対戦環境ポケモン情報を取得
      foreach ($terms as $term) {
        if ($term['rule'] == 0) {

          $id  = $term['id'];
          $rst = $term['rst'];
          $ts2 = $term['ts2'];
          $pokemons_file = $term['id']."-pokemons.json";
          // ポケモン情報保存用のフォルダ作成
          echo exec("mkdir JSON/POKEMON_INFO/season-$id");

          // 使用率ランキングを取得
          $cmd = "curl -XGET https://resource.pokemon-home.com/battledata/ranking/$id/$rst/$ts2/pokemon -H 'user-agent: Mozilla/5.0 (Linux; Android 8.0; Pixel 2 Build/OPD3.170816.012) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.61 Mobile Safari/537.36'  -H 'accept: application/json' -o JSON/RANKING/$pokemons_file";
          echo exec($cmd);

          for ($i=0;$i<5;$i++) {
            $j = $i + 1;
            $pokeinfo_file = $id."-pokeinfo-".$j.".json";

            // ポケモンの同時選出、採用技構成や持ち物などを取得
            $cmd = "curl -XGET https://resource.pokemon-home.com/battledata/ranking/$id/$rst/$ts2/pdetail-$j -H 'user-agent: Mozilla/5.0 (Linux; Android 8.0; Pixel 2 Build/OPD3.170816.012) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.61 Mobile Safari/537.36'  -H 'accept: application/json' -o JSON/POKEMON_INFO/season-$id/$pokeinfo_file";
            echo exec($cmd);
          }
        }
      }
    }
}