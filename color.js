jQuery(function($){
    $(".picker").spectrum({
        flat: false, // trueの場合、クリックしなくてもピッカーが表示されるようにする
        showInput: true, // コードの入力欄を表示する
        showAlpha: true, // 不透明度の選択バーを表示する
        disabled: false, // trueの場合、ピッカーを無効にする
        showPalette: true, // パレットを表示する
        showPaletteOnly: false, // true の場合、パレットのみの表示にする
        togglePaletteOnly: false, // true の場合、パレット以外の部分はボタンで表示切替する
        togglePaletteMoreText: "詳細", // togglePaletteOnlyがtrueの場合のボタン名(開く)
        togglePaletteLessText: "閉じる", // togglePaletteOnlyがtrueの場合のボタン名(閉じる)
        showSelectionPalette: true, // ユーザーが前に選択した色をパレットに表示する
        maxSelectionSize: 10, // 選択した色を記憶する数の上限
        hideAfterPaletteSelect: false, // true の場合、パレットを選んだ時点でピッカーを閉じる
        clickoutFiresChange: true, // ピッカーの外側をクリックしてピッカーを閉じた際にchangeイベントを発生させる
        showInitial: true, // 初期の色と選択した色を見比べるエリアを表示する
        allowEmpty: true, // 「指定なし」を許可する
        chooseText: "OK", // 選択ボタンのテキスト
        cancelText: "キャンセル", // キャンセルボタンのテキスト
        showButtons: true, // ボタンを表示する
        containerClassName: "full-spectrum", // ピッカーの部品を囲うタグ(要素)のクラス名
        replacerClassName: "", // ピッカーを表示させるボタンのクラス名
        preferredFormat: "hex", // カラーコードの形式を指定したものに変更する (可能な限り。hex, hex3等)
        localStorageKey: "spectrum.demo", // localStorageに選択色を記憶する際のキー
        palette: [ // パレット
            ["rgb(0, 0, 0)", "rgb(67, 67, 67)", "rgb(102, 102, 102)",
            "rgb(204, 204, 204)", "rgb(217, 217, 217)", "rgb(255, 255, 255)"],
            ["rgb(152, 0, 0)", "rgb(255, 0, 0)", "rgb(255, 153, 0)", "rgb(255, 255, 0)", "rgb(0, 255, 0)",
            "rgb(0, 255, 255)", "rgb(74, 134, 232)", "rgb(0, 0, 255)", "rgb(153, 0, 255)", "rgb(255, 0, 255)"],
            ["rgb(230, 184, 175)", "rgb(244, 204, 204)", "rgb(252, 229, 205)", "rgb(255, 242, 204)", "rgb(217, 234, 211)",
            "rgb(208, 224, 227)", "rgb(201, 218, 248)", "rgb(207, 226, 243)", "rgb(217, 210, 233)", "rgb(234, 209, 220)",
            "rgb(221, 126, 107)", "rgb(234, 153, 153)", "rgb(249, 203, 156)", "rgb(255, 229, 153)", "rgb(182, 215, 168)",
            "rgb(162, 196, 201)", "rgb(164, 194, 244)", "rgb(159, 197, 232)", "rgb(180, 167, 214)", "rgb(213, 166, 189)",
            "rgb(204, 65, 37)", "rgb(224, 102, 102)", "rgb(246, 178, 107)", "rgb(255, 217, 102)", "rgb(147, 196, 125)",
            "rgb(118, 165, 175)", "rgb(109, 158, 235)", "rgb(111, 168, 220)", "rgb(142, 124, 195)", "rgb(194, 123, 160)",
            "rgb(166, 28, 0)", "rgb(204, 0, 0)", "rgb(230, 145, 56)", "rgb(241, 194, 50)", "rgb(106, 168, 79)",
            "rgb(69, 129, 142)", "rgb(60, 120, 216)", "rgb(61, 133, 198)", "rgb(103, 78, 167)", "rgb(166, 77, 121)",
            "rgb(91, 15, 0)", "rgb(102, 0, 0)", "rgb(120, 63, 4)", "rgb(127, 96, 0)", "rgb(39, 78, 19)",
            "rgb(12, 52, 61)", "rgb(28, 69, 135)", "rgb(7, 55, 99)", "rgb(32, 18, 77)", "rgb(76, 17, 48)"]
        ],
        selectionPalette: [], // 選択色のパレットの初期値

        // 値の変更(確定)時イベント
        change: function(color){
        },
        // 値の変更(未確定)時イベント
        move: function(color){
        },
        // ピッカーが閉じた時のイベント
        hide: function(color){
        },
        // ピッカーの表示イベント
        show: function(color){
        },
        // ピッカーの表示直前のイベント。return falseでピッカーの表示を阻害できる
        beforeShow: function(color){
        },
    });
});