
/**
 * 補足情報の表示・非表示を切り替える処理のヘルパ関数
 * 　補足情報：あるイメージ＆タグに関連付いた情報（複数）のこと
 * objを起点として周辺のoptを探す
 * @param(I) isShow: 現在表示中か・非表示状態かのフラグ
 * @param(I) obj: クリックされたjQueryオブジェクト
 */
function SwOpt(isShow,obj)
{
  // まず開始ポイントを探す
  o=obj;
  if(o.is('.opt')) {
    // 自分がoptなら、上にもあるかも
    while(o[0]) {
      src=o.prev('.opt');
      if(!src[0]) break;
      o=src;
    }
    // 先頭はobj
    obj=o;
  } else {
    // 自分がoptじゃない場合、下にあるoptを探す
    o=obj.next();
    while(o[0]) {
       if(o.is('.opt')) {
         obj=o;
         break;
       }
       o=o.next();
    }
  }

  while(obj[0]) {
    
    if(isShow) {
      obj.show("slow");
    } else {
      obj.slideUp();
    }
    obj=obj.next('.opt');
  }
}

function toggleUpDown(btn)
{
	if(btn.is('.Down')) {
		flg=true;
		btn.removeClass('Down');
		btn.addClass('Up');
	} else {
		flg=false;
		btn.removeClass('Up');
		btn.addClass('Down');
	}
	return flg;
}


(function($) {

/** tag部分をクリックした場合の処理
 * 補足情報について、表示・非表示を切り替える
 */
$(document).on('click',".tag",function() {
	console.log($(this));
	btn=$(this).find('button');
	flg=toggleUpDown(btn);
	SwOpt(flg,$(this));
});

/** 編集エリアの表示・非表示を切り替える
 * @param(I) obj: クリック時のthisオブジェクト
 */
function swEditObjShow(obj,strEditTag)
{
	obj=obj.parent('tr').next(strEditTag);
	if(obj.is(":visible")) {
		obj.slideUp();
	} else {
		obj.show("slow");
		o=obj.find('input');
		if(!o[0]) o=obj.find('textarea');
		o.focus();
	}
}
/** 編集キャンセル処理
 * 編集モード:編集用テキストオブジェクト表示状態を非表示に戻す
 * 入力テキストに元のテキストを設定しておく
 * @param(I) obj: キャンセルボタン
 */
function procCancel(obj)
{
	tr=obj.parent('td').parent('tr');
	tr.slideUp();
	inp=tr.find('input');
	org=tr.prev('tr').find('td.data').text();
	inp.val(org);
}
/** テキスト更新処理
 * 入力されたテキストをDBに反映させる
 * @param(I) obj: 更新ボタン
 * @param(I) strKey: "title" or "description"
 * @param(I) whenOK: 更新処理が正常だった場合のコールバック
 *	引数には、obj(更新ボタン),val(更新したテキスト)が設定される
 */
function procUpdate(obj,strKey,whenOK)
{
	path=obj.attr('title');
	tr=obj.parent('td').parent('tr');
	inp=tr.find('input');
	if(!inp[0]) inp=tr.find('textarea');
	val=inp.val();
	var dat={};
	dat[strKey]=val;
	
	$.ajax( {
		type:"POST",
		url: "."+path,
		dataType: "json",
		data: dat,
		success: function(txt) {
			console.log(txt);
			alert(txt);
			whenOK(obj,val);
		},
		error: function(HTMLHttpRequest,textStatus,errorThrown) {
			console.log(textStatus);
			alert(textStatus);
		}
	});

}

/** タイトル部分をクリックした場合の処理
 * 編集エリアの表示・非表示を切り替える
 */
$(document).on('click',".title .data",function() {
	// edit data
	//alert("title click.");
	swEditObjShow($(this),".tEdit");
});
// キャンセルボタン押下時の処理
$(document).on('click',".tEdit .Cancel",function() {
	procCancel($(this));
});
// 更新ボタン押下時の処理
$(document).on('click',".tEdit .Update",function() {
	procUpdate($(this),"title",function(obj,txt) {
		// 成功時の処理、ここでは、元の表示エリアに更新テキストを反映する
		// obj: 元のthisオブジェクト
		// txt: 更新できたテキスト
		obj=obj.parent('td').parent('tr');
		view=obj.prev('tr').find('td.data');
		view.text(txt);
		if(obj.is(":visible")) {
			obj.slideUp();
		}
	});
});

// キャンセルボタン押下時の処理
$(document).on('click',".dEdit .Cancel",function() {
	procCancel($(this));
});
// 更新ボタン押下時の処理
$(document).on('click',".dEdit .Update",function() {
	console.log(this);
	procUpdate($(this),"descript",function(obj,txt) {
		// 成功時の処理、ここでは、元の表示エリアに更新テキストを反映する
		// obj: 元のthisオブジェクト
		// txt: 更新できたテキスト
		obj=obj.parent('td').parent('tr');
		view=obj.prev('tr').find('td.data');
		view.html(txt.replace(/\r?\n/g,"<br />"));
		if(obj.is(":visible")) {
			obj.slideUp();
		}
	});
});

// タイトル部分をクリックした場合の処理
// 編集エリアの表示・非表示を切り替える
$(document).on('click',".descript .data",function() {
	// edit data
	//alert("descript click.");
	swEditObjShow($(this),".dEdit");
});

// 補足情報表示領域をクリックした場合の処理
// 補足情報について非表示にする
$(document).on('click',".opt",function() {
	console.log(this);
	obj=$(this);
	while(obj[0]) {
		obj=obj.prev();
		if(obj.is('.tag')) break;
	}
	console.log(obj);
	btn=obj.find('button');
	toggleUpDown(btn);
	SwOpt(false,$(this));
});

})(jQuery);

