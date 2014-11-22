<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta http-equiv="content-script-type" content="text/javascript" />
	<title>
	ローカルリポジトリ一覧
	</title>
	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<script src="js/repo.js"></script>
	<link type="text/css" rel="stylesheet" href="css/repo.css">
</head>
<body>

<?php

// リポジトリがあるホスト・ポートを指定
// Dockerの-eオプションで指定しても良い。指定しない場合、ホストにある事とする
$host=getenv('REP_HOST');
$port=getenv('REP_HOST_PORT');
if(!isset($host) || !$host) {
	$host=getenv('HTTP_X_FORWARDED_SERVER');
}
if(!isset($port) || !$port) {
	$port=5000;
}

echo <<< INFO

<div>
リポジトリを取得するには、以下のコマンドを実行する
<br />
sudo docker pull $host:$port/[name][:tag]
</div>

INFO;

  /**
   * ローカルリポジトリ情報取得用ヘルパ関数
   * どの情報を取得するかはurlで指示する事を想定
   */
  function _get($url) {
    $ch=curl_init($url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
    $r=curl_exec($ch);
    curl_close($ch);

    return $r;
  }

  /**
   * メタ情報の取得
   */
  function get_text($rid,$tag,$host) {
    $url="http://$host/repo/meta/$rid/$tag";
    $ch=curl_init($url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
    $r=curl_exec($ch);
    curl_close($ch);
    $ar=json_decode($r);
    if(isset($ar) && is_array($ar) && count($ar)>0) {
      return $ar[0];
    }
    return null;
  }

// ローカルリポジトリに格納されている全イメージの取得
  $url="http://$host:$port/v1/search";
  $r=_get("http://$host:$port/v1/search");
  $dat=json_decode($r,true);
  if(!isset($dat)) {
    echo "no entry.<br />\n";
    //echo "url.: $url\n";
    return;
  }
  $num=$dat["num_results"];
  if(!isset($num) || $num<=0) {
    echo "no entry.<br />\n";
    //echo "url..: $url\n";
    return;
  }
  if(!isset($dat["results"])) {
    echo "no entry.<br />\n";
    //echo "url...: $url\n";
    return;
  }
  //print_r($dat["results"],true);
  echo "<table class='alldata'><tbody>\n";
  foreach($dat["results"] as $res) {

	// イメージ名称を取得
    $str=$res["name"];
    $pos=strrpos($str,'/');
    $rep=substr($str,$pos+1);
    echo <<< INAME_OUT
	<tr class="iname">
	<th colspan="3">$rep</th>
	</tr>
INAME_OUT;

	// イメージ名称に関連する全タグ情報を取得
    $u="http://$host:$port/v1/repositories/library/$rep/tags";
    $r=_get($u);
    $tags=json_decode($r,true);

	// イメージ名（ID）とタグにひもづく各情報を取得
    foreach($tags as $tname => $id) {

	//<tr class="tag"><th rowspan="9"></th>
      echo <<< TAG_OUT
	<tr class="TagGroup">
	<th colspan="3"></th>
	</tr>

	<tr class="tag">
	<td>Tag<button class='Down'>v</button></td>
	<td class="data">$tname</td>
	</tr>
TAG_OUT;

	// イメージIDとタグをキーにテキストと詳細テキストを取得する
      $t=get_text($rep,$tname,$host);
//print_r($t);
      if(isset($t)) {
	$title=$t->title;
	$desc=$t->descript;
	$html_desc=nl2br($desc);
      } else {
	$title="";
	$desc="";
	$html_desc="";
      }

	// title/descript出力
      echo <<< DESC_OUT
	
	<tr class="title">
	<td>Title</td>
	<td class="data">$title</td>
	</tr>
	<tr class="tEdit">
	<td><button class='Update' title="/meta/$rep/$tname">Update</button><button class='Cancel'>cancel</button></td>
	<td class="data"><input type="text" name="title" value="$title"></td>
	</tr>
	<tr class="descript">
	<td>Descript</td>
	<td class="data">$html_desc</td>
	</tr>
	<tr class="dEdit">
	<td><button class='Update' title="/meta/$rep/$tname">Update</button><button class='Cancel'>cancel</button></td>
	<td class="data"><textarea name="descript" raws="4">$desc</textarea></td>
	</tr>

DESC_OUT;

	// ローカルリポジトリから取得できるイメージ情報を取得する
	// ちなみに、この情報はその他の情報としてデフォルトでは非表示
	// class=opt
      $u="http://$host:$port/v1/images/$id/json";
      $r=_get($u);
      $r=json_decode($r);
      $cd=$r->{'created'};
      $dd=new DateTime(substr($cd,0,strpos($cd,'.')),new DateTimeZone('Asia/Tokyo'));

$d= $dd->format('Y-m-d H:i:s');
$au=$r->{"author"};
      echo <<< OPT1_OUT
	<tr class="opt">
	<td>Id</td>
	<td class="data">$id</td>
	</tr>
	<tr class="opt">
	<td>created</td>
	<td class="data"> $d </td>
	</tr>
	<tr class="opt">
	<td>Author</td>
	<td class="data"> $au </td>
	</tr>
	<tr class="opt">
	<td>Exposedports</td>
	<td class="data">
OPT1_OUT;
      if(isset($r->{'config'}->{'ExposedPorts'})) {
      foreach($r->{'config'}->{'ExposedPorts'} as $k => $v) {
        echo "[\"$k\"]";
      }
      }
      echo <<< OPT2_OUT
	</td></tr>
	<tr class="opt">
	<td>Cmd</td>
	<td class="data">
OPT2_OUT;
      if(isset($r->{'config'}->{'Cmd'})) {
      foreach($r->{'config'}->{'Cmd'} as $k) {
        echo " [\"$k\"]";
      }
      }

$ep=$r->{'config'}->{'Entrypoint'};
      echo <<< OPT3_OUT
	</td></tr>
	<tr class="opt">
	<td>Entrypoint</td>
	<td class="data">$ep</td>
OPT3_OUT;
    }
  }
  echo "</tbody></table>\n";
?>

</body>
</html>
