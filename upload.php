<?php
// ファイルの保存先ディレクトリ
$strUploadDir = '/opt/specialfraud/result/upload/';

// UnixTime取得(ミリ秒単位)
$fTimeStart = explode(' ', microtime());
$iUnixM = ((int)$fTimeStart[1]) * 1000 + ((int)round($fTimeStart[0] * 1000));

// 一時ファイルの名前を利用する
$strTmp = pathinfo($_FILES['upload']['tmp_name'], PATHINFO_FILENAME);
// 元のファイル名から拡張子を持ってくる
$strExtension = pathinfo($_FILES['upload']['name'], PATHINFO_EXTENSION);
// UnixTime_一時ファイルで衝突しないファイル名を設定する
$strFileName = sprintf('%s_%s.%s', $iUnixM, $strTmp, $strExtension);
// ファイルのフルパス
$strFilePath = $strUploadDir.$strFileName;

// 念の為ファイルの重複チェックを行う
// まず作成予定のファイル名でファイルオープン
$fp = @fopen($strFilePath, 'x'); 
// オープンに失敗したとき、ファイルが重複しているということなのでエラーログ出力
if ($fp === FALSE){
	date_default_timezone_set('Asia/Tokyo');
	error_log("[". date('Y-m-d H:i:s') . "]". $strFileName ."は既に存在します。\n", 3, "/opt/specialfraud/result/upload/log/error.log");
	die('ファイルが作成できません');
}
// 重複していないとき、オープンしたファイルはそのまま残しておく
fclose($fp);
// 上書きで保存する
if(move_uploaded_file($_FILES['upload']['tmp_name'], $strFilePath)){

	// ファイルのパーミッションを644に設定する
	chmod($filepath, 0644);
	// 完了メッセージを表示
	echo 'アップロード成功';
}
else{
	echo 'アップロード失敗';
}


