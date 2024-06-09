(function($) {
		/*
		* 検索機能付き セレクトボックス
		*
		* Copyright (c) 2020 iseyoshitaka
		*/
		$.fn.searchBox = function(opts) {

		// 引数に値が存在する場合、デフォルト値を上書きする
		var settings = $.extend({}, $.fn.searchBox.defaults, opts);

		var init = function (obj) {

			var self = $(obj),
				parent = self.closest('div,tr'),
				searchWord = ''; // 絞り込み文字列

			// 絞り込み検索用のテキスト入力欄の追加
			self.before('<input type="text" class="refineText formTextbox" />');
			var refineText = parent.find('.refineText');
			if (settings.mode === MODE.NORMAL) {
				refineText.attr('readonly', 'readonly');
			}

			// 初期表示で選択済みの場合、絞り込み文言入力欄に選択済みの文言を表示
			var selectedOption = self.find('option:selected');
			if(selectedOption){
				refineText.val(selectedOption.text());
				if (selectedOption.val() === '') {
					if (settings.mode === MODE.TAG) {
						refineText.val("");
					}
				}
			}

			// セレクトボックスの代わりに表示するダミーリストを作成
			var visibleTarget =self.find('option').map(function(i, e) {
				return '<li data-selected="off" data-searchval="' + $(e).val() + '"><span>' + $(e).text() + '</span></li>';
			}).get();
			self.after($('<ul class="searchBoxElement"></ul>').hide());

			// ダミーリストの表示幅をセレクトボックスにあわせる
			var refineTextWidth = (settings.elementWidth) ? settings.elementWidth : self.width();
			refineText.css('width', refineTextWidth);
			parent.find('.searchBoxElement').css('width', refineTextWidth);

			// 元のセレクトボックスは非表示にする
			self.hide();

			// ダミーリストを検索条件で絞り込みます。
			var changeSearchBoxElement = function() {
				if (searchWord !== '') {
					var matcher = new RegExp(searchWord.replace(/\\/g, '\\\\'), "i");
					var filterTarget = $(visibleTarget.join()); // 配列のコピー
					filterTarget = filterTarget.filter(function(){
						return $(this).text().match(matcher);
					});
					parent.find('.searchBoxElement').empty();
					parent.find('.searchBoxElement').html(filterTarget);
					parent.find('.searchBoxElement').show();
				} else {
					parent.find('.searchBoxElement').empty();
					parent.find('.searchBoxElement').html(visibleTarget.slice(0, settings.optionMaxSize).join(''));
					parent.find('.searchBoxElement').show();
				}

				// 選択中のLIタグの背景色を変更します。
				var selectedOption = self.find('option:selected');
				if(selectedOption){
					parent.find('.searchBoxElement').find('li').removeClass('selected');
					parent.find('.searchBoxElement').find('li[data-searchval="' + selectedOption.val() + '"]').addClass('selected');
				}

				// ダミーリスト選択時
				parent.find('.searchBoxElement').find('li').click(function(e){
					e.preventDefault();
					// e.stopPropagation();
					var li = $(this),
						searchval = li.data('searchval');
					self.val(searchval).change();
					parent.find('li').attr('data-selected', 'off');
					li.attr('data-selected', 'on');
				});

			};

			// keyup時のファンクション
			refineText.keyup(function(e){
				searchWord = $(this).val();
				// ダミーリストをリフレッシュ
				changeSearchBoxElement();
			});

			// セレクトボックス変更時
			self.change(function(){
				// 直近の絞り込み文言エリアへ選択オプションのテキストを反映
				var selectedOption = $(this).find('option:selected');
				searchWord = selectedOption.text();
				refineText.val(selectedOption.text());

				if (settings.selectCallback) {
					settings.selectCallback({
						selectVal: selectedOption.attr('value'),
						selectLabel: selectedOption.text()
					});
				}
			});

			// テキストボックスをクリックした場合はダミーリストを表示する
			refineText.click(function(e) {
				e.preventDefault();

				// モードに合わせて設定
				if (settings.mode === MODE.NORMAL) {
					searchWord = '';
				} else if (settings.mode === MODE.INPUT) {
					refineText.val('');
					searchWord = '';
				} else if (settings.mode === MODE.TAG) {
					var selectedOption = self.find('option:selected');
					if (selectedOption.val() === '') {
						refineText.val('');
						searchWord = '';
					}
				}

				// ダミーリストをリフレッシュ
				parent.find('.searchBoxElement').hide();
				changeSearchBoxElement();

			});

			// セレクトボックスの外をクリックした場合はダミーリストを非表示にする。
			$(document).click(function(e){
				if($(e.target).hasClass('refineText')){
					return;
				}
				parent.find('.searchBoxElement').hide();
				if (settings.mode !== MODE.TAG) {
					var selectedOption = self.find('option:selected');
					searchWord = selectedOption.text();
					refineText.val(selectedOption.text());
				}
			});

		}

		$(this).each(function (){
			init(this);
		});

		return this;
	}

	var MODE = {
		NORMAL: 0, // 通常のセレクトボックス
		INPUT: 1, // 入力式セレクトボックス
		TAG: 2 // タグ追加式セレクトボックス
	};

	$.fn.searchBox.defaults = {
		selectCallback: null, // 選択後に呼ばれるコールバック
		elementWidth: null, // セレクトボックスの表示幅
		optionMaxSize: 100, // セレクトボックス内に表示する最大数
		mode: MODE.INPUT // 表示モード
	};

})(jQuery);
