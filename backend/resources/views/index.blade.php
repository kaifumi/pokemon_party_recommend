<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 40vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 44px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="content">
            <div class="title m-b-md">
                ポケモンレートバトル<br>
                おすすめパーティ
            </div>
            <?php
                $fp           = fopen("../with_poke_lists.json", 'r');
                $json         = fgets($fp);
                $with_poke_lists = json_decode($json, true);
                fclose($fp);

                foreach (array_keys($with_poke_lists) as $index) {
                    echo "<br>";
                    if ($index <= 895){
                        $img_num = $index;
                        if (preg_match("/_/", $img_num)) {
                            $img_num = substr($index, 0, -2);
                            // 霊獣
                            if (in_array($img_num, array("641", "642", "645"), true)) {
                                $img_num = $img_num."-therian";
                            }
                            
                        }
                        $fp = fopen("../sprites/sprites/pokemon/$img_num.png", "rb");
                        $img = fread($fp, 4000);
                        fclose($fp);
                        $enc_img = base64_encode($img);
                        $imginfo = getimagesize('data:application/octet-stream;base64,' . $enc_img);
                        echo '<img src="data:' . $imginfo['mime'] . ';base64,'.$enc_img.'" style="width: 200px">';
                    }

                    // echo "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/$index.png";
                    foreach ($with_poke_lists[$index] as $pokemon) {
                        if ($pokemon['id'] <= 895){
                            $img_num = $pokemon['id'];
                            // フォーム違い対応
                            if ($pokemon['form'] != 0) {
                                if (in_array($img_num, array(641, 642, 645), true)) {
                                    $img_num = $img_num."-therian";
                                }
                            }
                            // if (in_array($pokemon['id'], array(641, 642, 645), true) && $pokemon['form'] == 1) {
                            //     $pokemon['id'] = $pokemon['id']."-therian";
                            // }
                            $fp = fopen("../sprites/sprites/pokemon/".$img_num.".png", "rb");
                            $img = fread($fp, 4000);
                            fclose($fp);
                            $enc_img = base64_encode($img);
                            $imginfo = getimagesize('data:application/octet-stream;base64,' . $enc_img);
                            echo '<img src="data:' . $imginfo['mime'] . ';base64,'.$enc_img.'">';
                        }
                        echo $pokemon['id'].",";
                    }
                    echo "<br>";
                }
            ?>
        </div>
    </body>
</html>
